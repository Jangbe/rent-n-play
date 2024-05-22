<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Notifications\InvoicePaid;
use App\Notifications\OrderPlaced;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey    = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized  = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds        = config('services.midtrans.is3ds');
        \Midtrans\Config::$curlOptions[CURLOPT_SSL_VERIFYHOST] = 0;
        \Midtrans\Config::$curlOptions[CURLOPT_SSL_VERIFYPEER] = 0;
        \Midtrans\Config::$curlOptions[CURLOPT_HTTPHEADER] = [];
    }

    public function index()
    {
        $user = request()->user();
        $transactions = Transaction::with('user', 'transactionDetails.product')->latest();
        if ($user->role == 'Customer') {
            $transactions->where('user_id', $user->id);
        }
        return response()->json($transactions->get());
    }

    public function getTransactionNumber()
    {
        $last = Transaction::select('transaction_number')->latest()->first();
        $number = str($last->transaction_number ?? '0000')->substr(-4, 4)->toInteger();
        $number = str($number + 1)->prepend('000')->substr(-4, 4)->prepend('TRX' . now()->format('Ymd'));
        return response()->json($number);
    }

    public function store(Request $request)
    {
        $request->merge(['user_id' => auth()->id()]);
        $validatedData = $request->validate([
            'transaction_number' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required',
            'delivery' => 'required|boolean',
            'delivery_fee' => 'required_if:delivery,1|numeric',
            'address_id' => 'required_if:delivery,1|exists:addresses,id',
            'order_datetime' => 'required|date',
            'products' => 'required|array|min:1',
        ]);

        $snapToken = DB::transaction(function () use ($validatedData, $request) {
            $transaction = Transaction::create($validatedData);
            $transaction->products()->sync($request->products);

            $admin = User::where('role', 'Admin')->get();
            $transaction->load(['user', 'transactionDetails.product.category']);

            foreach ($admin as $a) {
                $a->notify(new OrderPlaced($transaction, $admin));
            }

            if ($transaction->payment_method == 'Transfer') {
                $payload = [
                    'transaction_details' => [
                        'order_id' => now()->unix() . '-' . $transaction->transaction_number,
                    ],
                    'customer_details' => [
                        'first_name' => $transaction->user->name,
                        'email' => $transaction->user->email
                    ],
                    'item_details' => $transaction->transactionDetails->map(function ($t) use ($transaction) {
                        return [
                            'id' => $transaction->transaction_number . '-' . $t->product->id,
                            'price' => $t->product->price,
                            'quantity' => $t->quantity,
                            'name' => $t->product->name,
                            'category' => $t->product->category->name,
                            'merchant_name' => config('app.name')
                        ];
                    })->toArray()
                ];
                if ($transaction->delivery) {
                    $payload['item_details'][] = [
                        'id' => $transaction->transaction_number . ' - Ongkir',
                        'price' => $transaction->delivery_fee,
                        'quantity' => 1,
                        'name' => 'Ongkir'
                    ];
                }

                $snapToken = \Midtrans\Snap::getSnapToken($payload);
                $transaction->update(['snap_token' => $snapToken]);

                return $snapToken;
            } else {
                return null;
            }
        });

        return response()->json([
            'message' => 'Transaksi berhasil dibuat',
            'snapToken' => $snapToken
        ], 201);
    }


    public function show($id)
    {
        $transaction = Transaction::with(['user', 'transactionDetails.product', 'address'])->where('transaction_number', $id)->firstOrFail();
        if ($transaction->user_id != auth()->id() && auth()->user()->role != 'Admin') return abort(404);
        return response()->json($transaction);
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($request->user()->role != 'Admin') return abort(404);

        $validatedData = $request->validate([
            'status' => 'required'
        ]);

        $transaction->update($validatedData);
        $transaction->user->notify(new OrderStatusUpdated($transaction->load(['user', 'address', 'transactionDetails.product'])));

        return response()->json('Status transaksi berhasil diubah');
    }

    public function midtransCallback(Request $request, Transaction $transaction)
    {
        $response = \Midtrans\Transaction::status($request->get('order_id'));
        if ($response && $response->fraud_status == 'accept') {
            $transaction->update(['status' => 'success']);
            $transaction->user->notify(new InvoicePaid($transaction->load(['user', 'address', 'transactionDetails.product'])));
        }
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return response()->json(null, 204);
    }
}

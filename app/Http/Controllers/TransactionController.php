<?php

namespace App\Http\Controllers;

use App\Events\DashboardEvent;
use App\Events\OrderStatusUpdatedEvent;
use App\Models\ExtraTime;
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
        $last = Transaction::select('transaction_number')->whereDate('created_at', now())->latest()->first();
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
            'days' => 'required|numeric|min:1',
            'products' => 'required|array|min:1',
        ]);

        $snapToken = DB::transaction(function () use ($validatedData, $request) {
            $transaction = Transaction::create($validatedData);
            $transaction->products()->sync($request->products);

            $admin = User::where('role', 'Admin')->get();
            $transaction->load(['user', 'transactionDetails.product.category', 'testimonial']);

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
                if ($transaction->days > 1) {
                    $payload['item_details'][] = [
                        'id' => $transaction->transaction_number . ' - Jumlah Hari',
                        'price' => $transaction->transactionDetails->reduce(fn ($a, $b) => $a + $b->total, 0),
                        'quantity' => $transaction->days - 1,
                        'name' => 'x hari'
                    ];
                }

                $snapToken = \Midtrans\Snap::getSnapToken($payload);
                $transaction->update(['snap_token' => $snapToken]);

                return $snapToken;
            } else {
                return null;
            }
        });
        broadcast(new DashboardEvent());

        return response()->json([
            'message' => 'Transaksi berhasil dibuat',
            'snapToken' => $snapToken
        ], 201);
    }


    public function show($id)
    {
        $transaction = Transaction::with(['user', 'address', 'extraTimes', 'transactionDetails.product', 'testimonial'])->where('transaction_number', $id)->firstOrFail();
        if ($transaction->user_id != auth()->id() && auth()->user()->role != 'Admin') return abort(404);
        return response()->json($transaction);
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($request->user()->role != 'Admin') return abort(404);

        $request->validate([
            'status' => 'required'
        ]);

        $transaction->status = $request->status;
        if ($transaction->isDirty() && $request->status == 'ongoing') {
            $transaction->order_datetime = now();
        }

        $transaction->save();
        $transaction->user->notify(new OrderStatusUpdated($transaction->load(['user', 'address', 'transactionDetails.product', 'testimonial'])));
        broadcast(new DashboardEvent());

        return response()->json('Status transaksi berhasil diubah');
    }

    public function addExtraTime(Request $request, Transaction $transaction)
    {
        $validate = $request->validate([
            'payment_method' => 'required',
            'days' => 'required|numeric'
        ]);
        $et = $transaction->extraTimes()->create($validate);
        $price = $transaction->transactionDetails->reduce(fn ($a, $b) => $a + $b->total, 0);
        if ($request->payment_method == 'Transfer') {
            $payload = [
                'transaction_details' => [
                    'order_id' => now()->unix() . "-{$et->id}-extra-time-{$transaction->transaction_number}",
                ],
                'customer_details' => [
                    'first_name' => $transaction->user->name,
                    'email' => $transaction->user->email
                ],
                'item_details' => [[
                    'id' => "{$transaction->transaction_number}-extra-time-{$et->id}",
                    'price' => $price,
                    'quantity' => $et->days,
                    'name' => "Perpanjangan Waktu $et->days hari",
                    'merchant_name' => config('app.name')
                ]]
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($payload);
            $et->update(['snap_token' => $snapToken]);
        } else {
            $snapToken = null;
        }
        broadcast(new OrderStatusUpdatedEvent($transaction));
        broadcast(new DashboardEvent());
        return response()->json([
            'snapToken' => $snapToken,
            'message' => 'Berhasil memperpanjang sewa'
        ]);
    }

    public function midtransExtraTimeCallback(Request $request, Transaction $transaction, ExtraTime $extraTime)
    {
        $response = \Midtrans\Transaction::status($request->get('order_id'));
        if ($response && $response->fraud_status == 'accept') {
            $extraTime->update(['is_paid' => true]);
            broadcast(new OrderStatusUpdatedEvent($transaction));
            broadcast(new DashboardEvent());
        }
    }
    
    public function verifyPaid(ExtraTime $extraTime)
    {
        $extraTime->update(['is_paid' => true]);
        broadcast(new OrderStatusUpdatedEvent($extraTime->transaction));
        broadcast(new DashboardEvent());
    }

    public function midtransCallback(Request $request, Transaction $transaction)
    {
        $response = \Midtrans\Transaction::status($request->get('order_id'));
        if ($response && $response->fraud_status == 'accept') {
            $transaction->update(['status' => 'paid']);
            $transaction->user->notify(new InvoicePaid($transaction->load(['user', 'address', 'transactionDetails.product', 'testimonial'])));
            broadcast(new DashboardEvent());
        }
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Notifications\OrderPlaced;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

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

        DB::transaction(function () use ($validatedData, $request) {
            $transaction = Transaction::create($validatedData);
            $transaction->products()->sync($request->products);

            $admin = User::where('role', 'Admin')->get();
            $transaction->load(['user', 'transactionDetails.product']);
            foreach ($admin as $a) {
                $a->notify(new OrderPlaced($transaction, $admin));
            }
        });

        return response()->json('Transaksi berhasil dibuat', 201);
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
        $transaction->user->notify(new OrderStatusUpdated($transaction->load(['user', 'transactionDetails.product'])));

        return response()->json('Status transaksi berhasil diubah');
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return response()->json(null, 204);
    }
}

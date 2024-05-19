<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public function index()
    {
        $transactions = Transaction::all();
        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'transaction_number' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'delivery' => 'required|boolean',
            'delivery_fee' => 'required|numeric',
            'address_id' => 'nullable|exists:addresses,id',
            'total' => 'required|numeric',
            'status' => 'required|string|max:20',
            'order_datetime' => 'required|date',
        ]);

        $transaction = Transaction::create($validatedData);
        return response()->json($transaction, 201);
    }


    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        return response()->json($transaction);
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $validatedData = $request->validate([
            'transaction_number' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'delivery' => 'required|boolean',
            'delivery_fee' => 'required|numeric',
            'address_id' => 'nullable|exists:addresses,id',
            'total' => 'required|numeric',
            'status' => 'required|string|max:20',
            'order_datetime' => 'required|date',
        ]);

        $transaction->update($validatedData);
        return response()->json($transaction);
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return response()->json(null, 204);
    }
}

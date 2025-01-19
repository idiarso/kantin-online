<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Requests\TopUpRequest;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function topUp(TopUpRequest $request)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'type' => 'topup',
                'amount' => $request->amount,
                'description' => 'Top up saldo',
                'status' => 'completed'
            ]);

            auth()->user()->increment('balance', $request->amount);

            DB::commit();
            return redirect()->route('transactions.index')
                ->with('success', 'Top up successful');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Top up failed');
        }
    }
} 
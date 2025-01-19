<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $transactions = $user->transactions()
            ->latest()
            ->paginate(10);

        return view('customer.wallet.index', compact('user', 'transactions'));
    }

    public function topup(Request $request)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:10000'], // Minimum topup 10k
        ]);

        $user = auth()->user();

        try {
            DB::beginTransaction();

            // Create pending transaction
            $transaction = $user->transactions()->create([
                'type' => 'topup',
                'amount' => $validated['amount'],
                'description' => 'Manual top-up',
                'status' => 'pending',
            ]);

            // Here you would integrate with payment gateway
            // For now, we'll just simulate successful payment
            
            // Update transaction status
            $transaction->update(['status' => 'completed']);

            // Update user balance
            $user->increment('balance', $validated['amount']);

            DB::commit();

            return back()->with('success', 'Top-up successful.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process top-up. Please try again.');
        }
    }

    public function history()
    {
        $transactions = auth()->user()
            ->transactions()
            ->latest()
            ->paginate(15);

        return view('customer.wallet.history', compact('transactions'));
    }
} 
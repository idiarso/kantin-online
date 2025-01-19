<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BalanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('customer.balance.index', compact('user', 'transactions'));
    }

    public function topup(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:10000|max:1000000',
            'payment_proof' => 'required|image|max:2048', // max 2MB
        ]);

        try {
            DB::beginTransaction();

            // Store payment proof
            $paymentProofPath = $request->file('payment_proof')
                ->store('payment_proofs', 'public');

            // Create pending transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'type' => 'topup',
                'amount' => $validated['amount'],
                'description' => 'Top up balance',
                'status' => 'pending',
                'payment_proof' => $paymentProofPath,
            ]);

            DB::commit();

            return back()->with('success', 'Top up request submitted successfully. Please wait for admin verification.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process top up request. Please try again.');
        }
    }
} 
<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function viewAny(User $user)
    {
        return true; // Semua user bisa lihat daftar transaksi mereka
    }

    public function view(User $user, Transaction $transaction)
    {
        return $user->role === 'admin' || $user->id === $transaction->user_id;
    }

    public function create(User $user)
    {
        return in_array($user->role, ['student', 'teacher']); // Untuk top-up
    }

    public function update(User $user, Transaction $transaction)
    {
        return $user->role === 'admin'; // Hanya admin yang bisa update status transaksi
    }

    public function delete(User $user, Transaction $transaction)
    {
        return false; // Tidak ada yang boleh menghapus transaksi
    }
} 
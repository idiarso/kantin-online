<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Order $order)
    {
        return $user->id === $order->user_id 
            || $user->isAdmin() 
            || $user->isKantinAdmin() 
            || $user->isKantinStaff();
    }

    public function create(User $user)
    {
        return !$user->isKantinStaff();
    }

    public function update(User $user, Order $order)
    {
        return $user->isKantinAdmin() || $user->isKantinStaff();
    }

    public function delete(User $user, Order $order)
    {
        return $user->isAdmin() || $user->isKantinAdmin();
    }
} 
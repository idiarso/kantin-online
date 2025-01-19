<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Product::class => ProductPolicy::class,
        Order::class => OrderPolicy::class,
        Transaction::class => TransactionPolicy::class,
        Category::class => CategoryPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Define gates for roles
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('seller', function ($user) {
            return $user->role === 'seller';
        });

        Gate::define('customer', function ($user) {
            return in_array($user->role, ['student', 'teacher']);
        });
    }
} 
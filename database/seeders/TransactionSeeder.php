<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        // Get users and products
        $users = User::where('role', 'student')->get();
        if ($users->isEmpty()) {
            $users = User::all(); // Fallback to all users if no students found
        }
        
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            return; // Skip if no users or products
        }

        // Create transactions for the last 7 days
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays($i);
            
            // Create 5-15 transactions per day
            $transactionsCount = rand(5, 15);
            
            for ($j = 0; $j < $transactionsCount; $j++) {
                $user = $users->random();
                
                // Calculate items and total amount first
                $itemsCount = rand(1, 3);
                $totalAmount = 0;
                $items = [];
                
                for ($k = 0; $k < $itemsCount; $k++) {
                    $product = $products->random();
                    $quantity = rand(1, 3);
                    $subtotal = $product->price * $quantity;
                    $totalAmount += $subtotal;
                    
                    $items[] = [
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ];
                }
                
                // Create transaction with total amount
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'transaction_number' => Transaction::generateTransactionNumber(),
                    'total_amount' => $totalAmount,
                    'payment_method' => collect(['cash', 'card', 'digital'])->random(),
                    'payment_status' => 'paid',
                    'status' => 'completed',
                    'paid_at' => $date,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
                
                // Create transaction items
                foreach ($items as $item) {
                    TransactionItem::create(array_merge($item, [
                        'transaction_id' => $transaction->id,
                    ]));
                }
            }
        }
    }
} 
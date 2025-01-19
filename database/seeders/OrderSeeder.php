<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $users = User::where('role', 'user')->get();
        $products = Product::all();

        // Create some sample orders
        foreach ($users as $user) {
            for ($i = 0; $i < 5; $i++) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'total_amount' => 0,
                    'status' => fake()->randomElement(['pending', 'processing', 'completed']),
                    'payment_method' => fake()->randomElement(['balance', 'cash']),
                ]);

                // Add 2-4 random products to each order
                $orderProducts = $products->random(rand(2, 4));
                $total = 0;

                foreach ($orderProducts as $product) {
                    $quantity = rand(1, 3);
                    $subtotal = $product->price * $quantity;
                    $total += $subtotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ]);
                }

                $order->update(['total_amount' => $total]);
            }
        }
    }
} 
<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', User::ROLE_USER)->get();
        if ($users->isEmpty()) {
            $users = User::all();
        }

        $products = Product::all();
        if ($products->isEmpty()) {
            return;
        }

        $statuses = [
            Order::STATUS_COMPLETED,
            Order::STATUS_COMPLETED,
            Order::STATUS_PROCESSING,
            Order::STATUS_PENDING,
            Order::STATUS_CANCELLED,
        ];

        $paymentMethods = ['Bank Transfer (BCA)', 'QRIS / GoPay', 'Credit Card', 'COD'];

        // Create 25 realistic orders
        for ($i = 1; $i <= 25; $i++) {
            $user = $users->random();
            $status = $statuses[array_rand($statuses)];
            $createdAt = now()->subDays(rand(1, 45))->subHours(rand(1, 23));

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(8)) . '-' . str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                'total_amount' => 0,
                'status' => $status,
                'shipping_address' => "Jl. Sudirman No. {$i}, RT 0" . rand(1, 9) . "/RW 0" . rand(1, 9) . ", Jakarta Selatan, DKI Jakarta 12190",
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'payment_status' => $status === Order::STATUS_CANCELLED ? 'cancelled' : ($status === Order::STATUS_PENDING ? 'unpaid' : 'paid'),
                'notes' => rand(0, 1) ? 'Tolong packing kayu dan bubble wrap tebal ya min, terima kasih!' : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Select 1 to 4 random products for this order
            $orderProducts = $products->random(rand(1, 4));
            $orderTotal = 0;

            foreach ($orderProducts as $product) {
                $qty = rand(1, 3);
                $subtotal = $product->price * $qty;
                $orderTotal += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            // Update order total
            $order->update(['total_amount' => $orderTotal]);
        }
    }
}

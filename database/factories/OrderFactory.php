<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'total_amount' => 0,
            'status' => fake()->randomElement([
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_COMPLETED,
                Order::STATUS_CANCELLED,
            ]),
            'shipping_address' => fake()->address(),
            'payment_method' => fake()->randomElement(['Bank Transfer', 'Credit Card', 'QRIS / GoPay', 'COD']),
            'payment_status' => fake()->randomElement(['paid', 'unpaid']),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => Order::STATUS_COMPLETED,
            'payment_status' => 'paid',
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => Order::STATUS_PENDING,
            'payment_status' => 'unpaid',
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->randomElement([
                'Kualitas barang sangat bagus dan pengiriman super cepat! Sangat recommended.',
                'Produk original, packing rapi menggunakan bubble wrap tebal. Mantap!',
                'Barang sesuai ekspektasi, berfungsi dengan baik dan garansi resmi.',
                'Cukup bagus untuk harga segini, recommended seller!',
                'Respon penjual sangat ramah dan informatif. Akan belanja lagi di sini.',
                'Barang sampai dengan selamat tanpa ada cacat sedikitpun. Terima kasih!',
                'Bahan berkualitas, jahitan rapi, ukuran pas banget di badan.',
                'Build quality premium, suara jernih dan bass mantap!',
            ]),
            'is_approved' => true,
        ];
    }
}

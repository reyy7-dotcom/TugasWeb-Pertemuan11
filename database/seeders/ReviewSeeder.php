<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        $comments5Stars = [
            'Produk luar biasa! Original 100%, garansi resmi aktif. Pengiriman super kilat!',
            'Sangat puas berbelanja di sini! Kualitas build sangat solid dan packing sangat aman.',
            'Barang sampai dengan mulus. Performa kencang sesuai review di YouTube. Recommended seller!',
            'Kualitas audio dan baterai sangat tahan lama, bass menggelegar dan vokal jernih sekali.',
            'Desainnya mewah banget, ringan tapi terasa premium. Tidak menyesal beli ini.',
            'Barang istimewa, pelayanan toko sangat responsif dan ramah saat konsultasi spek.',
        ];

        $comments4Stars = [
            'Barang bagus dan berfungsi normal. Cuma kurirnya agak lama sampainya.',
            'Kualitas oke sesuai deskripsi, kelengkapan fullset original.',
            'Sangat memuaskan untuk pemakaian sehari-hari, harga bersahabat.',
            'Overall mantap, packaging aman pakai bubble tebal.',
        ];

        $comments3Stars = [
            'Cukup oke, ada sedikit kendala setting awal tapi akhirnya bisa.',
            'Produk standar sesuai harga, tidak ada yang terlalu istimewa tapi berfungsi.',
        ];

        // Seed 60+ realistic reviews distributed across products
        for ($i = 0; $i < 65; $i++) {
            $product = $products->random();
            $user = $users->random();

            $ratingRoll = rand(1, 10);
            if ($ratingRoll <= 6) {
                $rating = 5;
                $comment = $comments5Stars[array_rand($comments5Stars)];
            } elseif ($ratingRoll <= 9) {
                $rating = 4;
                $comment = $comments4Stars[array_rand($comments4Stars)];
            } else {
                $rating = 3;
                $comment = $comments3Stars[array_rand($comments3Stars)];
            }

            Review::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'rating' => $rating,
                'comment' => $comment,
                'is_approved' => true,
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}

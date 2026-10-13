<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Smartphone & Tablet',
                'description' => 'Koleksi smartphone flagship, mid-range, dan tablet terkini dari brand terkemuka.',
                'icon' => 'device-phone-mobile',
            ],
            [
                'name' => 'Laptop & Komputer',
                'description' => 'Laptop produktivitas, laptop gaming, ultrabook, dan perangkat desktop canggih.',
                'icon' => 'computer-desktop',
            ],
            [
                'name' => 'Audio & Headphone',
                'description' => 'TWS earbuds, headphone active noise-cancelling, dan speaker portabel audiophile.',
                'icon' => 'speaker-wave',
            ],
            [
                'name' => 'Smartwatch & Wearable',
                'description' => 'Jam tangan pintar fitness tracker, smartwatch premium, dan aksesoris kesehatan pintar.',
                'icon' => 'clock',
            ],
            [
                'name' => 'Aksesoris & Periferal',
                'description' => 'Mechanical keyboard, mouse ergonomis, monitor 4K, charger GaN, dan kabel berkualitas.',
                'icon' => 'cpu-chip',
            ],
            [
                'name' => 'Fashion & Lifestyle',
                'description' => 'Pakaian kasual, jaket streetwear, tas laptop anti air, dan sepatu sneakers premium.',
                'icon' => 'sparkles',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'icon' => $cat['icon'],
                    'is_active' => true,
                ]
            );
        }
    }
}

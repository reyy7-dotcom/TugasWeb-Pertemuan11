<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@ecommerce.test')->first() ?? User::where('role', User::ROLE_ADMIN)->first();
        $editor = User::where('email', 'editor@ecommerce.test')->first() ?? User::where('role', User::ROLE_EDITOR)->first();

        $postsData = [
            [
                'author' => $admin,
                'title' => 'Panduan Memilih Gadget & Laptop Terbaik untuk Mahasiswa Teknik 2026',
                'excerpt' => 'Tips komprehensif menentukan spesifikasi prosesor, RAM, dan kartu grafis yang tepat untuk kebutuhan coding, multimedia, dan produktivitas harian.',
                'content' => "Memasuki era kecerdasan buatan dan komputasi modern, kebutuhan laptop bagi mahasiswa teknik semakin menuntut spesifikasi yang mumpuni. Dalam artikel ini, kita mengupas tuntas mengapa kapasitas RAM minimal 16GB dan SSD berkecepatan tinggi menjadi standar mutlak.\n\nSelain itu, pemilihan GPU diskrit seperti NVIDIA GeForce RTX seri 40 terbukti meningkatkan efisiensi pelatihan model AI dan simulasi grafis secara signifikan. Simak rekomendasi perangkat unggulan kami di katalog untuk penawaran terbaik!",
                'image_url' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'author' => $editor,
                'title' => 'Review Mendalam: Mengapa Layar OLED Menjadi Standar Baru Monitor Gaming',
                'excerpt' => 'Eksplorasi mendalam mengenai teknologi panel OLED, tingkat kontras tak terhingga, waktu respons sub-milidetik, serta perbandingannya dengan IPS.',
                'content' => "Monitor gaming telah berevolusi dari era TN panel ke IPS, dan kini puncak performa visual didominasi oleh panel QD-OLED dan WOLED. Dengan pixel response time hingga 0.03ms, motion blur praktis hilang sepenuhnya.\n\nDitambah dengan reproduksi warna hitam sempurna (true black) berkat kontrol pencahayaan per-pixel mandiri, pengalaman bermain game AAA dan menonton konten HDR terasa sangat hidup dan imersif.",
                'image_url' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'author' => $editor,
                'title' => 'Tips Merawat Mechanical Keyboard Agar Tetap Awet dan Responsif',
                'excerpt' => 'Cara membersihkan switch keyboard, melumasi stabilizer, dan merawat keycap PBT agar ketikan tetap empuk dan tidak berdebu.',
                'content' => "Mechanical keyboard custom adalah investasi jangka panjang untuk kenyamanan mengetik berjam-jam. Langkah pertama dalam perawatannya adalah membersihkan sela-sela switch menggunakan kuas antistatis dan blower mini secara berkala.\n\nJangan lupa mencuci keycap PBT menggunakan air hangat dan sabun lembut, serta lakukan relubing stabilizer setiap 6 bulan sekali untuk mencegah rattle atau suara oblak yang mengganggu.",
                'image_url' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&auto=format&fit=crop&q=80',
            ],
            [
                'author' => $admin,
                'title' => 'Pengumuman Resmi: Promo Flash Sale Spesial Pertemuan 11',
                'excerpt' => 'Nikmati diskon hingga 40% untuk kategori Aksesoris & Gadget Flagship pilihan selama pekan ini!',
                'content' => "Sebagai bentuk apresiasi kepada seluruh pelanggan setia kami, kami menghadirkan pesta diskon spektakuler Flash Sale. Dapatkan penawaran harga spesial untuk produk pilihan seperti Apple AirPods Pro, Sony WH-1000XM5, dan Logitech MX Master 3S.\n\nStok sangat terbatas! Segera checkout keranjang belanja Anda sebelum periode promo berakhir.",
                'image_url' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=800&auto=format&fit=crop&q=80',
            ],
        ];

        foreach ($postsData as $index => $data) {
            $user = $data['author'] ?? $admin;
            Post::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'user_id' => $user->id,
                    'title' => $data['title'],
                    'excerpt' => $data['excerpt'],
                    'content' => $data['content'],
                    'image_url' => $data['image_url'],
                    'is_published' => true,
                    'published_at' => now()->subDays($index * 2),
                ]
            );
        }
    }
}

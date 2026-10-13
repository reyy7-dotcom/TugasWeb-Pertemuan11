<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Katalog Produk</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
            <div class="mb-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 p-4 rounded-lg">{{ session('status') }}</div>
            @endif

            <!-- Search & Filter -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..." class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                    <select name="category" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <select name="sort" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                    </select>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">Cari</button>
                </form>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden hover:shadow-md transition">
                    <div class="h-48 bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                        <span class="text-white text-4xl">🛍️</span>
                    </div>
                    <div class="p-5">
                        <span class="text-xs px-2 py-1 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300">{{ $product->category->name ?? '-' }}</span>
                        <h3 class="mt-2 font-semibold text-gray-800 dark:text-gray-200 line-clamp-2">
                            <a href="{{ route('products.show', $product) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">{{ $product->name }}</a>
                        </h3>
                        <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400 mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <div class="flex items-center justify-between mt-3">
                            <div class="flex items-center text-sm text-yellow-500">
                                ⭐ {{ number_format($product->reviews_avg_rating ?? 0, 1) }}
                                <span class="text-gray-400 dark:text-gray-500 ml-1">({{ $product->reviews_count ?? 0 }})</span>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full {{ $product->stock > 0 ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' : 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300' }}">
                                {{ $product->stock > 0 ? "Stok: {$product->stock}" : 'Habis' }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12 text-gray-500 dark:text-gray-400">Tidak ada produk ditemukan.</div>
                @endforelse
            </div>

            <div class="mt-6">{{ $products->links() }}</div>
        </div>
    </div>
</x-app-layout>


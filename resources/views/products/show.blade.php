<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $product->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
            <div class="mb-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 p-4 rounded-lg">{{ session('status') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Product Image -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                    <div class="h-96 bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                        <span class="text-white text-6xl">🛍️</span>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <span class="text-sm px-3 py-1 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300">{{ $product->category->name ?? '-' }}</span>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mt-3">{{ $product->name }}</h1>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mt-3">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                    <div class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <p><span class="font-semibold">SKU:</span> {{ $product->sku ?? '-' }}</p>
                        <p><span class="font-semibold">Stok:</span>
                            <span class="px-2 py-0.5 rounded text-xs {{ $product->stock > 0 ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' : 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300' }}">
                                {{ $product->stock > 0 ? "{$product->stock} unit" : 'Habis' }}
                            </span>
                        </p>
                        <p><span class="font-semibold">Rating:</span> ⭐ {{ number_format($product->reviews_avg_rating ?? 0, 1) }} ({{ $product->reviews_count ?? 0 }} ulasan)</p>
                    </div>

                    <div class="mt-4 text-gray-700 dark:text-gray-300">
                        <h3 class="font-semibold mb-1">Deskripsi:</h3>
                        <p class="text-sm leading-relaxed">{{ $product->description }}</p>
                    </div>

                    <!-- Quick Order Form -->
                    @auth
                    @if($product->stock > 0)
                    <div class="mt-6 border-t dark:border-gray-700 pt-6">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-3">🛒 Pesan Sekarang</h3>
                        <form method="POST" action="{{ route('orders.store') }}" class="space-y-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah</label>
                                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                                @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Pengiriman</label>
                                <textarea name="shipping_address" rows="2" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Metode Pembayaran</label>
                                <select name="payment_method" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                                    <option>Bank Transfer (BCA)</option>
                                    <option>QRIS / GoPay</option>
                                    <option>Credit Card</option>
                                    <option>COD</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Catatan (opsional)</label>
                                <input type="text" name="notes" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition font-semibold">Beli Sekarang</button>
                        </form>
                    </div>
                    @endif
                    @else
                    <p class="mt-6 text-sm text-gray-500 dark:text-gray-400"><a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 font-semibold hover:underline">Login</a> untuk memesan produk ini.</p>
                    @endauth
                </div>
            </div>

            <!-- Reviews -->
            <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Ulasan Pelanggan ({{ $product->reviews_count ?? 0 }})</h3>
                @forelse($product->reviews as $review)
                <div class="border-b dark:border-gray-700 pb-4 mb-4 last:border-0 last:pb-0 last:mb-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $review->user->name ?? 'Anonim' }}</span>
                            <span class="ml-2 text-yellow-500">{{ str_repeat('⭐', $review->rating) }}</span>
                        </div>
                        <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $review->comment }}</p>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400">Belum ada ulasan untuk produk ini.</p>
                @endforelse
            </div>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Produk Terkait</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($relatedProducts as $related)
                    <a href="{{ route('products.show', $related) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 hover:shadow-md transition">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm line-clamp-2">{{ $related->name }}</h4>
                        <p class="text-indigo-600 dark:text-indigo-400 font-bold mt-1">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>


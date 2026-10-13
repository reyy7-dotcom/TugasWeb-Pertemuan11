<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $post->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
            <div class="mb-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 p-4 rounded-lg">{{ session('status') }}</div>
            @endif

            <article class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                <div class="h-64 bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                    <span class="text-white text-6xl">📝</span>
                </div>
                <div class="p-8">
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                        <span>Oleh <strong class="text-gray-700 dark:text-gray-300">{{ $post->user->name ?? 'Anonim' }}</strong></span>
                        <span class="mx-2">•</span>
                        <span>{{ $post->published_at?->format('d F Y') ?? $post->created_at->format('d F Y') }}</span>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-4">{{ $post->title }}</h1>

                    @if($post->excerpt)
                    <p class="text-lg text-gray-600 dark:text-gray-400 italic mb-6 border-l-4 border-indigo-500 pl-4">{{ $post->excerpt }}</p>
                    @endif

                    <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $post->content }}</div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex items-center gap-4 border-t dark:border-gray-700 pt-6">
                        <a href="{{ route('posts.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">← Kembali ke Blog</a>
                        @can('update', $post)
                        <a href="{{ route('posts.edit', $post) }}" class="text-sm bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 transition">✏️ Edit</a>
                        @endcan
                        @can('delete', $post)
                        <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">🗑️ Hapus</button>
                        </form>
                        @endcan
                    </div>
                </div>
            </article>

            <!-- Recent Posts -->
            @if($recentPosts->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Artikel Lainnya</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($recentPosts as $recent)
                    <a href="{{ route('posts.show', $recent) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 hover:shadow-md transition">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm line-clamp-2">{{ $recent->title }}</h4>
                        <p class="text-xs text-gray-400 mt-1">{{ $recent->published_at?->format('d M Y') }}</p>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>


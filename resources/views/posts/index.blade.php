<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Blog & Artikel</h2>
            @can('create', App\Models\Post::class)
            <a href="{{ route('posts.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700 transition">✍️ Tulis Artikel</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
            <div class="mb-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 p-4 rounded-lg">{{ session('status') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($posts as $post)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                    <div class="h-40 bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                        <span class="text-white text-4xl">📝</span>
                    </div>
                    <div class="p-5">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 line-clamp-2">
                            <a href="{{ route('posts.show', $post) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">{{ $post->title }}</a>
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 line-clamp-3">{{ $post->excerpt }}</p>
                        <div class="flex items-center justify-between mt-4 text-xs text-gray-400">
                            <span>{{ $post->user->name ?? 'Anonim' }}</span>
                            <span>{{ $post->published_at?->format('d M Y') ?? $post->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12 text-gray-500 dark:text-gray-400">Belum ada artikel.</div>
                @endforelse
            </div>

            <div class="mt-6">{{ $posts->links() }}</div>
        </div>
    </div>
</x-app-layout>


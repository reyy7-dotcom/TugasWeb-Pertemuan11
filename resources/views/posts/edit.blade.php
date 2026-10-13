<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">✏️ Edit Artikel</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <form method="POST" action="{{ route('posts.update', $post) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Artikel</label>
                        <input type="text" name="title" value="{{ old('title', $post->title) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ringkasan</label>
                        <textarea name="excerpt" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">{{ old('excerpt', $post->excerpt) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konten Artikel</label>
                        <textarea name="content" rows="10" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">{{ old('content', $post->content) }}</textarea>
                        @error('content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL Gambar</label>
                        <input type="url" name="image_url" value="{{ old('image_url', $post->image_url) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm">
                    </div>
                    <div class="flex items-center">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1" {{ $post->is_published ? 'checked' : '' }} id="is_published" class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm">
                        <label for="is_published" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Publikasikan</label>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition font-semibold">Simpan Perubahan</button>
                        <a href="{{ route('posts.show', $post) }}" class="px-6 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


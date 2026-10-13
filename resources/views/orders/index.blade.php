<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">📦 Riwayat Pesanan Saya</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
            <div class="mb-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 p-4 rounded-lg">{{ session('status') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No. Pesanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($orders as $order)
                        <tr>
                            <td class="px-6 py-4 text-sm font-mono text-gray-800 dark:text-gray-200">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <span class="text-xs px-2 py-1 rounded-full
                                    @if($order->status === 'completed') bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300
                                    @elseif($order->status === 'processing') bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300
                                    @elseif($order->status === 'pending') bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300
                                    @else bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300
                                    @endif">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-800 dark:text-gray-200">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('orders.show', $order) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada pesanan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $orders->links() }}</div>
        </div>
    </div>
</x-app-layout>


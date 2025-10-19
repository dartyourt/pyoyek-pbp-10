<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4 flex gap-2">
                        <select name="status" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm px-3 py-2">
                            <option value="">All Status</option>
                            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Filter</button>
                        @if(request('status'))
                            <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Clear</a>
                        @endif
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto bg-white dark:bg-gray-800">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700">
                                    <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Order ID</th>
                                    <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Customer</th>
                                    <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Total</th>
                                    <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Status</th>
                                    <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Date</th>
                                    <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($orders as $order)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">#{{ $order->id }}</td>
                                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $order->user->name }}</td>
                                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 rounded text-xs 
                                                @if($order->status == 'diproses') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($order->status == 'dikirim') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @elseif($order->status == 'selesai') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $order->created_at->format('d M Y') }}</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 dark:text-blue-400 hover:underline mr-2">View</a>
                                            <a href="{{ route('admin.orders.edit', $order) }}" class="text-yellow-600 dark:text-yellow-400 hover:underline">Update Status</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">No orders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
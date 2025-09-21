<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">My Orders</h1>

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($orders->count() > 0)
                        <div class="space-y-4">
                            @foreach($orders as $order)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900">
                                                    Order #{{ $order->id }}
                                                </h3>
                                                <p class="text-sm text-gray-500">
                                                    Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <div class="text-lg font-bold text-gray-900">
                                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                            </div>
                                            <div class="mt-1">
                                                @if($order->status === 'pending')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Pending
                                                    </span>
                                                @elseif($order->status === 'processing')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Processing
                                                    </span>
                                                @elseif($order->status === 'shipped')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        Shipped
                                                    </span>
                                                @elseif($order->status === 'delivered')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Delivered
                                                    </span>
                                                @elseif($order->status === 'cancelled')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Cancelled
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <div class="text-sm text-gray-600">
                                            <strong>Payment:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                                        </div>
                                        <div class="text-sm text-gray-600 mt-1">
                                            <strong>Shipping to:</strong> {{ Str::limit($order->shipping_address, 50) }}
                                        </div>
                                    </div>

                                    <div class="mt-4 flex justify-between items-center">
                                        <div class="text-sm text-gray-500">
                                            {{ $order->orderItems->count() }} item{{ $order->orderItems->count() > 1 ? 's' : '' }}
                                        </div>
                                        <a href="{{ route('orders.show', $order) }}"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <!-- No Orders -->
                        <div class="text-center py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">No orders yet</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                When you place an order, it will appear here.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('catalog.index') }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Start Shopping
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
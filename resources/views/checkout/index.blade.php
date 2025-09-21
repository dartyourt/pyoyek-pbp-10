<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">Checkout</h1>

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Order Summary -->
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h2>
                            <div class="space-y-4">
                                @foreach($cartItems as $item)
                                    <div class="flex items-center space-x-4 border-b border-gray-200 pb-4">
                                        <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded-md overflow-hidden">
                                            @if($item->product->image_path)
                                                <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product->name }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">
                                                    No Image
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium text-gray-900">{{ $item->product->name }}</h3>
                                            <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                            <p class="text-sm font-medium text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900">
                                                Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 border-t border-gray-200 pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-medium text-gray-900">Total:</span>
                                    <span class="text-2xl font-bold text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Checkout Form -->
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Shipping & Payment Information</h2>
                            <form action="{{ route('checkout.store') }}" method="POST">
                                @csrf

                                <!-- Shipping Address -->
                                <div class="mb-6">
                                    <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">
                                        Shipping Address *
                                    </label>
                                    <textarea id="shipping_address" name="shipping_address" rows="4"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Enter your complete shipping address..."
                                        required>{{ old('shipping_address') }}</textarea>
                                    @error('shipping_address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Payment Method -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Payment Method *
                                    </label>
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <input id="cash_on_delivery" name="payment_method" type="radio" value="cash_on_delivery"
                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                                @checked(old('payment_method', 'cash_on_delivery') === 'cash_on_delivery') required>
                                            <label for="cash_on_delivery" class="ml-2 block text-sm text-gray-900">
                                                Cash on Delivery
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="bank_transfer" name="payment_method" type="radio" value="bank_transfer"
                                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                                @checked(old('payment_method') === 'bank_transfer')>
                                            <label for="bank_transfer" class="ml-2 block text-sm text-gray-900">
                                                Bank Transfer
                                            </label>
                                        </div>
                                    </div>
                                    @error('payment_method')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Notes -->
                                <div class="mb-6">
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Order Notes (Optional)
                                    </label>
                                    <textarea id="notes" name="notes" rows="3"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Any special instructions for delivery...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('cart.index') }}"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Back to Cart
                                    </a>

                                    <button type="submit"
                                        class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Place Order
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
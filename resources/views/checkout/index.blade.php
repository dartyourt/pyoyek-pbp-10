<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">Checkout</h1>

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
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
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium text-gray-900">{{ $item->product->name }}</h3>
                                            <p class="text-sm text-gray-500">Qty: {{ $item->qty }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900">
                                                Rp {{ number_format($item->qty * $item->price, 0, ',', '.') }}
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
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Shipping & Payment</h2>
                            <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- Shipping Address -->
                                <div class="mb-4">
                                    <label for="shipping_address" class="block text-sm font-medium text-gray-700">Shipping Address *</label>
                                    <textarea id="shipping_address" name="shipping_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('shipping_address') }}</textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="shipping_city" class="block text-sm font-medium text-gray-700">City *</label>
                                        <input type="text" id="shipping_city" name="shipping_city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('shipping_city') }}" required>
                                    </div>
                                    <div>
                                        <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700">Postal Code *</label>
                                        <input type="number" id="shipping_postal_code" name="shipping_postal_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('shipping_postal_code') }}" min="0" max="99999" required oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="5">
                                    </div>
                                </div>

                                <!-- Payment Proof -->
                                <div class="mb-6">
                                    <label for="payment_proof" class="block text-sm font-medium text-gray-700">Proof of Payment *</label>
                                    <input type="file" id="payment_proof" name="payment_proof" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                                    @error('payment_proof')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="border-t pt-6">
                                    <button type="submit"
                                        class="w-full flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700">
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

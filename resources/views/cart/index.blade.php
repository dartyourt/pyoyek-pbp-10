<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Your Shopping Cart</h1>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($cart && $cart->cartItems->count() > 0)
                        <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                            <div class="inline-block min-w-full shadow-md rounded-lg overflow-hidden">
                                <table class="min-w-full leading-normal">
                                    <thead>
                                        <tr>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Product</th>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $subtotal = 0; @endphp
                                        @foreach ($cart->cartItems as $item)
                                            @php $subtotal += $item->price * $item->qty; @endphp
                                            <tr data-product-id="{{ $item->product->id }}" data-cart-item-id="{{ $item->id }}">
                                                <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 w-16 h-16">
                                                            <img class="w-full h-full rounded-md object-cover" src="{{ $item->product->image_path ? asset('storage/' . $item->product->image_path) : asset('images/default-product.png') }}" alt="{{ $item->product->name }}" />
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="text-gray-900 dark:text-white whitespace-no-wrap font-semibold">{{ $item->product->name }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                                    <p class="text-gray-900 dark:text-white whitespace-no-wrap">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                                </td>
                                                <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="flex justify-center" data-ajax="cart-update">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input name="qty" type="number" value="{{ $item->qty }}" min="1" class="w-16 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-center">
                                                        <button type="submit" class="ml-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-xs font-bold">UPDATE</button>
                                                    </form>
                                                </td>
                                                <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-right">
                                                    <p class="text-gray-900 dark:text-white whitespace-no-wrap font-semibold cart-row-total">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</p>
                                                </td>
                                                <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-center">
                                                    <form action="{{ route('cart.destroy', $item) }}" method="POST" data-ajax="cart-remove">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                                        <button type="submit" class="text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400" title="Remove item">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Order summary -->
                        <div class="mt-6 flex justify-end">
                            <div class="w-full max-w-sm">
                                <div class="flex justify-between text-lg font-medium text-gray-900 dark:text-white">
                                    <p>Subtotal</p>
                                    <p class="cart-subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                </div>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Shipping and taxes calculated at checkout.</p>
                                <div class="mt-6">
                                    <a href="{{ route('checkout.index') }}" class="w-full flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700">Checkout</a>
                                </div>
                                <div class="mt-6 flex justify-center text-center text-sm text-gray-500 dark:text-gray-400">
                                    <p>or <a href="{{ route('catalog.index') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">Continue Shopping<span aria-hidden="true"> &rarr;</span></a></p>
                                </div>
                            </div>
                        </div>

                    @else
                        <div class="text-center py-12">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Your cart is empty.</h2>
                            <p class="mt-2 text-gray-500 dark:text-gray-400">Looks like you haven't added anything to your cart yet.</p>
                            <div class="mt-6">
                                <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
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
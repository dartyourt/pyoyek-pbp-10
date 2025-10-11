<div x-data="miniCart()" class="relative ms-4">
    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none" title="Cart">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
            <path d="M16 11V7a4 4 0 00-4-4H6a4 4 0 100 8h6v2a1 1 0 001 1h1a1 1 0 100-2h-1v-1z" />
        </svg>
        <span class="ms-2 text-sm font-medium text-gray-700" x-text="items_count"></span>
    </button>

    <!-- dropdown preview -->
    <div x-show="items_count > 0" x-cloak class="absolute right-0 mt-2 w-64 bg-white border rounded shadow-lg z-50">
        <div class="p-4">
            <div class="text-sm font-medium text-gray-900">Cart Preview</div>
            <div class="mt-2 text-sm text-gray-600" x-show="items.length > 0">
                <template x-for="item in items" :key="item.id">
                    <div class="flex items-center justify-between py-2 border-b last:border-b-0">
                        <div class="text-sm" x-text="item.product_name"></div>
                        <div class="text-sm font-semibold" x-text="formatCurrency(item.total)"></div>
                    </div>
                </template>
            </div>

            <div class="mt-3 flex items-center justify-between">
                <div class="text-sm font-medium">Subtotal</div>
                <div class="text-sm font-semibold" x-text="formatCurrency(subtotal)"></div>
            </div>

            <div class="mt-3">
                <a href="{{ route('cart.index') }}" class="block w-full text-center px-3 py-2 bg-indigo-600 text-white rounded">View Cart</a>
            </div>
        </div>
    </div>
</div>

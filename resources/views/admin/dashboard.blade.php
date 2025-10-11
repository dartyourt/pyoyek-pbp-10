<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __('UMKM Mini-Commerce Admin') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <!-- Users Card -->
                        <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg shadow">
                            <h4 class="font-semibold text-blue-800 dark:text-blue-200">Users</h4>
                            <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $userCount ?? 0 }}</p>
                        </div>
                        
                        <!-- Categories Card -->
                        <div class="bg-green-100 dark:bg-green-900 p-4 rounded-lg shadow">
                            <h4 class="font-semibold text-green-800 dark:text-green-200">Categories</h4>
                            <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $categoryCount ?? 0 }}</p>
                        </div>
                        
                        <!-- Products Card -->
                        <div class="bg-yellow-100 dark:bg-yellow-900 p-4 rounded-lg shadow">
                            <h4 class="font-semibold text-yellow-800 dark:text-yellow-200">Products</h4>
                            <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ $productCount ?? 0 }}</p>
                        </div>
                        
                        <!-- Orders Card -->
                        <div class="bg-purple-100 dark:bg-purple-900 p-4 rounded-lg shadow">
                            <h4 class="font-semibold text-purple-800 dark:text-purple-200">Orders</h4>
                            <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ $orderCount ?? 0 }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Quick Links -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-semibold mb-2">Quick Links</h4>
                            <ul class="space-y-2">
                                <li>
                                    <a href="{{ route('admin.categories.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                        Manage Categories
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.products.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                        Manage Products
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.orders.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                        Manage Orders
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('catalog.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                        View Catalog
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- System Info -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-semibold mb-2">System Information</h4>
                            <ul class="text-sm space-y-1">
                                <li>Laravel Version: {{ app()->version() }}</li>
                                <li>PHP Version: {{ phpversion() }}</li>
                                <li>Environment: {{ app()->environment() }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
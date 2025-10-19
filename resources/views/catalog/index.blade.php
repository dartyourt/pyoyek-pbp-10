<x-app-layout>

    <!-- Hero Header Carousel -->
    <div x-data="{
        slides: [
            { 
                image: '{{ asset('storage/ui/header.jpeg') }}', 
                slogan: 'Cita Rasa Asli Nusantara', 
                subSlogan: 'Kualitas Mendunia' 
            },
            { 
                image: '{{ asset('storage/ui/header3.png') }}', 
                slogan: 'Pedasnya Khas', 
                subSlogan: 'Gurihnya Juara' 
            },
            { 
                image: '{{ asset('storage/ui/header4.png') }}', 
                slogan: 'Elegansi Warisan Bangsa', 
                subSlogan: 'Gaya Masa Kini' 
            }
        ],
        active: 0,
        loop() {
            setInterval(() => { this.active = (this.active + 1) % this.slides.length }, 3000)
        }
    }" x-init="loop()" class="relative h-80 md:h-96 overflow-hidden">
        <!-- Slides -->
        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="active === index" 
                 class="absolute inset-0 bg-cover bg-center animate-ken-burns"
                 :style="`background-image: url('${slide.image}');`"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-700"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                
                <div class="absolute inset-0 bg-black opacity-40"></div>

                <div class="relative h-full flex flex-col justify-center items-center text-center text-white">
                    <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight" x-text="slide.slogan"></h1>
                    <p class="mt-2 text-xl md:text-2xl text-gray-200" x-text="slide.subSlogan"></p>
                </div>
            </div>
        </template>

        <!-- Navigation Dots -->
        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex space-x-3">
            <template x-for="(slide, index) in slides" :key="index">
                <button @click="active = index" 
                        class="w-3 h-3 rounded-full transition-colors duration-300"
                        :class="{'bg-white': active === index, 'bg-white/50 hover:bg-white': active !== index}"></button>
            </template>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Search and Filters -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-12">
                <div class="p-6 bg-white dark:bg-gray-800">
                    <form action="{{ route('catalog.index') }}" method="GET" class="space-y-4">
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Search -->
                            <div class="flex-1">
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                                <input type="text" name="search" id="search" value="{{ $search ?? '' }}" 
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                    placeholder="Search products...">
                            </div>
                            
                            <!-- Category Filter -->
                            <div class="w-full md:w-1/4">
                                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                                <select name="category" id="category" 
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected($categoryId == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Sort Order -->
                            <div class="w-full md:w-1/4">
                                <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort By</label>
                                <select name="sort" id="sort" 
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                    <option value="newest" @selected($sort === 'newest')>Newest</option>
                                    <option value="price_low" @selected($sort === 'price_low')>Price: Low to High</option>
                                    <option value="price_high" @selected($sort === 'price_high')>Price: High to Low</option>
                                    <option value="name" @selected($sort === 'name')>Name</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-primary-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                View Matches
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Promotional Sections (only show when no filters are active) -->
            @if(!request('search') && !request('category') && !request('sort'))
            <div class="my-16 space-y-16 mb-12">
                <!-- Fashion Section -->
                @if($fashionProducts->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 items-center">
                        <div class="order-2 md:order-1 p-8 md:p-12">
                            <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Gaya & Elegansi Batik</h2>
                            <p class="mt-4 text-gray-600 dark:text-gray-300">Setiap helai menceritakan sebuah kisah. Temukan koleksi fashion batik kami yang memadukan tradisi dengan gaya modern.</p>
                            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($fashionProducts as $product)
                                    <div class="border dark:border-gray-700 rounded-lg p-3 hover:shadow-md transition-shadow">
                                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="h-32 w-full object-cover rounded-md mb-3">
                                        <h4 class="font-semibold text-sm dark:text-white">{{ $product->name }}</h4>
                                        <p class="text-primary-600 font-bold text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('catalog.index', ['category' => $fashionCategory->id]) }}" class="mt-6 inline-block bg-primary-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-primary-700 transition-colors">
                                Shop Fashion
                            </a>
                        </div>
                        <div class="order-1 md:order-2">
                            <img src="{{ asset('storage/ui/model1.png') }}" alt="Model wearing Batik" class="h-full w-full object-cover">
                        </div>
                    </div>
                </div>
                @endif

                <!-- Food Section -->
                @if($foodProducts->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 items-center">
                        <div>
                            <img src="{{ asset('storage/ui/model3.png') }}" alt="Model with Food" class="h-full w-full object-cover">
                        </div>
                        <div class="p-8 md:p-12">
                            <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Cita Rasa Khas Nusantara</h2>
                            <p class="mt-4 text-gray-600 dark:text-gray-300">Dari biji kopi pilihan hingga sambal warisan, nikmati rasa otentik yang tak terlupakan dari dapur kami.</p>
                            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($foodProducts as $product)
                                    <div class="border dark:border-gray-700 rounded-lg p-3 hover:shadow-md transition-shadow">
                                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="h-32 w-full object-cover rounded-md mb-3">
                                        <h4 class="font-semibold text-sm dark:text-white">{{ $product->name }}</h4>
                                        <p class="text-primary-600 font-bold text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('catalog.index', ['category' => $foodCategory->id]) }}" class="mt-6 inline-block bg-primary-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-primary-700 transition-colors">
                                Discover Flavors
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif
            
            <!-- Products Grid -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-12">
                <div class="p-6 bg-white dark:bg-gray-800">
                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach($products as $product)
                                <div class="group relative bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-shadow duration-300">
                                    <div class="aspect-w-3 aspect-h-2 bg-gray-200 dark:bg-gray-600 w-full overflow-hidden">
                                        @if($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" 
                                                class="w-full h-48 object-cover object-center group-hover:opacity-75">
                                        @else
                                            <div class="w-full h-48 flex items-center justify-center bg-gray-100 dark:bg-gray-500 text-gray-400 dark:text-gray-300">
                                                No Image
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="p-4">
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                            <a href="{{ route('catalog.show', $product) }}">
                                                <span aria-hidden="true" class="absolute inset-0"></span>
                                                {{ $product->name }}
                                            </a>
                                        </h3>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $product->category ? $product->category->name : 'Uncategorized' }}</p>
                                        <p class="mt-2 text-sm font-bold text-gray-900 dark:text-white">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        
                                        <div class="mt-3 flex justify-between items-center">
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                @if($product->stock > 0)
                                                    <span class="text-green-600">In Stock ({{ $product->stock }})</span>
                                                @else
                                                    <span class="text-red-600">Out of Stock</span>
                                                @endif
                                            </div>
                                            
                                            @auth
                                                <form action="{{ route('cart.add') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" 
                                                        class="text-sm bg-primary-600 hover:bg-primary-700 text-white py-1 px-3 rounded-md"
                                                        @disabled($product->stock <= 0)>
                                                        Add to Cart
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('login') }}" class="text-sm text-primary-600 hover:text-primary-900">
                                                    Login to Buy
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">No products found</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Try adjusting your search or filter to find what you're looking for.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

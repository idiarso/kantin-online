<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $content['title'] }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ url('/') }}" class="text-xl font-bold text-indigo-600">
                                {{ config('app.name', 'E-Kantin') }}
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-indigo-600">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 mr-4">Login</a>
                            <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Categories Slider -->
        <div class="bg-white shadow-sm py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative" x-data="{ scrollLeft: 0 }">
                    <!-- Left Arrow -->
                    <button @click="$refs.categoriesContainer.scrollLeft -= 200" 
                            class="absolute left-0 top-1/2 -translate-y-1/2 bg-white rounded-full shadow-lg p-2 z-10 hover:bg-gray-50">
                        <i class="fas fa-chevron-left text-gray-600"></i>
                    </button>

                    <!-- Categories Container -->
                    <div x-ref="categoriesContainer" 
                         class="flex space-x-6 overflow-x-auto scrollbar-hide scroll-smooth px-8">
                        @foreach($categories as $category)
                        <a href="{{ route('menu.index', ['category' => $category->id]) }}" 
                           class="flex flex-col items-center flex-shrink-0 group">
                            <div class="w-16 h-16 rounded-full bg-indigo-50 flex items-center justify-center group-hover:bg-indigo-100 transition-colors">
                                <i class="fas fa-utensils text-indigo-600 text-xl"></i>
                            </div>
                            <span class="mt-2 text-sm font-medium text-gray-900">{{ $category->name }}</span>
                            <span class="text-xs text-gray-500">{{ $category->products_count }} items</span>
                        </a>
                        @endforeach
                    </div>

                    <!-- Right Arrow -->
                    <button @click="$refs.categoriesContainer.scrollLeft += 200" 
                            class="absolute right-0 top-1/2 -translate-y-1/2 bg-white rounded-full shadow-lg p-2 z-10 hover:bg-gray-50">
                        <i class="fas fa-chevron-right text-gray-600"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Banner Carousel -->
        @if(count($banners) > 0)
        <div class="relative bg-gray-900">
            <div class="mx-auto max-w-7xl">
                <div class="relative z-10 lg:w-full">
                    <div x-data="{ currentBanner: 0, totalBanners: {{ count($banners) }} }" class="relative">
                        @foreach($banners as $index => $banner)
                        <div x-show="currentBanner === {{ $index }}"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-90"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-90"
                             class="relative">
                            <div class="h-80 sm:h-96 lg:h-[32rem]">
                                @if(isset($banner['image']))
                                <img src="{{ Storage::url($banner['image']) }}" alt="{{ $banner['title'] }}" class="object-cover object-center w-full h-full">
                                @endif
                            </div>
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center">
                                <div class="px-8 sm:px-12 max-w-xl">
                                    <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">{{ $banner['title'] }}</h2>
                                    <p class="text-lg text-white mb-6">{{ $banner['description'] }}</p>
                                    @if(isset($banner['link']))
                                    <a href="{{ $banner['link'] }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700">
                                        Learn More
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- Navigation Buttons -->
                        <div class="absolute bottom-4 right-4 flex space-x-2">
                            @foreach($banners as $index => $banner)
                            <button @click="currentBanner = {{ $index }}"
                                    :class="{ 'bg-white': currentBanner === {{ $index }}, 'bg-gray-400': currentBanner !== {{ $index }} }"
                                    class="w-3 h-3 rounded-full transition-colors duration-200"></button>
                            @endforeach
                        </div>

                        <!-- Auto-advance -->
                        <div x-init="setInterval(() => { currentBanner = (currentBanner + 1) % totalBanners }, 5000)"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Hero Section -->
        <div class="relative bg-gray-50">
            <div class="max-w-7xl mx-auto">
                <div class="relative z-10 pb-8 bg-gray-50 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                    <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                        <div class="sm:text-center lg:text-left">
                            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                                <span class="block">{{ $content['title'] }}</span>
                            </h1>
                            <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                {{ $content['description'] }}
                            </p>
                            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                                <div class="rounded-md shadow">
                                    <a href="{{ route('menu.index') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                        View Menu
                                    </a>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>

        <!-- Featured Products Section -->
        <div class="bg-white">
            <div class="max-w-2xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:max-w-7xl lg:px-8">
                <h2 class="text-2xl font-extrabold tracking-tight text-gray-900">Featured Products</h2>
                <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    @foreach($featuredProducts as $product)
                        <div class="group relative">
                            <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                                @else
                                    <div class="flex items-center justify-center h-full">
                                        <span class="text-gray-400">No Image</span>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-4 flex justify-between">
                                <div>
                                    <h3 class="text-sm text-gray-700">
                                        <a href="{{ route('menu.index', ['product' => $product->id]) }}">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ $product->category->name }}</p>
                                </div>
                                <p class="text-sm font-medium text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Categories Section -->
        <div class="bg-gray-50">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
                <div class="lg:grid lg:grid-cols-2 lg:gap-8 lg:items-center">
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                            Browse by Category
                        </h2>
                        <p class="mt-3 max-w-3xl text-lg text-gray-500">
                            Explore our wide selection of food and beverages categorized for your convenience.
                        </p>
                    </div>
                    <div class="mt-8 grid grid-cols-2 gap-0.5 md:grid-cols-3 lg:mt-0 lg:grid-cols-2">
                        @foreach($categories as $category)
                            <div class="col-span-1 flex justify-center py-8 px-8 bg-white">
                                <a href="{{ route('menu.index', ['category' => $category->id]) }}" class="text-center">
                                    <span class="text-lg font-medium text-gray-900">{{ $category->name }}</span>
                                    <p class="text-sm text-gray-500">{{ $category->products_count }} items</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white">
            <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-3">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Opening Hours</h3>
                        <p class="mt-2 text-base text-gray-500">{{ $content['opening_hours'] }}</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Contact</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Email: {{ $content['contact_email'] }}<br>
                            Phone: {{ $content['contact_phone'] }}
                        </p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Location</h3>
                        <p class="mt-2 text-base text-gray-500">{{ $content['address'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-50">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
                <div class="flex justify-center space-x-6 md:order-2">
                    <span class="text-gray-400">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</span>
                </div>
            </div>
        </footer>
    </body>
</html> 
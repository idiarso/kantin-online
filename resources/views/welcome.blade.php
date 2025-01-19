<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'E-Kantin') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold text-orange-500">E-Kantin</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" 
                                   placeholder="Cari makanan favoritmu..." 
                                   class="w-64 pl-10 pr-4 py-2 rounded-full border border-gray-200 focus:outline-none focus:border-orange-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-orange-500">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-600 hover:text-orange-500">Login</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="pt-16">
            <!-- Categories -->
            <section class="py-6 bg-white">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="relative">
                        <button class="absolute -left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-white rounded-full shadow-lg z-10 flex items-center justify-center">
                            <i class="fas fa-chevron-left text-gray-400"></i>
                        </button>
                        
                        <div class="flex space-x-6 overflow-x-auto scrollbar-hide px-4">
                            @foreach($categories as $category)
                            <div class="category-card">
                                <div class="category-icon bg-orange-50">
                                    <i class="fas fa-{{ $category->icon }} text-orange-500 text-xl"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-700">{{ $category->name }}</p>
                                <span class="text-xs text-gray-500">{{ $category->products_count }} items</span>
                            </div>
                            @endforeach
                        </div>

                        <button class="absolute -right-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-white rounded-full shadow-lg z-10 flex items-center justify-center">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Featured Products -->
            <section class="py-8">
                <div class="max-w-7xl mx-auto px-4">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Menu Favorit</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($featuredProducts as $product)
                        <div class="product-card">
                            <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                                <!-- Placeholder for product image -->
                                <div class="w-full h-48 bg-gray-200"></div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-medium text-gray-900">{{ $product->name }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</p>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-orange-500 font-bold">Rp {{ number_format($product->price) }}</span>
                                    <button class="text-orange-500 hover:text-orange-600">
                                        <i class="fas fa-plus-circle text-xl"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </main>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.scrollbar-hide');
            const prevBtn = container.parentElement.querySelector('.fa-chevron-left').parentElement;
            const nextBtn = container.parentElement.querySelector('.fa-chevron-right').parentElement;
            
            prevBtn.addEventListener('click', () => {
                container.scrollBy({ left: -200, behavior: 'smooth' });
            });
            
            nextBtn.addEventListener('click', () => {
                container.scrollBy({ left: 200, behavior: 'smooth' });
            });
        });
        </script>
    </body>
</html> 
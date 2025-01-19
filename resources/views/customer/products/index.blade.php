<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Categories Filter -->
            <div class="mb-6 flex flex-wrap gap-2">
                <a href="{{ route('customer.products.index') }}" 
                   class="px-4 py-2 rounded-full {{ !request('category') ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }} hover:bg-indigo-500 hover:text-white transition-colors">
                    All
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('customer.products.index', ['category' => $category->id]) }}" 
                       class="px-4 py-2 rounded-full {{ request('category') == $category->id ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }} hover:bg-indigo-500 hover:text-white transition-colors">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500">No Image</span>
                            </div>
                        @endif
                        
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                                <span class="text-sm bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                    {{ $product->category->name }}
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($product->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold">Rp {{ number_format($product->price) }}</span>
                                @if($product->stock > 0)
                                    <form action="{{ route('customer.cart.add', $product) }}" method="POST">
                                        @csrf
                                        <x-primary-button>
                                            Add to Cart
                                        </x-primary-button>
                                    </form>
                                @else
                                    <span class="text-red-600">Out of Stock</span>
                                @endif
                            </div>
                            <div class="mt-2 text-sm text-gray-500">
                                <span>Stock: {{ $product->stock }}</span>
                                @if($product->preparation_time)
                                    <span class="ml-2">•</span>
                                    <span class="ml-2">Prep: {{ $product->preparation_time }} mins</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif
</x-app-layout> 
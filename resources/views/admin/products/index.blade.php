@extends('layouts.admin')

@section('header', 'Products')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
            <h1 class="text-2xl font-bold">Products</h1>
            <!-- Category Filter -->
            <select id="category-filter" class="border rounded-md px-3 py-2">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <a href="{{ route('admin.products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
            Add New Product
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($products as $product)
        <div class="bg-white rounded-lg shadow-md overflow-hidden product-card" data-category="{{ $product->category_id }}">
            <!-- Product Image -->
            <div class="relative h-48 bg-gray-200">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="flex items-center justify-center h-full">
                        <span class="text-gray-400">No Image</span>
                    </div>
                @endif
                <div class="absolute top-2 right-2">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $product->status == 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($product->status) }}
                    </span>
                </div>
            </div>

            <!-- Product Info -->
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h3>
                <p class="text-sm text-gray-600 mb-2">{{ $product->category->name }}</p>
                <p class="text-sm text-gray-500 mb-4">{{ $product->description }}</p>

                <div class="flex justify-between items-center mb-4">
                    <span class="text-blue-600 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    <span class="text-sm text-gray-500">Stock: {{ $product->stock }}</span>
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-500 hover:text-blue-700">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this product?')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.getElementById('category-filter');
    const productCards = document.querySelectorAll('.product-card');

    categoryFilter.addEventListener('change', function() {
        const selectedCategory = this.value;
        
        productCards.forEach(card => {
            if (!selectedCategory || card.dataset.category === selectedCategory) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>
@endpush
@endsection 
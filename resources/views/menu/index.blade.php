@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Menu Kantin</h1>
            <div class="flex space-x-4">
                <input type="text" 
                    placeholder="Cari menu..." 
                    class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    id="searchInput">
            </div>
        </div>

        <!-- Category Tabs -->
        <div class="mb-8">
            <div class="flex space-x-4 overflow-x-auto pb-2">
                <button class="category-tab px-4 py-2 rounded-full bg-blue-500 text-white hover:bg-blue-600 focus:outline-none active" 
                    data-category="all">
                    Semua Menu
                </button>
                @foreach($categories as $category)
                <button class="category-tab px-4 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 focus:outline-none" 
                    data-category="{{ $category->id }}">
                    {{ $category->name }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Menu Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="menuGrid">
            @foreach($products as $product)
            <div class="menu-item bg-white rounded-lg shadow-md overflow-hidden" 
                data-category="{{ $product->category_id }}"
                data-name="{{ strtolower($product->name) }}"
                data-description="{{ strtolower($product->description) }}">
                <!-- Product Image -->
                <div class="relative h-48">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                            alt="{{ $product->name }}" 
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-utensils text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                    <div class="absolute top-2 right-2">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $product->status == 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->status == 'available' ? 'Tersedia' : 'Habis' }}
                        </span>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-500 mb-2">{{ $product->category->name }}</p>
                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($product->description, 100) }}</p>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-blue-600">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <span class="text-sm text-gray-500">
                            Stok: {{ $product->stock }}
                        </span>
                    </div>

                    @if($product->status == 'available' && $product->stock > 0)
                    <button class="mt-4 w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors">
                        Pesan Sekarang
                    </button>
                    @else
                    <button class="mt-4 w-full bg-gray-300 text-gray-500 py-2 px-4 rounded-lg cursor-not-allowed" disabled>
                        Tidak Tersedia
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.querySelectorAll('.menu-item');
    const categoryTabs = document.querySelectorAll('.category-tab');
    const searchInput = document.getElementById('searchInput');

    // Category filter
    function filterByCategory(category) {
        menuItems.forEach(item => {
            if (category === 'all' || item.dataset.category === category) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Search filter
    function filterBySearch(searchTerm) {
        menuItems.forEach(item => {
            const name = item.dataset.name;
            const description = item.dataset.description;
            
            if (name.includes(searchTerm) || description.includes(searchTerm)) {
                if (item.style.display !== 'none' || !item.style.display) {
                    item.style.display = '';
                }
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Category click handler
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active class from all tabs
            categoryTabs.forEach(t => {
                t.classList.remove('bg-blue-500', 'text-white', 'active');
                t.classList.add('bg-gray-200', 'text-gray-700');
            });

            // Add active class to clicked tab
            tab.classList.remove('bg-gray-200', 'text-gray-700');
            tab.classList.add('bg-blue-500', 'text-white', 'active');

            // Filter items
            const selectedCategory = tab.dataset.category;
            filterByCategory(selectedCategory);

            // Re-apply search filter if there's a search term
            if (searchInput.value) {
                filterBySearch(searchInput.value.toLowerCase());
            }
        });
    });

    // Search input handler
    searchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        
        // Get active category
        const activeTab = document.querySelector('.category-tab.active');
        const activeCategory = activeTab ? activeTab.dataset.category : 'all';

        // Reset all items
        menuItems.forEach(item => item.style.display = '');

        // Apply category filter
        filterByCategory(activeCategory);

        // Apply search filter
        filterBySearch(searchTerm);
    });

    // Initialize with "all" category active
    filterByCategory('all');
});
</script>
@endpush
@endsection 
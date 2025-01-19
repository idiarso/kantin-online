@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">Edit Product: {{ $product->name }}</h1>

        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Image Upload -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                <div class="flex items-center">
                    <div class="w-32 h-32 bg-gray-100 rounded-lg overflow-hidden mr-4">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                alt="{{ $product->name }}" 
                                class="w-full h-full object-cover" 
                                id="preview">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400" id="placeholder">
                                <i class="fas fa-image text-3xl"></i>
                            </div>
                            <img id="preview" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                        @endif
                    </div>
                    <div class="flex-1">
                        <input type="file" 
                            name="image" 
                            accept="image/*" 
                            onchange="previewImage(this)"
                            class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100">
                        <p class="mt-1 text-sm text-gray-500">
                            PNG, JPG, GIF up to 2MB
                        </p>
                    </div>
                </div>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select name="category_id" class="w-full border rounded-md px-3 py-2">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Name -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" name="name" value="{{ $product->name }}" 
                    class="w-full border rounded-md px-3 py-2">
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3" 
                    class="w-full border rounded-md px-3 py-2">{{ $product->description }}</textarea>
            </div>

            <!-- Price and Stock in one row -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                    <input type="number" name="price" value="{{ $product->price }}" 
                        class="w-full border rounded-md px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
                    <input type="number" name="stock" value="{{ $product->stock }}" 
                        class="w-full border rounded-md px-3 py-2">
                </div>
            </div>

            <!-- Preparation Time -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Preparation Time (minutes)</label>
                <input type="number" name="preparation_time" value="{{ $product->preparation_time }}" 
                    class="w-full border rounded-md px-3 py-2">
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border rounded-md px-3 py-2">
                    <option value="available" {{ $product->status == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ $product->status == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-2">
                <a href="{{ route('products.index') }}" 
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit" 
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    const placeholder = document.getElementById('placeholder');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            if (placeholder) placeholder.classList.add('hidden');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection 
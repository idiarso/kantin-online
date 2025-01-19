@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Create New Product</h1>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Image Upload -->
            <div>
                <label class="block text-gray-700 font-bold mb-2">Product Image</label>
                <div class="mt-1 flex items-center">
                    <span class="inline-block h-32 w-32 rounded-lg overflow-hidden bg-gray-100">
                        <img id="preview" src="#" alt="Preview" class="h-32 w-32 object-cover hidden">
                        <div id="placeholder" class="h-32 w-32 flex items-center justify-center text-gray-400">
                            <i class="fas fa-image text-3xl"></i>
                        </div>
                    </span>
                    <label class="ml-5 cursor-pointer">
                        <span class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Choose File
                        </span>
                        <input type="file" name="image" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </label>
                </div>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="category_id" class="block text-gray-700 font-bold mb-2">Category</label>
                <select name="category_id" id="category_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
                <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200" value="{{ old('name') }}">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                <textarea name="description" id="description" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="price" class="block text-gray-700 font-bold mb-2">Price</label>
                    <input type="number" name="price" id="price" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200" value="{{ old('price') }}">
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="stock" class="block text-gray-700 font-bold mb-2">Stock</label>
                    <input type="number" name="stock" id="stock" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200" value="{{ old('stock') }}">
                    @error('stock')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="preparation_time" class="block text-gray-700 font-bold mb-2">Preparation Time (minutes)</label>
                <input type="number" name="preparation_time" id="preparation_time" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200" value="{{ old('preparation_time') }}">
                @error('preparation_time')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block text-gray-700 font-bold mb-2">Status</label>
                <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    <option value="available">Available</option>
                    <option value="unavailable">Unavailable</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create Product
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
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection 
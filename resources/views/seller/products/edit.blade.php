<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Product') }}: {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('seller.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Product Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <x-select id="category_id" name="category_id" class="mt-1 block w-full" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $product->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="price" :value="__('Price')" />
                                <x-text-input id="price" name="price" type="number" class="mt-1 block w-full" 
                                    :value="old('price', $product->price)" required min="0" step="100" />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="stock" :value="__('Stock')" />
                                <x-text-input id="stock" name="stock" type="number" class="mt-1 block w-full" 
                                    :value="old('stock', $product->stock)" required min="0" />
                                <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="preparation_time" :value="__('Preparation Time (minutes)')" />
                                <x-text-input id="preparation_time" name="preparation_time" type="number" 
                                    class="mt-1 block w-full" :value="old('preparation_time', $product->preparation_time)" min="1" />
                                <x-input-error :messages="$errors->get('preparation_time')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="image" :value="__('Product Image')" />
                            @if($product->image)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" 
                                         class="h-32 w-32 object-cover rounded-lg">
                                </div>
                            @endif
                            <input type="file" id="image" name="image" accept="image/*"
                                class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                            <p class="mt-1 text-sm text-gray-500">Leave empty to keep current image. Maximum file size: 2MB</p>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <x-select id="status" name="status" class="mt-1 block w-full" required>
                                <option value="available" {{ old('status', $product->status) === 'available' ? 'selected' : '' }}>Available</option>
                                <option value="unavailable" {{ old('status', $product->status) === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                            </x-select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update Product') }}</x-primary-button>
                            <a href="{{ route('seller.products.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
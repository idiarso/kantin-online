<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit Menu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('kantin.admin.menu.update', $product) }}" 
                          method="POST" 
                          enctype="multipart/form-data"
                          class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <x-input-label for="category_id" value="Kategori" />
                                <select id="category_id" 
                                        name="category_id"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="name" value="Nama Menu" />
                                <x-text-input id="name" 
                                             name="name" 
                                             type="text" 
                                             class="block w-full mt-1" 
                                             value="{{ old('name', $product->name) }}" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="description" value="Deskripsi" />
                            <textarea id="description"
                                      name="description"
                                      rows="3"
                                      class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $product->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <div>
                                <x-input-label for="price" value="Harga" />
                                <x-text-input id="price" 
                                             name="price" 
                                             type="number" 
                                             class="block w-full mt-1" 
                                             value="{{ old('price', $product->price) }}" />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="stock" value="Stok" />
                                <x-text-input id="stock" 
                                             name="stock" 
                                             type="number" 
                                             class="block w-full mt-1" 
                                             value="{{ old('stock', $product->stock) }}" />
                                <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="preparation_time" value="Waktu Persiapan (menit)" />
                                <x-text-input id="preparation_time" 
                                             name="preparation_time" 
                                             type="number" 
                                             class="block w-full mt-1" 
                                             value="{{ old('preparation_time', $product->preparation_time) }}" />
                                <x-input-error :messages="$errors->get('preparation_time')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <x-input-label for="image" value="Gambar Menu" />
                                @if($product->image)
                                    <div class="mt-2 mb-4">
                                        <img src="{{ Storage::url($product->image) }}" 
                                             alt="{{ $product->name }}"
                                             class="w-32 h-32 rounded object-cover">
                                    </div>
                                @endif
                                <input type="file" 
                                       id="image" 
                                       name="image"
                                       accept="image/*"
                                       class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="status" value="Status" />
                                <select id="status" 
                                        name="status"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="available" {{ old('status', $product->status) == 'available' ? 'selected' : '' }}>
                                        Tersedia
                                    </option>
                                    <option value="unavailable" {{ old('status', $product->status) == 'unavailable' ? 'selected' : '' }}>
                                        Tidak Tersedia
                                    </option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <x-secondary-button type="button" 
                                              onclick="window.history.back()"
                                              class="mr-3">
                                Batal
                            </x-secondary-button>
                            <x-primary-button>
                                Update Menu
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
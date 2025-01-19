<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ isset($category) ? 'Edit Kategori' : 'Tambah Kategori' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ isset($category) ? route('kantin.admin.categories.update', $category) : route('kantin.admin.categories.store') }}" 
                          method="POST"
                          class="space-y-6">
                        @csrf
                        @if(isset($category))
                            @method('PUT')
                        @endif

                        <div>
                            <x-input-label for="name" value="Nama Kategori" />
                            <x-text-input id="name" 
                                         name="name" 
                                         type="text" 
                                         class="block w-full mt-1" 
                                         value="{{ old('name', $category->name ?? '') }}" 
                                         required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Deskripsi" />
                            <textarea id="description"
                                      name="description"
                                      rows="3"
                                      class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $category->description ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="icon" value="Icon (Emoji)" />
                            <x-text-input id="icon" 
                                         name="icon" 
                                         type="text" 
                                         class="block w-full mt-1" 
                                         value="{{ old('icon', $category->icon ?? '') }}" />
                            <x-input-error :messages="$errors->get('icon')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">
                                Gunakan emoji sebagai icon kategori (opsional)
                            </p>
                        </div>

                        <div class="flex justify-end">
                            <x-secondary-button type="button" 
                                              onclick="window.history.back()"
                                              class="mr-3">
                                Batal
                            </x-secondary-button>
                            <x-primary-button>
                                {{ isset($category) ? 'Update Kategori' : 'Simpan Kategori' }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
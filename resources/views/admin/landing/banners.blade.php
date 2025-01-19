@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Manage Banners</h2>
        <button type="button" onclick="addBanner()"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-plus"></i> Add New Banner
        </button>
    </div>
@endsection

@section('content')
<div class="container mx-auto px-6 py-8">
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <form action="{{ route('admin.landing.banners.update') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf

            <div class="space-y-8" id="banners-container">
                @forelse($banners as $index => $banner)
                    <div class="banner-item border rounded-lg p-6 relative" data-index="{{ $index }}">
                        <button type="button" onclick="removeBanner(this)"
                            class="absolute top-4 right-4 text-red-500 hover:text-red-700">
                            <i class="fas fa-trash"></i>
                        </button>

                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Banner Image</label>
                                <div class="mt-1 flex items-center space-x-4">
                                    @if($banner['image'])
                                        <div class="relative">
                                            <img src="{{ Storage::url($banner['image']) }}" alt="Banner Image"
                                                class="h-32 w-auto object-cover rounded-lg">
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <input type="file" name="banners[{{ $index }}][image]" accept="image/*"
                                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    </div>
                                </div>
                                @error("banners.{$index}.image")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Title</label>
                                <input type="text" name="banners[{{ $index }}][title]" value="{{ $banner['title'] }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error("banners.{$index}.title")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="banners[{{ $index }}][description]" rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $banner['description'] }}</textarea>
                                @error("banners.{$index}.description")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Link (Optional)</label>
                                <input type="url" name="banners[{{ $index }}][link]" value="{{ $banner['link'] ?? '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error("banners.{$index}.link")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No banners added yet. Click "Add New Banner" to create one.</p>
                @endforelse
            </div>

            <div class="pt-6 flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Banner Template -->
<template id="banner-template">
    <div class="banner-item border rounded-lg p-6 relative" data-index="INDEX">
        <button type="button" onclick="removeBanner(this)"
            class="absolute top-4 right-4 text-red-500 hover:text-red-700">
            <i class="fas fa-trash"></i>
        </button>

        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Banner Image</label>
                <div class="mt-1">
                    <input type="file" name="banners[INDEX][image]" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="banners[INDEX][title]"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="banners[INDEX][description]" rows="2"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Link (Optional)</label>
                <input type="url" name="banners[INDEX][link]"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
function addBanner() {
    const container = document.getElementById('banners-container');
    const template = document.getElementById('banner-template');
    const index = container.querySelectorAll('.banner-item').length;
    
    const newBanner = template.content.cloneNode(true);
    newBanner.querySelector('.banner-item').dataset.index = index;
    
    // Update all input names with the correct index
    newBanner.querySelectorAll('[name*="INDEX"]').forEach(input => {
        input.name = input.name.replace('INDEX', index);
    });
    
    container.appendChild(newBanner);
}

function removeBanner(button) {
    const banner = button.closest('.banner-item');
    banner.remove();
    
    // Reindex remaining banners
    const container = document.getElementById('banners-container');
    container.querySelectorAll('.banner-item').forEach((item, index) => {
        item.dataset.index = index;
        item.querySelectorAll('[name*="banners["]').forEach(input => {
            input.name = input.name.replace(/banners\[\d+\]/, `banners[${index}]`);
        });
    });
}
</script>
@endpush
@endsection 
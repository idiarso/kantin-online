@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Manage Banners</h2>
        <button type="button" onclick="addBannerForm()"
            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
            Add New Banner
        </button>
    </div>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.landing.banners.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="banners-container" class="space-y-6">
            @forelse($banners as $index => $banner)
            <div class="banner-form bg-white p-6 rounded-lg shadow-sm border" data-index="{{ $index }}">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium">Banner #{{ $index + 1 }}</h3>
                    <button type="button" onclick="removeBanner(this)" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="banners[{{ $index }}][title]" value="{{ $banner['title'] }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error("banners.{$index}.title")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="banners[{{ $index }}][description]" rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $banner['description'] }}</textarea>
                        @error("banners.{$index}.description")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Image</label>
                        <div class="mt-1 flex items-center">
                            @if(isset($banner['image']))
                            <div class="relative">
                                <img src="{{ Storage::url($banner['image']) }}" alt="Banner Preview" class="w-32 h-32 object-cover rounded">
                            </div>
                            @endif
                            <input type="file" name="banners[{{ $index }}][image]" accept="image/*" 
                                   class="ml-4 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        @error("banners.{$index}.image")
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Link (Optional)</label>
                        <input type="url" name="banners[{{ $index }}][link]" value="{{ $banner['link'] ?? '' }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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

        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                Save Changes
            </button>
        </div>
    </form>
</div>

<template id="banner-template">
    <div class="banner-form bg-white p-6 rounded-lg shadow-sm border" data-index="INDEX">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">New Banner</h3>
            <button type="button" onclick="removeBanner(this)" class="text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i> Remove
            </button>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="banners[INDEX][title]" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="banners[INDEX][description]" rows="3" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Image</label>
                <input type="file" name="banners[INDEX][image]" accept="image/*" 
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Link (Optional)</label>
                <input type="url" name="banners[INDEX][link]" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
function addBannerForm() {
    const container = document.getElementById('banners-container');
    const template = document.getElementById('banner-template');
    const index = document.querySelectorAll('.banner-form').length;
    
    let newBanner = template.content.cloneNode(true);
    newBanner.querySelector('.banner-form').dataset.index = index;
    
    // Update all name attributes with the correct index
    newBanner.querySelectorAll('[name*="INDEX"]').forEach(input => {
        input.name = input.name.replace('INDEX', index);
    });
    
    container.appendChild(newBanner);
}

function removeBanner(button) {
    const bannerForm = button.closest('.banner-form');
    bannerForm.remove();
    
    // Reindex remaining banners
    document.querySelectorAll('.banner-form').forEach((form, index) => {
        form.dataset.index = index;
        form.querySelectorAll('[name*="banners["]').forEach(input => {
            input.name = input.name.replace(/banners\[\d+\]/, `banners[${index}]`);
        });
    });
}
</script>
@endpush
@endsection 
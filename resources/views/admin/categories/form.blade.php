@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">
            {{ isset($category) ? 'Edit Category' : 'Create New Category' }}
        </h2>
        <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left"></i> Back to Categories
        </a>
    </div>
@endsection

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="p-8">
            @csrf
            @if(isset($category))
                @method('PUT')
            @endif

            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                    <p class="mt-1 text-sm text-gray-500">Category details and attributes.</p>

                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $category->name ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $category->description ?? '') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Category Image</label>
                            <div class="mt-1 flex items-center space-x-4">
                                @if(isset($category) && $category->image)
                                    <div class="relative">
                                        <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}"
                                            class="h-32 w-auto object-cover rounded-lg">
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <input type="file" name="image" id="image" accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="mt-1 text-xs text-gray-500">Recommended size: 400x400px. Max file size: 2MB.</p>
                                </div>
                            </div>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="active" {{ (old('status', $category->status ?? '') == 'active') ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ (old('status', $category->status ?? '') == 'inactive') ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        {{ isset($category) ? 'Update Category' : 'Create Category' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 
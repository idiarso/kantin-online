@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Landing Page Content</h2>
        <div class="flex space-x-4">
            <a href="{{ route('admin.landing.banners') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-images mr-2"></i> Manage Banners
            </a>
        </div>
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
        <form action="{{ route('admin.landing.content.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <!-- Hero Section -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Hero Section</h3>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="hero_title" class="block text-sm font-medium text-gray-700">Hero Title</label>
                        <input type="text" name="hero_title" id="hero_title" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            value="{{ old('hero_title', $content['hero_title']) }}" required>
                        @error('hero_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="hero_subtitle" class="block text-sm font-medium text-gray-700">Hero Subtitle</label>
                        <input type="text" name="hero_subtitle" id="hero_subtitle" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            value="{{ old('hero_subtitle', $content['hero_subtitle']) }}" required>
                        @error('hero_subtitle')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="hero_description" class="block text-sm font-medium text-gray-700">Hero Description</label>
                        <textarea name="hero_description" id="hero_description" rows="3" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('hero_description', $content['hero_description']) }}</textarea>
                        @error('hero_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="hero_image" class="block text-sm font-medium text-gray-700">Hero Image</label>
                        <div class="mt-1 flex items-center space-x-4">
                            @if($content['hero_image'])
                                <div class="flex-shrink-0 h-32 w-48">
                                    <img class="h-32 w-48 object-cover rounded-lg" 
                                        src="{{ asset('storage/' . $content['hero_image']) }}" 
                                        alt="Hero Image">
                                </div>
                            @endif
                            <input type="file" name="hero_image" id="hero_image" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                        @error('hero_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- About Section -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">About Section</h3>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="about_title" class="block text-sm font-medium text-gray-700">About Title</label>
                        <input type="text" name="about_title" id="about_title" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            value="{{ old('about_title', $content['about_title']) }}" required>
                        @error('about_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="about_content" class="block text-sm font-medium text-gray-700">About Content</label>
                        <textarea name="about_content" id="about_content" rows="4" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('about_content', $content['about_content']) }}</textarea>
                        @error('about_content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Features Section</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($content['features'] as $index => $feature)
                        <div class="space-y-4">
                            <div>
                                <label for="features[{{ $index }}][title]" class="block text-sm font-medium text-gray-700">Feature {{ $index + 1 }} Title</label>
                                <input type="text" name="features[{{ $index }}][title]" id="features[{{ $index }}][title]" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    value="{{ old("features.$index.title", $feature['title']) }}" required>
                                @error("features.$index.title")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="features[{{ $index }}][description]" class="block text-sm font-medium text-gray-700">Feature {{ $index + 1 }} Description</label>
                                <textarea name="features[{{ $index }}][description]" id="features[{{ $index }}][description]" rows="3" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old("features.$index.description", $feature['description']) }}</textarea>
                                @error("features.$index.description")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ isset($content) ? 'Edit Content' : 'Add New Content' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ isset($content) ? route('admin.landing.update', $content) : route('admin.landing.store') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="space-y-6">
                        @csrf
                        @if(isset($content))
                            @method('PUT')
                        @endif

                        <div>
                            <x-input-label for="section" value="Section" />
                            <x-text-input id="section"
                                         name="section"
                                         type="text"
                                         class="block w-full mt-1"
                                         value="{{ old('section', $content->section ?? '') }}"
                                         required />
                            <x-input-error :messages="$errors->get('section')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="title" value="Title" />
                            <x-text-input id="title"
                                         name="title"
                                         type="text"
                                         class="block w-full mt-1"
                                         value="{{ old('title', $content->title ?? '') }}"
                                         required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="content_text" value="Content Text" />
                            <textarea id="content_text"
                                      name="content[text]"
                                      rows="4"
                                      class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">{{ old('content.text', $content->content['text'] ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('content.text')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <x-input-label for="button_text" value="Button Text" />
                                <x-text-input id="button_text"
                                             name="content[button_text]"
                                             type="text"
                                             class="block w-full mt-1"
                                             value="{{ old('content.button_text', $content->content['button_text'] ?? '') }}" />
                                <x-input-error :messages="$errors->get('content.button_text')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="button_link" value="Button Link" />
                                <x-text-input id="button_link"
                                             name="content[button_link]"
                                             type="url"
                                             class="block w-full mt-1"
                                             value="{{ old('content.button_link', $content->content['button_link'] ?? '') }}" />
                                <x-input-error :messages="$errors->get('content.button_link')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="image" value="Image" />
                            <input type="file"
                                   id="image"
                                   name="image"
                                   accept="image/*"
                                   class="block w-full mt-1">
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            
                            @if(isset($content) && $content->image)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($content->image) }}"
                                         alt="Current image"
                                         class="w-32 h-32 object-cover rounded">
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox"
                                       name="status"
                                       value="1"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm"
                                       {{ old('status', $content->status ?? true) ? 'checked' : '' }}>
                                <span class="ml-2">Active</span>
                            </label>
                        </div>

                        <div class="flex justify-end">
                            <x-secondary-button type="button"
                                              onclick="window.history.back()"
                                              class="mr-3">
                                Cancel
                            </x-secondary-button>
                            <x-primary-button>
                                {{ isset($content) ? 'Update' : 'Create' }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
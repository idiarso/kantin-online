@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">General Settings</h2>
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
        <form action="{{ route('admin.settings.update', 'general') }}" method="POST" class="p-8">
            @csrf

            <div class="space-y-6">
                <!-- School Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900">School Information</h3>
                    <p class="mt-1 text-sm text-gray-500">Basic information about your school.</p>

                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <div>
                            <label for="school_name" class="block text-sm font-medium text-gray-700">School Name</label>
                            <input type="text" name="settings[school_name]" id="school_name"
                                value="{{ $settings['school_name'] ?? '' }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('settings.school_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="school_address" class="block text-sm font-medium text-gray-700">School Address</label>
                            <input type="text" name="settings[school_address]" id="school_address"
                                value="{{ $settings['school_address'] ?? '' }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('settings.school_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="pt-6">
                    <h3 class="text-lg font-medium text-gray-900">Contact Information</h3>
                    <p class="mt-1 text-sm text-gray-500">Contact details for notifications and support.</p>

                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700">Contact Email</label>
                            <input type="email" name="settings[contact_email]" id="contact_email"
                                value="{{ $settings['contact_email'] ?? '' }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('settings.contact_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700">Contact Phone</label>
                            <input type="text" name="settings[contact_phone]" id="contact_phone"
                                value="{{ $settings['contact_phone'] ?? '' }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('settings.contact_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- System Settings -->
                <div class="pt-6">
                    <h3 class="text-lg font-medium text-gray-900">System Settings</h3>
                    <p class="mt-1 text-sm text-gray-500">Configure system-wide settings.</p>

                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                            <select name="settings[currency]" id="currency"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="IDR" {{ ($settings['currency'] ?? '') == 'IDR' ? 'selected' : '' }}>IDR - Indonesian Rupiah</option>
                                <option value="USD" {{ ($settings['currency'] ?? '') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                            </select>
                            @error('settings.currency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-700">Timezone</label>
                            <select name="settings[timezone]" id="timezone"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="Asia/Jakarta" {{ ($settings['timezone'] ?? '') == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta</option>
                                <option value="Asia/Makassar" {{ ($settings['timezone'] ?? '') == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar</option>
                                <option value="Asia/Jayapura" {{ ($settings['timezone'] ?? '') == 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura</option>
                            </select>
                            @error('settings.timezone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Save Settings
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection 
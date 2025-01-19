@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">
            {{ isset($courier) ? 'Edit Courier: ' . $courier->name : 'Create New Courier' }}
        </h2>
        <a href="{{ route('admin.couriers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i> Back to Couriers
        </a>
    </div>
@endsection

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            <form action="{{ isset($courier) ? route('admin.couriers.update', $courier) : route('admin.couriers.store') }}" 
                method="POST" 
                enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @if(isset($courier))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                value="{{ old('name', $courier->name ?? '') }}">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                value="{{ old('email', $courier->email ?? '') }}">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="phone" id="phone" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                value="{{ old('phone', $courier->phone ?? '') }}">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" rows="3" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address', $courier->address ?? '') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Vehicle Information -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900">Vehicle Information</h3>

                        <!-- Vehicle Type -->
                        <div>
                            <label for="vehicle_type" class="block text-sm font-medium text-gray-700">Vehicle Type</label>
                            <select name="vehicle_type" id="vehicle_type" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select vehicle type</option>
                                <option value="motorcycle" {{ old('vehicle_type', $courier->vehicle_type ?? '') === 'motorcycle' ? 'selected' : '' }}>
                                    Motorcycle
                                </option>
                                <option value="car" {{ old('vehicle_type', $courier->vehicle_type ?? '') === 'car' ? 'selected' : '' }}>
                                    Car
                                </option>
                                <option value="bicycle" {{ old('vehicle_type', $courier->vehicle_type ?? '') === 'bicycle' ? 'selected' : '' }}>
                                    Bicycle
                                </option>
                            </select>
                            @error('vehicle_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Vehicle Number -->
                        <div>
                            <label for="vehicle_number" class="block text-sm font-medium text-gray-700">Vehicle Number</label>
                            <input type="text" name="vehicle_number" id="vehicle_number" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                value="{{ old('vehicle_number', $courier->vehicle_number ?? '') }}">
                            @error('vehicle_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- License Number -->
                        <div>
                            <label for="license_number" class="block text-sm font-medium text-gray-700">License Number</label>
                            <input type="text" name="license_number" id="license_number" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                value="{{ old('license_number', $courier->license_number ?? '') }}">
                            @error('license_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Photo -->
                        <div>
                            <label for="photo" class="block text-sm font-medium text-gray-700">Photo</label>
                            <div class="mt-1 flex items-center space-x-4">
                                @if(isset($courier) && $courier->photo)
                                    <div class="flex-shrink-0 h-20 w-20">
                                        <img class="h-20 w-20 rounded-full object-cover" 
                                            src="{{ $courier->photo_url }}" 
                                            alt="{{ $courier->name }}">
                                    </div>
                                @endif
                                <input type="file" name="photo" id="photo" accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    {{ !isset($courier) ? 'required' : '' }}>
                            </div>
                            @error('photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="status" id="status" value="1"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                {{ old('status', $courier->status ?? true) ? 'checked' : '' }}>
                            <label for="status" class="text-sm font-medium text-gray-700">Active</label>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('admin.couriers.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        {{ isset($courier) ? 'Update Courier' : 'Create Courier' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('photo');
    const vehicleTypeSelect = document.getElementById('vehicle_type');

    photoInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            if (e.target.files[0].size > 2 * 1024 * 1024) {
                alert('Photo size must not exceed 2MB');
                e.target.value = '';
            }
        }
    });

    vehicleTypeSelect.addEventListener('change', function() {
        const vehicleNumberInput = document.getElementById('vehicle_number');
        if (this.value === 'bicycle') {
            vehicleNumberInput.removeAttribute('required');
            vehicleNumberInput.setAttribute('disabled', 'disabled');
            vehicleNumberInput.value = '';
        } else {
            vehicleNumberInput.setAttribute('required', 'required');
            vehicleNumberInput.removeAttribute('disabled');
        }
    });
});
</script>
@endpush
@endsection 
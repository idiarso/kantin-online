@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Batch Generate Digital Cards</h2>
        <div class="flex space-x-4">
            <a href="{{ route('admin.cards.generate') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-plus"></i> Individual Generation
            </a>
            <a href="{{ route('admin.cards.print') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-print"></i> Print Cards
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
        <div class="p-8">
            <div class="space-y-8">
                <!-- Statistics -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900">User Statistics</h3>
                    <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <!-- Students Stats -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user-graduate text-2xl text-green-600"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Students Without Cards
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900">
                                                    {{ $roles['student'] }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Teachers Stats -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-chalkboard-teacher text-2xl text-blue-600"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">
                                                Teachers Without Cards
                                            </dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900">
                                                    {{ $roles['teacher'] }}
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Batch Generation Form -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Generate Cards in Batch</h3>
                    <p class="mt-1 text-sm text-gray-500">Generate multiple digital cards at once for users without cards.</p>

                    <form action="{{ route('admin.cards.batch.process') }}" method="POST" class="mt-6 space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700">User Role</label>
                                <select name="role" id="role" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select Role</option>
                                    <option value="student">Students</option>
                                    <option value="teacher">Teachers</option>
                                </select>
                                @error('role')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="count" class="block text-sm font-medium text-gray-700">Number of Cards</label>
                                <input type="number" name="count" id="count" min="1" max="100" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <p class="mt-1 text-xs text-gray-500">Maximum 100 cards per batch</p>
                                @error('count')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                Generate Cards
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const countInput = document.getElementById('count');
    const maxCounts = {
        'student': {{ $roles['student'] }},
        'teacher': {{ $roles['teacher'] }}
    };

    roleSelect.addEventListener('change', function() {
        const role = this.value;
        if (role) {
            countInput.max = maxCounts[role];
            countInput.value = Math.min(countInput.value || 0, maxCounts[role]);
        }
    });
});
</script>
@endpush
@endsection 
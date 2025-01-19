@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Print Digital Cards</h2>
        <div class="flex space-x-4">
            <a href="{{ route('admin.cards.generate') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-plus"></i> Generate New Cards
            </a>
            <a href="{{ route('admin.cards.batch') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-layer-group"></i> Batch Generation
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
        <form action="{{ route('admin.cards.download-batch') }}" method="POST" class="p-8">
            @csrf

            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Select Cards to Print</h3>
                    <p class="mt-1 text-sm text-gray-500">Select users to print digital cards for. Only users with generated cards are shown.</p>
                </div>

                <!-- Filters -->
                <div class="flex gap-4 mb-4">
                    <div class="flex-1">
                        <input type="text" id="search" placeholder="Search by name, ID, or card number..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <select id="role-filter" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Roles</option>
                        <option value="student">Students</option>
                        <option value="teacher">Teachers</option>
                    </select>
                </div>

                <!-- Users Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Card Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Generated At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr class="user-row" data-role="{{ $user->role }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="users[]" value="{{ $user->id }}" 
                                            class="user-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">
                                            ID: {{ $user->role === 'student' ? $user->student_id : $user->employee_id }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $user->role === 'teacher' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $user->card_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $user->card_generated_at ? $user->card_generated_at->format('M d, Y H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.cards.download', $user) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                        No digital cards found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $users->links() }}
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Download Selected Cards
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All Functionality
    const selectAll = document.getElementById('select-all');
    const userCheckboxes = document.getElementsByClassName('user-checkbox');

    selectAll.addEventListener('change', function() {
        Array.from(userCheckboxes).forEach(checkbox => {
            const row = checkbox.closest('.user-row');
            if (!row.classList.contains('hidden')) {
                checkbox.checked = selectAll.checked;
            }
        });
    });

    // Search Functionality
    const searchInput = document.getElementById('search');
    const rows = document.getElementsByClassName('user-row');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        Array.from(rows).forEach(row => {
            const text = row.textContent.toLowerCase();
            row.classList.toggle('hidden', !text.includes(searchTerm));
        });
    });

    // Role Filter
    const roleFilter = document.getElementById('role-filter');
    
    roleFilter.addEventListener('change', function() {
        const selectedRole = this.value;
        Array.from(rows).forEach(row => {
            if (!selectedRole || row.dataset.role === selectedRole) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    });
});
</script>
@endpush
@endsection 
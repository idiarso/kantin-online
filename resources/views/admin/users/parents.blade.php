@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Parents Management</h2>
        <a href="{{ route('admin.users.create', ['role' => 'parent']) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-plus mr-2"></i> Add New Parent
        </a>
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
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <div class="flex-1">
                    <input type="text" id="search" placeholder="Search parents..."
                        class="w-full sm:w-1/3 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-center space-x-4">
                    <select id="status-filter" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Children</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="user-row" data-status="{{ $user->status ? 'active' : 'inactive' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                src="{{ $user->profile_photo_url }}" 
                                                alt="{{ $user->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">Joined {{ $user->created_at->format('M d, Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @foreach($user->children as $child)
                                            <div class="mb-1">{{ $child->name }} ({{ $child->class }})</div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Rp {{ number_format($user->balance, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $user->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                            class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="toggleStatus({{ $user->id }})" 
                                            class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-toggle-{{ $user->status ? 'on' : 'off' }}"></i>
                                        </button>
                                        <button onclick="deleteUser({{ $user->id }})" 
                                            class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No parents found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Parent</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete this parent? This action cannot be undone.
                </p>
            </div>
            <div class="mt-4 flex justify-center space-x-4">
                <button onclick="hideDeleteModal()"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Cancel
                </button>
                <form id="delete-form" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleStatus(userId) {
    fetch(`/admin/users/${userId}/status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    }).then(response => {
        if (response.ok) {
            window.location.reload();
        }
    });
}

function deleteUser(userId) {
    const modal = document.getElementById('delete-modal');
    const form = document.getElementById('delete-form');
    
    form.action = `/admin/users/${userId}`;
    modal.classList.remove('hidden');
}

function hideDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const statusFilter = document.getElementById('status-filter');
    const rows = document.getElementsByClassName('user-row');

    function filterRows() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;

        Array.from(rows).forEach(row => {
            const text = row.textContent.toLowerCase();
            const status = row.dataset.status;
            
            const matchesSearch = text.includes(searchTerm);
            const matchesStatus = !statusValue || status === statusValue;

            row.classList.toggle('hidden', !matchesSearch || !matchesStatus);
        });
    }

    searchInput.addEventListener('input', filterRows);
    statusFilter.addEventListener('change', filterRows);
});
</script>
@endpush
@endsection 
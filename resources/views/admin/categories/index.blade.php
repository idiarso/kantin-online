@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Menu Categories</h2>
        <a href="{{ route('admin.categories.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-plus"></i> Add New Category
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

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <div class="flex-1">
                    <input type="text" id="search" placeholder="Search categories..."
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($categories as $category)
                            <tr class="category-row" data-status="{{ $category->status }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($category->image)
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-lg object-cover" 
                                                    src="{{ Storage::url($category->image) }}" 
                                                    alt="{{ $category->name }}">
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ Str::limit($category->description, 100) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $category->products_count }} products</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button onclick="updateStatus('{{ $category->id }}')" class="status-badge">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $category->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($category->status) }}
                                        </span>
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($category->products_count === 0)
                                        <form action="{{ route('admin.categories.destroy', $category) }}" 
                                            method="POST" 
                                            class="inline-block"
                                            onsubmit="return confirm('Are you sure you want to delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateStatus(categoryId) {
    fetch(`/admin/categories/${categoryId}/status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const badge = event.target.closest('.status-badge').querySelector('span');
            badge.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
            badge.classList.remove('bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800');
            if (data.status === 'active') {
                badge.classList.add('bg-green-100', 'text-green-800');
            } else {
                badge.classList.add('bg-red-100', 'text-red-800');
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const statusFilter = document.getElementById('status-filter');
    const rows = document.getElementsByClassName('category-row');

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
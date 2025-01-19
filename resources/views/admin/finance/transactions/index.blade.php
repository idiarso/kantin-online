@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Transactions</h2>
        <button onclick="showExportModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-download"></i> Export
        </button>
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
                    <input type="text" id="search" placeholder="Search transactions..."
                        class="w-full sm:w-1/3 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-center space-x-4">
                    <select id="type-filter" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Types</option>
                        <option value="deposit">Deposit</option>
                        <option value="purchase">Purchase</option>
                        <option value="refund">Refund</option>
                    </select>
                    <select id="status-filter" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="completed">Completed</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                            <tr class="transaction-row" 
                                data-type="{{ $transaction->type }}"
                                data-status="{{ $transaction->status }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $transaction->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $transaction->user->role }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $transaction->type === 'deposit' ? 'bg-green-100 text-green-800' : 
                                           ($transaction->type === 'purchase' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium {{ $transaction->type === 'deposit' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->type === 'deposit' ? '+' : '-' }}
                                        {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($transaction->balance_after, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $transaction->description ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No transactions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div id="export-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Export Transactions</h3>
            <form action="{{ route('admin.finance.reports.download') }}" method="GET" class="mt-4">
                <input type="hidden" name="type" value="transactions">
                
                <div class="mt-4">
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" id="start_date" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="mt-4">
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" id="end_date" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" onclick="hideExportModal()"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Export
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showExportModal() {
    document.getElementById('export-modal').classList.remove('hidden');
}

function hideExportModal() {
    document.getElementById('export-modal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const typeFilter = document.getElementById('type-filter');
    const statusFilter = document.getElementById('status-filter');
    const rows = document.getElementsByClassName('transaction-row');

    function filterRows() {
        const searchTerm = searchInput.value.toLowerCase();
        const typeValue = typeFilter.value;
        const statusValue = statusFilter.value;

        Array.from(rows).forEach(row => {
            const text = row.textContent.toLowerCase();
            const type = row.dataset.type;
            const status = row.dataset.status;
            
            const matchesSearch = text.includes(searchTerm);
            const matchesType = !typeValue || type === typeValue;
            const matchesStatus = !statusValue || status === statusValue;

            row.classList.toggle('hidden', !matchesSearch || !matchesType || !matchesStatus);
        });
    }

    searchInput.addEventListener('input', filterRows);
    typeFilter.addEventListener('change', filterRows);
    statusFilter.addEventListener('change', filterRows);
});
</script>
@endpush
@endsection 
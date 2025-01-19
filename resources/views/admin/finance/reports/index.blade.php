@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Financial Reports</h2>
        <button onclick="showExportModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-download"></i> Export Report
        </button>
    </div>
@endsection

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 bg-opacity-75">
                    <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-500">Total Revenue</h4>
                    <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 bg-opacity-75">
                    <i class="fas fa-exchange-alt text-blue-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-500">Total Transactions</h4>
                    <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['total_transactions']) }}</p>
                </div>
            </div>
        </div>

        <!-- Average Transaction -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 bg-opacity-75">
                    <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-500">Average Transaction</h4>
                    <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($stats['average_transaction'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 bg-opacity-75">
                    <i class="fas fa-users text-yellow-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-500">Active Users</h4>
                    <p class="text-lg font-semibold text-gray-900">{{ number_format($stats['active_users']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Revenue Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Revenue</h3>
            <canvas id="monthlyRevenueChart" height="300"></canvas>
        </div>

        <!-- Daily Transactions Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Daily Transactions</h3>
            <canvas id="dailyTransactionsChart" height="300"></canvas>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Top Products</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($stats['top_products'] as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $product->category->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($product->total_sales) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($product->total_revenue, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Methods</h3>
            <canvas id="paymentMethodsChart" height="300"></canvas>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div id="export-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Export Financial Report</h3>
            <form action="{{ route('admin.finance.reports.download') }}" method="GET" class="mt-4">
                <input type="hidden" name="type" value="report">
                
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function showExportModal() {
    document.getElementById('export-modal').classList.remove('hidden');
}

function hideExportModal() {
    document.getElementById('export-modal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    // Monthly Revenue Chart
    const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(monthlyRevenueCtx, {
        type: 'line',
        data: {
            labels: @json($stats['monthly_revenue']['labels']),
            datasets: [{
                label: 'Revenue',
                data: @json($stats['monthly_revenue']['data']),
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Daily Transactions Chart
    const dailyTransactionsCtx = document.getElementById('dailyTransactionsChart').getContext('2d');
    new Chart(dailyTransactionsCtx, {
        type: 'bar',
        data: {
            labels: @json($stats['daily_transactions']['labels']),
            datasets: [{
                label: 'Transactions',
                data: @json($stats['daily_transactions']['data']),
                backgroundColor: '#60A5FA',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Payment Methods Chart
    const paymentMethodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
    new Chart(paymentMethodsCtx, {
        type: 'doughnut',
        data: {
            labels: @json($stats['payment_methods']['labels']),
            datasets: [{
                data: @json($stats['payment_methods']['data']),
                backgroundColor: ['#10B981', '#60A5FA', '#F59E0B'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            cutout: '60%'
        }
    });
});
</script>
@endpush
@endsection 
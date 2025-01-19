<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Laporan Penjualan') }}
            </h2>
            <div class="flex space-x-4">
                <form action="{{ route('kantin.admin.reports.export') }}" method="GET" class="flex items-center space-x-4">
                    <div>
                        <x-input-label for="start_date" value="Tanggal Mulai" />
                        <x-text-input type="date" 
                                     name="start_date" 
                                     value="{{ $startDate->format('Y-m-d') }}" />
                    </div>
                    <div>
                        <x-input-label for="end_date" value="Tanggal Akhir" />
                        <x-text-input type="date" 
                                     name="end_date" 
                                     value="{{ $endDate->format('Y-m-d') }}" />
                    </div>
                    <div class="flex items-end space-x-2">
                        <x-primary-button type="submit" formaction="{{ route('kantin.admin.reports.index') }}">
                            Filter
                        </x-primary-button>
                        <x-secondary-button type="submit">
                            Export Excel
                        </x-secondary-button>
                    </div>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Sales Chart -->
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="mb-4 text-lg font-medium">Grafik Penjualan</h3>
                    <canvas id="salesChart"></canvas>
                </div>

                <!-- Payment Method Stats -->
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="mb-4 text-lg font-medium">Metode Pembayaran</h3>
                    <div class="space-y-4">
                        @foreach($paymentStats as $stat)
                            <div class="p-4 border rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium">
                                            {{ $stat->payment_method === 'balance' ? 'Saldo' : 'Tunai' }}
                                        </h4>
                                        <p class="text-sm text-gray-600">
                                            {{ $stat->total_transactions }} transaksi
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold">
                                            Rp {{ number_format($stat->total_amount, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Product Performance -->
                <div class="p-6 bg-white rounded-lg shadow lg:col-span-2">
                    <h3 class="mb-4 text-lg font-medium">Performa Produk</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Produk
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Kategori
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Total Terjual
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Total Pendapatan
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($productPerformance as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $product->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $product->category->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $product->total_sold }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            Rp {{ number_format($product->total_revenue, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Category Performance -->
                <div class="p-6 bg-white rounded-lg shadow lg:col-span-2">
                    <h3 class="mb-4 text-lg font-medium">Performa Kategori</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($categoryPerformance as $category)
                            <div class="p-4 border rounded-lg">
                                <h4 class="mb-2 text-lg font-medium">{{ $category->name }}</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Total Penjualan</p>
                                        <p class="text-xl font-bold">{{ $category->total_sales }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Total Pendapatan</p>
                                        <p class="text-xl font-bold">
                                            Rp {{ number_format($category->total_revenue, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesReport->pluck('date')) !!},
                datasets: [{
                    label: 'Pendapatan',
                    data: {!! json_encode($salesReport->pluck('revenue')) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout> 
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard Kantin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Search and Filter Section -->
            <div class="p-6 mb-6 bg-white rounded-lg shadow">
                <form action="{{ route('kantin.admin.dashboard') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Pencarian</label>
                            <input type="text" name="search" id="search" 
                                value="{{ request('search') }}"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Cari produk atau pesanan...">
                        </div>
                        <div>
                            <label for="date_range" class="block text-sm font-medium text-gray-700">Rentang Tanggal</label>
                            <input type="date" name="date_from" id="date_from"
                                value="{{ request('date_from') }}"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <input type="date" name="date_to" id="date_to"
                                value="{{ request('date_to') }}"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status Pesanan</label>
                            <select name="status" id="status" 
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Kategori Produk</label>
                            <select name="category" id="category" 
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Filter
                        </button>
                        <div class="space-x-2">
                            <a href="{{ route('kantin.admin.export.excel') }}" class="px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-700">
                                Export Excel
                            </a>
                            <a href="{{ route('kantin.admin.export.pdf') }}" class="px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-700">
                                Export PDF
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Notification Section -->
            <div id="notifications" class="mb-6">
                @if($newOrders->isNotEmpty())
                    <div class="p-4 mb-4 bg-blue-100 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Pesanan Baru!
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Anda memiliki {{ $newOrders->count() }} pesanan baru yang menunggu untuk diproses.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-4">
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500">Pesanan Hari Ini</h3>
                    <p class="text-2xl font-bold">{{ $todayStats['orders'] }}</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500">Pendapatan Hari Ini</h3>
                    <p class="text-2xl font-bold">Rp {{ number_format($todayStats['revenue'], 0, ',', '.') }}</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500">Produk Terjual</h3>
                    <p class="text-2xl font-bold">{{ $todayStats['products_sold'] }}</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500">Pesanan Pending</h3>
                    <p class="text-2xl font-bold">{{ $todayStats['pending_orders'] }}</p>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="p-6 mb-6 bg-white rounded-lg shadow">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium">Pendapatan Bulan Ini</h3>
                    <div class="text-sm text-gray-500">
                        vs bulan lalu
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-3xl font-bold">
                            Rp {{ number_format($currentMonthRevenue, 0, ',', '.') }}
                        </p>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2 py-1 text-sm rounded-full
                                {{ $revenueGrowth >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($revenueGrowth >= 0)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                    @endif
                                </svg>
                                {{ abs(round($revenueGrowth, 1)) }}%
                            </span>
                        </div>
                    </div>
                    <div>
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Sales Chart -->
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="mb-4 text-lg font-medium">Grafik Penjualan (30 Hari Terakhir)</h3>
                    <canvas id="salesChart"></canvas>
                </div>

                <!-- Payment Method Distribution -->
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="mb-4 text-lg font-medium">Distribusi Metode Pembayaran</h3>
                    <canvas id="paymentChart"></canvas>
                </div>

                <!-- Popular Products -->
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="mb-4 text-lg font-medium">Produk Terpopuler</h3>
                    <div class="space-y-4">
                        @foreach($popularProducts as $product)
                            <div class="flex items-center justify-between p-4 border rounded">
                                <div>
                                    <h4 class="font-medium">{{ $product->name }}</h4>
                                    <p class="text-sm text-gray-600">
                                        Terjual: {{ $product->total_sold }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Stok: {{ $product->stock }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="p-6 bg-white rounded-lg shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium">Pesanan Terbaru</h3>
                        <a href="{{ route('kantin.admin.orders.index') }}" 
                           class="text-sm text-blue-600 hover:text-blue-800">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="space-y-4">
                        @foreach($recentOrders as $order)
                            <div class="p-4 border rounded">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <span class="font-medium">#{{ $order->id }}</span>
                                        <span class="ml-2 text-sm text-gray-600">
                                            {{ $order->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        text-{{ $order->status_color }}-800 
                                        bg-{{ $order->status_color }}-100">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="text-sm">
                                        <p class="font-medium">{{ $order->user->name }}</p>
                                        <p class="text-gray-600">
                                            {{ $order->items->count() }} item(s)
                                        </p>
                                    </div>
                                    <p class="font-medium">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Low Stock Alert -->
            @if($lowStockProducts->isNotEmpty())
                <div class="p-6 mt-6 bg-yellow-50 rounded-lg">
                    <h3 class="mb-4 text-lg font-medium text-yellow-800">Peringatan Stok Menipis</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($lowStockProducts as $product)
                            <div class="p-4 bg-white rounded shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium">{{ $product->name }}</h4>
                                        <p class="text-sm text-gray-600">
                                            Kategori: {{ $product->category->name }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-yellow-600">
                                            {{ $product->stock }}
                                        </p>
                                        <p class="text-xs text-gray-500">stok tersisa</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesData->pluck('date')) !!},
                datasets: [{
                    label: 'Penjualan',
                    data: {!! json_encode($salesData->pluck('total')) !!},
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

        // Payment Method Chart
        const paymentCtx = document.getElementById('paymentChart').getContext('2d');
        new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: ['Saldo', 'Tunai'],
                datasets: [{
                    data: [
                        {{ $paymentMethodStats['balance'] ?? 0 }},
                        {{ $paymentMethodStats['cash'] ?? 0 }}
                    ],
                    backgroundColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: ['Bulan Lalu', 'Bulan Ini'],
                datasets: [{
                    data: [{{ $lastMonthRevenue }}, {{ $currentMonthRevenue }}],
                    backgroundColor: ['rgb(156, 163, 175)', 'rgb(59, 130, 246)']
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
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });

        // Real-time notifications using Laravel Echo
        window.Echo.private('orders')
            .listen('NewOrder', (e) => {
                const notifications = document.getElementById('notifications');
                const newNotif = `
                    <div class="p-4 mb-4 bg-blue-100 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Pesanan Baru!
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Ada pesanan baru dari ${e.order.user.name}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                notifications.innerHTML = newNotif + notifications.innerHTML;
            });
    </script>
    @endpush
</x-app-layout> 
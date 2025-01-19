<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Daftar Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <div class="flex items-center justify-between">
                            <input type="text" 
                                   id="search" 
                                   placeholder="Cari pesanan..."
                                   class="w-64 px-4 py-2 border rounded-lg">
                            
                            <div class="flex space-x-2">
                                <select id="statusFilter" 
                                        class="px-4 py-2 border rounded-lg">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="processing">Diproses</option>
                                    <option value="ready">Siap</option>
                                    <option value="completed">Selesai</option>
                                    <option value="cancelled">Dibatalkan</option>
                                </select>

                                <select id="dateFilter" 
                                        class="px-4 py-2 border rounded-lg">
                                    <option value="today">Hari Ini</option>
                                    <option value="week">Minggu Ini</option>
                                    <option value="month">Bulan Ini</option>
                                    <option value="all">Semua</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Order ID
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Pelanggan
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Total
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Metode Pembayaran
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Waktu
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            #{{ $order->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium">{{ $order->user->name }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $order->user->role === 'student' ? 'Siswa' : 'Guru' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                {{ $order->payment_method === 'balance' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $order->payment_method === 'balance' ? 'Saldo' : 'Tunai' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <select class="status-select px-2 py-1 text-sm rounded border
                                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                       ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                                        'bg-yellow-100 text-yellow-800') }}"
                                                    data-order-id="{{ $order->id }}">
                                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>
                                                    Pending
                                                </option>
                                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>
                                                    Diproses
                                                </option>
                                                <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>
                                                    Siap
                                                </option>
                                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>
                                                    Selesai
                                                </option>
                                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>
                                                    Dibatalkan
                                                </option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm">
                                                {{ $order->created_at->format('d/m/Y H:i') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $order->created_at->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('kantin.admin.orders.show', $order) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Search functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(row => {
                const orderId = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const customer = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                row.style.display = orderId.includes(searchTerm) || customer.includes(searchTerm) ? '' : 'none';
            });
        });

        // Status filter
        const statusFilter = document.getElementById('statusFilter');
        statusFilter.addEventListener('change', function() {
            const status = this.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(row => {
                if (!status) {
                    row.style.display = '';
                    return;
                }
                const orderStatus = row.querySelector('.status-select').value.toLowerCase();
                row.style.display = orderStatus === status ? '' : 'none';
            });
        });

        // Status update
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', async function() {
                const orderId = this.dataset.orderId;
                const newStatus = this.value;

                try {
                    const response = await fetch(`/kantin-admin/orders/${orderId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ status: newStatus })
                    });

                    const data = await response.json();
                    if (data.success) {
                        // Update select styling based on new status
                        this.className = `status-select px-2 py-1 text-sm rounded border ${
                            newStatus === 'completed' ? 'bg-green-100 text-green-800' : 
                            (newStatus === 'cancelled' ? 'bg-red-100 text-red-800' : 
                             'bg-yellow-100 text-yellow-800')
                        }`;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Gagal memperbarui status');
                }
            });
        });
    </script>
    @endpush
</x-app-layout> 
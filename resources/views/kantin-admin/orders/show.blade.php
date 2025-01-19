<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Detail Pesanan #' . $order->id) }}
            </h2>
            <a href="{{ route('kantin.admin.orders.index') }}" 
               class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <!-- Order Info -->
                <div class="md:col-span-2">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="mb-4 text-lg font-medium">Informasi Pesanan</h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Status</p>
                                    <select class="status-select mt-1 px-3 py-1 text-sm rounded border
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
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-600">Waktu Pemesanan</p>
                                    <p class="mt-1 font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Metode Pembayaran</p>
                                    <p class="mt-1 font-medium">{{ $order->payment_method === 'balance' ? 'Saldo' : 'Tunai' }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Status Pembayaran</p>
                                    <p class="mt-1 font-medium">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6">
                                <h4 class="mb-2 font-medium">Detail Item</h4>
                                <div class="space-y-4">
                                    @foreach($order->items as $item)
                                        <div class="flex items-center justify-between p-4 border rounded-lg">
                                            <div>
                                                <h5 class="font-medium">{{ $item->product->name }}</h5>
                                                <p class="text-sm text-gray-600">
                                                    {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            <p class="font-medium">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="flex justify-between mt-4 pt-4 border-t">
                                    <span class="font-medium">Total</span>
                                    <span class="text-xl font-bold">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div>
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="mb-4 text-lg font-medium">Informasi Pelanggan</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-600">Nama</p>
                                    <p class="mt-1 font-medium">{{ $order->user->name }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Role</p>
                                    <p class="mt-1 font-medium">
                                        {{ $order->user->role === 'student' ? 'Siswa' : 'Guru' }}
                                    </p>
                                </div>

                                @if($order->user->role === 'student')
                                    <div>
                                        <p class="text-sm text-gray-600">Kelas</p>
                                        <p class="mt-1 font-medium">{{ $order->user->class }}</p>
                                    </div>
                                @endif

                                <div>
                                    <p class="text-sm text-gray-600">ID</p>
                                    <p class="mt-1 font-medium">
                                        {{ $order->user->student_id ?? $order->user->employee_id }}
                                    </p>
                                </div>

                                @if($order->note)
                                    <div class="pt-4 mt-4 border-t">
                                        <p class="text-sm text-gray-600">Catatan</p>
                                        <p class="mt-1">{{ $order->note }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Status update
        document.querySelector('.status-select').addEventListener('change', async function() {
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
                    this.className = `status-select mt-1 px-3 py-1 text-sm rounded border ${
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
    </script>
    @endpush
</x-app-layout> 
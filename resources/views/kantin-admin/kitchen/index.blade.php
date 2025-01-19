<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Tampilan Dapur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Pending & Processing Orders -->
                <div>
                    <h3 class="mb-4 text-lg font-medium">Pesanan Dalam Proses</h3>
                    <div class="space-y-4">
                        @forelse($pendingOrders as $order)
                            <div class="p-4 bg-white rounded-lg shadow">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <span class="text-lg font-bold">#{{ $order->id }}</span>
                                        <span class="ml-2 text-sm text-gray-600">
                                            {{ $order->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <select class="status-select px-3 py-1 text-sm rounded border
                                            {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}"
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
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <div class="text-sm font-medium">{{ $order->user->name }}</div>
                                    <div class="text-xs text-gray-600">
                                        {{ $order->user->role === 'student' ? 'Siswa - ' . $order->user->class : 'Guru' }}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    @foreach($order->items as $item)
                                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                            <div class="flex items-center">
                                                <span class="w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-800 rounded-full font-bold">
                                                    {{ $item->quantity }}
                                                </span>
                                                <span class="ml-3">{{ $item->product->name }}</span>
                                            </div>
                                            <span class="text-sm text-gray-600">
                                                {{ $item->product->preparation_time }} menit
                                            </span>
                                        </div>
                                    @endforeach
                                </div>

                                @if($order->note)
                                    <div class="mt-4 p-2 bg-yellow-50 text-yellow-800 rounded">
                                        <span class="font-medium">Catatan:</span> {{ $order->note }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="p-4 text-center bg-white rounded-lg shadow">
                                <p class="text-gray-600">Tidak ada pesanan yang perlu diproses</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Ready Orders -->
                <div>
                    <h3 class="mb-4 text-lg font-medium">Pesanan Siap Diambil</h3>
                    <div class="space-y-4">
                        @forelse($readyOrders as $order)
                            <div class="p-4 bg-white rounded-lg shadow">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <span class="text-lg font-bold">#{{ $order->id }}</span>
                                        <span class="ml-2 text-sm text-gray-600">
                                            {{ $order->updated_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <button class="complete-order px-3 py-1 text-sm bg-green-100 text-green-800 rounded hover:bg-green-200"
                                            data-order-id="{{ $order->id }}">
                                        Selesai
                                    </button>
                                </div>

                                <div class="mb-4">
                                    <div class="text-sm font-medium">{{ $order->user->name }}</div>
                                    <div class="text-xs text-gray-600">
                                        {{ $order->user->role === 'student' ? 'Siswa - ' . $order->user->class : 'Guru' }}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    @foreach($order->items as $item)
                                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                            <div class="flex items-center">
                                                <span class="w-8 h-8 flex items-center justify-center bg-green-100 text-green-800 rounded-full font-bold">
                                                    {{ $item->quantity }}
                                                </span>
                                                <span class="ml-3">{{ $item->product->name }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center bg-white rounded-lg shadow">
                                <p class="text-gray-600">Tidak ada pesanan yang siap diambil</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
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
                        // Reload page to update order lists
                        window.location.reload();
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Gagal memperbarui status');
                }
            });
        });

        // Complete order
        document.querySelectorAll('.complete-order').forEach(button => {
            button.addEventListener('click', async function() {
                const orderId = this.dataset.orderId;

                try {
                    const response = await fetch(`/kantin-admin/orders/${orderId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ status: 'completed' })
                    });

                    const data = await response.json();
                    if (data.success) {
                        // Reload page to update order lists
                        window.location.reload();
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Gagal menyelesaikan pesanan');
                }
            });
        });

        // Auto refresh every 30 seconds
        setInterval(() => {
            window.location.reload();
        }, 30000);
    </script>
    @endpush
</x-app-layout> 
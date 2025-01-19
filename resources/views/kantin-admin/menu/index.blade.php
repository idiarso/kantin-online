<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Manajemen Menu') }}
            </h2>
            <a href="{{ route('kantin.admin.menu.create') }}" 
               class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                Tambah Menu
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <input type="text" 
                               id="search" 
                               placeholder="Cari menu..."
                               class="w-full px-4 py-2 border rounded-lg">
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Gambar
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Nama
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Kategori
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Harga
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Stok
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($products as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img src="{{ Storage::url($product->image) }}" 
                                                 alt="{{ $product->name }}"
                                                 class="w-16 h-16 rounded object-cover">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-500">
                                                Persiapan: {{ $product->preparation_time }} menit
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $product->category->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" 
                                                   value="{{ $product->stock }}"
                                                   min="0"
                                                   class="w-20 px-2 py-1 text-center border rounded stock-input"
                                                   data-id="{{ $product->id }}">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button class="status-toggle px-3 py-1 text-sm rounded-full
                                                {{ $product->status === 'available' 
                                                    ? 'bg-green-100 text-green-800' 
                                                    : 'bg-red-100 text-red-800' }}"
                                                    data-id="{{ $product->id }}">
                                                {{ $product->status === 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('kantin.admin.menu.edit', $product) }}" 
                                                   class="text-blue-600 hover:text-blue-900">
                                                    Edit
                                                </a>
                                                <form action="{{ route('kantin.admin.menu.destroy', $product) }}" 
                                                      method="POST"
                                                      onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $products->links() }}
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
                const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const category = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                row.style.display = name.includes(searchTerm) || category.includes(searchTerm) ? '' : 'none';
            });
        });

        // Stock update
        document.querySelectorAll('.stock-input').forEach(input => {
            input.addEventListener('change', async function() {
                const productId = this.dataset.id;
                const newStock = this.value;

                try {
                    const response = await fetch(`/kantin-admin/menu/${productId}/stock`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ stock: newStock })
                    });

                    const data = await response.json();
                    if (data.success) {
                        // Optional: Show success message
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Gagal memperbarui stok');
                }
            });
        });

        // Status toggle
        document.querySelectorAll('.status-toggle').forEach(button => {
            button.addEventListener('click', async function() {
                const productId = this.dataset.id;

                try {
                    const response = await fetch(`/kantin-admin/menu/${productId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();
                    if (data.success) {
                        if (data.status === 'available') {
                            this.classList.remove('bg-red-100', 'text-red-800');
                            this.classList.add('bg-green-100', 'text-green-800');
                            this.textContent = 'Tersedia';
                        } else {
                            this.classList.remove('bg-green-100', 'text-green-800');
                            this.classList.add('bg-red-100', 'text-red-800');
                            this.textContent = 'Tidak Tersedia';
                        }
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
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Point of Sale') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Product List -->
                <div class="col-span-2 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-4">
                            <input type="text" 
                                   id="search"
                                   placeholder="Cari produk..."
                                   class="w-full px-4 py-2 border rounded-lg">
                        </div>

                        <div class="grid grid-cols-2 gap-4 md:grid-cols-3">
                            @foreach($categories as $category)
                                <div class="space-y-4">
                                    <h3 class="text-lg font-semibold">{{ $category->name }}</h3>
                                    @foreach($category->products as $product)
                                        <div class="p-4 border rounded-lg product-item"
                                             data-id="{{ $product->id }}"
                                             data-name="{{ $product->name }}"
                                             data-price="{{ $product->price }}"
                                             data-stock="{{ $product->stock }}">
                                            <h4 class="font-medium">{{ $product->name }}</h4>
                                            <p class="text-sm text-gray-600">
                                                Stok: {{ $product->stock }}
                                            </p>
                                            <p class="text-lg font-semibold text-blue-600">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </p>
                                            <button class="w-full px-4 py-2 mt-2 text-white bg-blue-500 rounded hover:bg-blue-600 add-to-cart">
                                                Tambah
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Cart -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-semibold">Keranjang</h3>
                        
                        <div class="mb-4">
                            <button id="scanQr" 
                                    class="w-full px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">
                                Scan QR Code
                            </button>
                        </div>

                        <div id="customerInfo" class="hidden mb-4 p-4 border rounded-lg">
                            <h4 class="font-medium customer-name"></h4>
                            <p class="text-sm text-gray-600 customer-balance"></p>
                        </div>

                        <div id="cartItems" class="space-y-4">
                            <!-- Cart items will be inserted here -->
                        </div>

                        <div class="mt-6 pt-4 border-t">
                            <div class="flex justify-between mb-2">
                                <span class="font-medium">Total:</span>
                                <span class="text-xl font-bold" id="cartTotal">Rp 0</span>
                            </div>

                            <div class="mb-4">
                                <select id="paymentMethod" class="w-full px-4 py-2 border rounded">
                                    <option value="balance">Saldo</option>
                                    <option value="cash">Tunai</option>
                                </select>
                            </div>

                            <button id="processPayment"
                                    class="w-full px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600 disabled:opacity-50"
                                    disabled>
                                Proses Pembayaran
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // POS JavaScript logic will be added here
    </script>
    @endpush
</x-app-layout> 
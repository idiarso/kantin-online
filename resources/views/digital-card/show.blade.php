<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Kartu Digital') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="max-w-md mx-auto">
                        <div class="overflow-hidden bg-white border rounded-lg shadow-lg">
                            <div class="px-6 py-4">
                                <div class="mb-4 text-center">
                                    <h3 class="text-xl font-bold">{{ $cardData['name'] }}</h3>
                                    <p class="text-gray-600">{{ $cardData['role'] }}</p>
                                    <p class="text-gray-600">ID: {{ $cardData['id'] }}</p>
                                </div>

                                <div class="flex justify-center mb-4">
                                    <div class="p-2 bg-white border rounded">
                                        <img src="data:image/png;base64,{{ base64_encode($qrCode) }}" 
                                             alt="QR Code"
                                             class="w-48 h-48">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Saldo:</p>
                                    <p class="text-2xl font-bold text-blue-600">
                                        Rp {{ number_format($cardData['balance'], 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <div class="px-6 py-4 bg-gray-50">
                                <div class="flex justify-center space-x-4">
                                    <a href="{{ route('digital-card.download') }}" 
                                       class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                                        Download Kartu
                                    </a>
                                    <a href="{{ route('transactions.index') }}" 
                                       class="px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">
                                        Riwayat Transaksi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
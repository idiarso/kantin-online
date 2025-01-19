<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Wallet') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Wallet Balance -->
                <div class="md:col-span-1">
                    <x-card>
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900">Current Balance</h3>
                            <div class="mt-4">
                                <span class="text-3xl font-bold text-gray-900">
                                    Rp {{ number_format(auth()->user()->wallet_balance, 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- Top Up Form -->
                            <div class="mt-6">
                                <form action="{{ route('customer.wallet.topup') }}" method="POST">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <x-label for="amount" value="Top Up Amount" />
                                            <x-input id="amount" name="amount" type="number" class="mt-1 block w-full" required min="10000" step="10000" />
                                            <p class="mt-1 text-sm text-gray-500">Minimum amount: Rp 10.000</p>
                                            <x-input-error for="amount" class="mt-2" />
                                        </div>

                                        <x-button type="submit" class="w-full justify-center">
                                            Top Up Balance
                                        </x-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Transaction History -->
                <div class="md:col-span-2">
                    <x-card>
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-medium text-gray-900">Transaction History</h3>

                                <!-- Filter Form -->
                                <form action="{{ route('customer.wallet.index') }}" method="GET" class="flex items-center space-x-2">
                                    <select name="type" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">All Transactions</option>
                                        <option value="topup" {{ request('type') == 'topup' ? 'selected' : '' }}>Top Up</option>
                                        <option value="payment" {{ request('type') == 'payment' ? 'selected' : '' }}>Payment</option>
                                    </select>
                                    <x-button type="submit">
                                        Filter
                                    </x-button>
                                </form>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($transactions as $transaction)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $transaction->created_at->format('d M Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $transaction->type === 'topup' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                        {{ ucfirst($transaction->type) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $transaction->description }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @if($transaction->type === 'topup')
                                                        <span class="text-green-600">+Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                                                    @else
                                                        <span class="text-red-600">-Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    Rp {{ number_format($transaction->balance_after, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                    No transactions found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-4">
                                {{ $transactions->links() }}
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
    @endif

    @push('scripts')
    <script>
        // Format amount input with thousand separator
        const amountInput = document.getElementById('amount');
        amountInput.addEventListener('input', function(e) {
            // Remove non-numeric characters
            let value = this.value.replace(/\D/g, '');
            // Format number with thousand separator
            this.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        });
    </script>
    @endpush
</x-app-layout> 
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Transaction Detail') }} #{{ $transaction->id }}
            </h2>
            <a href="{{ route('admin.transactions.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Transaction Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Transaction Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="font-medium">Transaction ID:</span>
                                    <span class="ml-2">#{{ $transaction->id }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Type:</span>
                                    <span class="ml-2">{{ ucfirst($transaction->type) }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Amount:</span>
                                    <span class="ml-2">Rp {{ number_format($transaction->amount) }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Status:</span>
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($transaction->status === 'failed' ? 'bg-red-100 text-red-800' : 
                                           'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="font-medium">Date:</span>
                                    <span class="ml-2">{{ $transaction->created_at->format('d M Y H:i:s') }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">User Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="font-medium">Name:</span>
                                    <span class="ml-2">{{ $transaction->user->name }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Email:</span>
                                    <span class="ml-2">{{ $transaction->user->email }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Role:</span>
                                    <span class="ml-2">{{ ucfirst($transaction->user->role) }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Current Balance:</span>
                                    <span class="ml-2">Rp {{ number_format($transaction->user->balance) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Details -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Additional Information</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="font-medium">Description:</span>
                                <p class="mt-1">{{ $transaction->description }}</p>
                            </div>

                            @if($transaction->type === 'topup' && $transaction->payment_proof)
                            <div>
                                <span class="font-medium">Payment Proof:</span>
                                <div class="mt-2">
                                    <img src="{{ Storage::url($transaction->payment_proof) }}" 
                                         alt="Payment Proof" 
                                         class="max-w-md rounded-lg shadow-sm">
                                </div>
                            </div>
                            @endif

                            @if($transaction->type === 'payment' && $transaction->order)
                            <div>
                                <span class="font-medium">Related Order:</span>
                                <div class="mt-2">
                                    <a href="{{ route('admin.orders.show', $transaction->order) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        View Order #{{ $transaction->order->id }}
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($transaction->status === 'pending' && $transaction->type === 'topup')
                    <!-- Action Buttons for Pending Top-up -->
                    <div class="mt-8 flex gap-4">
                        <form action="{{ route('admin.transactions.approve', $transaction) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <x-primary-button>
                                {{ __('Approve Top-up') }}
                            </x-primary-button>
                        </form>

                        <form action="{{ route('admin.transactions.reject', $transaction) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" 
                                    onclick="return confirm('Are you sure you want to reject this top-up?')">
                                {{ __('Reject Top-up') }}
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
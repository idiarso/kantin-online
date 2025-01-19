<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Detail') }} #{{ $order->id }}
            </h2>
            <a href="{{ route('customer.orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Order Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Order Status</h3>
                            <div class="mt-2">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                        @if($order->status === 'pending')
                            <form action="{{ route('customer.orders.cancel', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                        onclick="return confirm('Are you sure you want to cancel this order?')">
                                    Cancel Order
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Order Information -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Order Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="font-medium">Order Date:</span>
                                    <span class="ml-2">{{ $order->created_at->format('d M Y H:i:s') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Pickup Time:</span>
                                    <span class="ml-2">{{ $order->pickup_time->format('d M Y H:i') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Notes:</span>
                                    <p class="mt-1">{{ $order->notes ?: 'No notes' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="font-medium">Total Items:</span>
                                    <span class="ml-2">{{ $order->items->sum('quantity') }} items</span>
                                </div>
                                <div>
                                    <span class="font-medium">Total Amount:</span>
                                    <span class="ml-2">Rp {{ number_format($order->total) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    @if($item->product->image)
                                                        <img src="{{ Storage::url($item->product->image) }}" 
                                                             alt="{{ $item->product->name }}" 
                                                             class="h-12 w-12 object-cover rounded mr-4">
                                                    @endif
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $item->product->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $item->product->category->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->price) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->price * $item->quantity) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold">
                                            Rp {{ number_format($order->total) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
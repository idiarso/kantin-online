<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Detail') }} #{{ $order->id }}
            </h2>
            <a href="{{ route('seller.orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Order Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Order Information</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="font-medium">Order ID:</span>
                                <span class="ml-2">#{{ $order->id }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Status:</span>
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
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
                </div>

                <!-- Customer Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Customer Information</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="font-medium">Name:</span>
                                <span class="ml-2">{{ $order->user->name }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Email:</span>
                                <span class="ml-2">{{ $order->user->email }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Phone:</span>
                                <span class="ml-2">{{ $order->user->phone }}</span>
                            </div>
                            @if($order->user->role === 'student')
                            <div>
                                <span class="font-medium">Class:</span>
                                <span class="ml-2">{{ $order->user->class }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
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
                                    @if($item->product->seller_id === auth()->id())
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
                                                            {{ Str::limit($item->product->description, 50) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->price) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->price * $item->quantity) }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold">
                                        Rp {{ number_format($order->items->where('product.seller_id', auth()->id())->sum(function($item) {
                                            return $item->price * $item->quantity;
                                        })) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            @if($order->status === 'pending')
            <!-- Action Buttons -->
            <div class="mt-6 flex gap-4">
                <form action="{{ route('seller.orders.process', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <x-primary-button>
                        {{ __('Process Order') }}
                    </x-primary-button>
                </form>

                <form action="{{ route('seller.orders.cancel', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                            onclick="return confirm('Are you sure you want to cancel this order?')">
                        {{ __('Cancel Order') }}
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</x-app-layout> 
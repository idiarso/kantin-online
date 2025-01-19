<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Order Details') }} #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <h3 class="mb-4 text-lg font-medium">Order Information</h3>
                            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 py-1 text-xs font-semibold text-{{ $order->status_color }}-800 bg-{{ $order->status_color }}-100 rounded-full">
                                            {{ $order->status }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                                    <dd class="mt-1">{{ ucfirst($order->payment_method) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                                    <dd class="mt-1">{{ ucfirst($order->payment_status) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Pickup Time</dt>
                                    <dd class="mt-1">{{ $order->pickup_time->format('d M Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="mb-4 text-lg font-medium">Order Items</h3>
                            <div class="space-y-4">
                                @foreach($order->items as $item)
                                    <div class="flex items-center justify-between p-4 border rounded-lg">
                                        <div>
                                            <h4 class="font-medium">{{ $item->product->name }}</h4>
                                            <p class="text-sm text-gray-500">
                                                {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-medium">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 text-right">
                                <p class="text-lg font-medium">
                                    Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($order->note)
                        <div class="mt-6">
                            <h3 class="mb-2 text-lg font-medium">Note</h3>
                            <p class="text-gray-700">{{ $order->note }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
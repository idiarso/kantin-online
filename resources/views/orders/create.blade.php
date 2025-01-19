<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Create New Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="space-y-4">
                            <h3 class="text-lg font-medium">Available Products</h3>
                            
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                                @foreach($products as $product)
                                    <div class="p-4 border rounded-lg">
                                        @if($product->image)
                                            <img src="{{ Storage::url($product->image) }}" 
                                                 alt="{{ $product->name }}"
                                                 class="object-cover w-full h-48 mb-4 rounded">
                                        @endif
                                        
                                        <h4 class="mb-2 text-lg font-medium">{{ $product->name }}</h4>
                                        <p class="mb-2 text-sm text-gray-600">{{ $product->description }}</p>
                                        <p class="mb-4 text-lg font-semibold">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </p>
                                        
                                        <div class="flex items-center space-x-2">
                                            <input type="number" 
                                                   name="items[{{ $product->id }}][quantity]" 
                                                   min="0"
                                                   max="{{ $product->stock }}"
                                                   value="0"
                                                   class="w-20 px-3 py-2 border rounded">
                                            <input type="hidden" 
                                                   name="items[{{ $product->id }}][product_id]" 
                                                   value="{{ $product->id }}">
                                            <span class="text-sm text-gray-500">
                                                Available: {{ $product->stock }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 mt-6 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Payment Method
                                </label>
                                <select name="payment_method" 
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                    <option value="balance">Balance (Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }})</option>
                                    <option value="cash">Cash</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Pickup Time
                                </label>
                                <input type="datetime-local" 
                                       name="pickup_time"
                                       class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Note
                            </label>
                            <textarea name="note" 
                                      rows="3"
                                      class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                                Place Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
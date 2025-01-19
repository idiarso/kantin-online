<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($cartItems->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        <p class="mb-4">Your cart is empty</p>
                        <a href="{{ route('customer.products.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md">
                            Browse Products
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($cartItems as $item)
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
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                Rp {{ number_format($item->product->price) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <form action="{{ route('customer.cart.update', $item) }}" 
                                                      method="POST" 
                                                      class="flex items-center space-x-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button" 
                                                            onclick="this.parentNode.querySelector('input').stepDown(); this.parentNode.submit();"
                                                            class="text-gray-500 hover:text-gray-700">
                                                        -
                                                    </button>
                                                    <input type="number" 
                                                           name="quantity" 
                                                           value="{{ $item->quantity }}" 
                                                           min="1" 
                                                           max="{{ $item->product->stock }}"
                                                           class="w-16 text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                           onchange="this.form.submit()">
                                                    <button type="button" 
                                                            onclick="this.parentNode.querySelector('input').stepUp(); this.parentNode.submit();"
                                                            class="text-gray-500 hover:text-gray-700">
                                                        +
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                Rp {{ number_format($item->product->price * $item->quantity) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <form action="{{ route('customer.cart.remove', $item) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900"
                                                            onclick="return confirm('Remove this item from cart?')">
                                                        Remove
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>
                                        <td colspan="2" class="px-6 py-4 font-bold">
                                            Rp {{ number_format($total) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Checkout Form -->
                        <form action="{{ route('customer.orders.store') }}" method="POST" class="mt-6">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="pickup_time" :value="__('Pickup Time')" />
                                    <x-text-input id="pickup_time" 
                                                 name="pickup_time" 
                                                 type="datetime-local"
                                                 class="mt-1 block w-full" 
                                                 required />
                                    <x-input-error :messages="$errors->get('pickup_time')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="notes" :value="__('Notes (Optional)')" />
                                    <textarea id="notes" 
                                              name="notes" 
                                              rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                                </div>

                                <div class="flex justify-between items-center">
                                    <a href="{{ route('customer.products.index') }}" class="text-gray-600 hover:text-gray-900">
                                        Continue Shopping
                                    </a>
                                    <x-primary-button>
                                        Place Order
                                    </x-primary-button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout> 
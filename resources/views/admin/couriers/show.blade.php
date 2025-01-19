@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Courier Details</h2>
        <div class="flex space-x-4">
            <a href="{{ route('admin.couriers.edit', $courier) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-edit mr-2"></i> Edit Courier
            </a>
            <a href="{{ route('admin.couriers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i> Back to Couriers
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="text-center">
                    <img class="h-32 w-32 rounded-full mx-auto object-cover" 
                        src="{{ $courier->photo_url }}" 
                        alt="{{ $courier->name }}">
                    <h3 class="mt-4 text-xl font-medium text-gray-900">{{ $courier->name }}</h3>
                    <div class="mt-2 flex justify-center space-x-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $courier->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $courier->status_label }}
                        </span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $courier->availability_status === 'Available' ? 'bg-blue-100 text-blue-800' : 
                               ($courier->availability_status === 'Busy' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ $courier->availability_status }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 border-t border-gray-200 pt-4">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $courier->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $courier->phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $courier->address }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Vehicle Information -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900">Vehicle Information</h3>
                <div class="mt-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Vehicle Type</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($courier->vehicle_type) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Vehicle Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $courier->vehicle_number ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">License Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $courier->license_number }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900">Statistics</h3>
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-blue-900">Active Orders</dt>
                        <dd class="mt-1 text-2xl font-semibold text-blue-900">{{ $courier->active_orders }}</dd>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-green-900">Total Orders</dt>
                        <dd class="mt-1 text-2xl font-semibold text-green-900">{{ $courier->orders_count }}</dd>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-purple-900">Total Deliveries</dt>
                        <dd class="mt-1 text-2xl font-semibold text-purple-900">{{ $courier->deliveries_count }}</dd>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-yellow-900">Completion Rate</dt>
                        <dd class="mt-1 text-2xl font-semibold text-yellow-900">
                            {{ $courier->orders_count > 0 
                                ? number_format(($courier->deliveries_count / $courier->orders_count) * 100, 1) . '%'
                                : '0%' }}
                        </dd>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="mt-6">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900">Recent Orders</h3>
                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($courier->orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        #{{ $order->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->delivery_address }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                               ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a href="{{ route('admin.orders.show', $order) }}" 
                                            class="text-blue-600 hover:text-blue-900">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No recent orders found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
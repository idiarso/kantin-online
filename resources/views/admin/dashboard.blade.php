@extends('layouts.admin')

@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Stats Cards -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                <i class="fas fa-shopping-cart text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Total Sales</p>
                <p class="text-lg font-semibold">Rp {{ number_format($stats['total_sales'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    <!-- Add more stats cards -->
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold">Recent Orders</h2>
        </div>
        <div class="p-4">
            <!-- Add recent orders table -->
        </div>
    </div>

    <!-- Popular Products -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold">Popular Products</h2>
        </div>
        <div class="p-4">
            <!-- Add popular products list -->
        </div>
    </div>
</div>
@endsection 
<!DOCTYPE html>
<html>
<head>
    <title>Orders Report</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border: 1px solid #ddd; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h1>Orders Report</h1>
    <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>

    <h2>Today's Statistics</h2>
    <table>
        <tr>
            <th>Total Orders</th>
            <th>Revenue</th>
            <th>Products Sold</th>
            <th>Pending Orders</th>
        </tr>
        <tr>
            <td>{{ $stats['orders'] }}</td>
            <td>Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</td>
            <td>{{ $stats['products_sold'] }}</td>
            <td>{{ $stats['pending_orders'] }}</td>
        </tr>
    </table>

    <h2>Orders List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Items</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->user->name }}</td>
            <td>{{ $order->items->count() }}</td>
            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            <td>{{ $order->status }}</td>
            <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html> 
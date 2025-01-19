@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Create New Deposit</h2>
        <a href="{{ route('admin.finance.deposits') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i> Back to Deposits
        </a>
    </div>
@endsection

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.finance.deposits.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- User Selection -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Select User</label>
                    <select name="user_id" id="user_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select a user</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ ucfirst($user->role) }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" name="amount" id="amount" required min="1000"
                            class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            value="{{ old('amount') }}" placeholder="0">
                    </div>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                    <select name="payment_method" id="payment_method" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="online_payment" {{ old('payment_method') == 'online_payment' ? 'selected' : '' }}>Online Payment</option>
                    </select>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reference Number -->
                <div>
                    <label for="reference_number" class="block text-sm font-medium text-gray-700">Reference Number</label>
                    <input type="text" name="reference_number" id="reference_number"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        value="{{ old('reference_number') }}" placeholder="Optional">
                    @error('reference_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Optional">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.finance.deposits') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Create Deposit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodSelect = document.getElementById('payment_method');
    const referenceNumberInput = document.getElementById('reference_number');

    paymentMethodSelect.addEventListener('change', function() {
        const method = this.value;
        if (method === 'cash') {
            referenceNumberInput.parentElement.classList.add('hidden');
            referenceNumberInput.value = '';
        } else {
            referenceNumberInput.parentElement.classList.remove('hidden');
        }
    });

    // Trigger change event on load to handle initial state
    paymentMethodSelect.dispatchEvent(new Event('change'));
});
</script>
@endpush
@endsection 
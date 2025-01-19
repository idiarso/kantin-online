@extends('layouts.admin')

@section('header')
    <h2 class="text-2xl font-semibold text-gray-800">Canteen Operating Hours</h2>
@endsection

@section('content')
<div class="container mx-auto px-6 py-8">
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <form action="{{ route('admin.canteen.hours.update') }}" method="POST" class="p-8">
            @csrf

            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Set Operating Hours</h3>
                    <p class="mt-1 text-sm text-gray-500">Configure the canteen's operating hours for each day of the week.</p>
                </div>

                <div class="space-y-4">
                    @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                            <div class="w-32">
                                <span class="text-sm font-medium text-gray-700">{{ ucfirst($day) }}</span>
                            </div>

                            <label class="inline-flex items-center">
                                <input type="checkbox" name="hours[{{ $day }}][closed]" value="1" 
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    {{ $hours[$day]['closed'] ? 'checked' : '' }}
                                    onchange="toggleTimeInputs('{{ $day }}', this.checked)">
                                <span class="ml-2 text-sm text-gray-600">Closed</span>
                            </label>

                            <div id="{{ $day }}_times" class="flex items-center space-x-4 {{ $hours[$day]['closed'] ? 'hidden' : '' }}">
                                <div>
                                    <label for="{{ $day }}_open" class="block text-sm font-medium text-gray-700">Opening Time</label>
                                    <input type="time" name="hours[{{ $day }}][open]" id="{{ $day }}_open"
                                        value="{{ $hours[$day]['open'] }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="{{ $day }}_close" class="block text-sm font-medium text-gray-700">Closing Time</label>
                                    <input type="time" name="hours[{{ $day }}][close]" id="{{ $day }}_close"
                                        value="{{ $hours[$day]['close'] }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Save Operating Hours
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleTimeInputs(day, isClosed) {
    const timesDiv = document.getElementById(day + '_times');
    if (isClosed) {
        timesDiv.classList.add('hidden');
    } else {
        timesDiv.classList.remove('hidden');
    }
}
</script>
@endpush
@endsection 
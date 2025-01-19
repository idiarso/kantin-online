@props([
    'type' => 'button',
    'variant' => 'primary'
])

@php
    $variantClasses = [
        'primary' => 'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 text-white',
        'secondary' => 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500 text-white',
        'success' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500 text-white',
        'danger' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500 text-white',
        'warning' => 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500 text-white',
    ][$variant];

    $classes = 'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 ' . $variantClasses;
@endphp

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button> 
@props(['href', 'active'])

@php
$classes = ($active ?? false)
            ? 'block px-4 py-3 text-white bg-blue-700 rounded-lg transition-colors duration-300 flex items-center'
            : 'block px-4 py-3 text-white hover:bg-blue-700 rounded-lg transition-colors duration-300 flex items-center';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

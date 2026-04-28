@props(['active'])

@php
$classes = 'nav-link' . ($active ? ' active' : '');
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

@props([
  'href' => null,
  'variant' => 'primary', // primary | secondary | secondary-white
  'type' => 'button',
  'full' => false,
])

@php
$base = 'type-input-label transition-color-figma inline-flex items-center justify-center rounded-[8px] px-8 py-4';
$width = $full ? 'w-full' : '';

$variantClasses = match($variant) {
    'secondary' => 'border border-brand-dark-green text-brand-dark-green hover:bg-[rgba(155,204,87,0.4)]',
    'secondary-white' => 'border border-brand-light-gray text-brand-light-gray hover:border-base-white hover:bg-[rgba(255,255,255,0.2)] hover:text-base-white',
    default => 'bg-brand-light-green text-brand-dark-green hover:bg-[#7db709]',
};

$classes = trim($base . ' ' . $width . ' ' . $variantClasses . ' ' . $attributes->get('class'));
@endphp

@if($href)
  <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
  </a>
@else
  <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
  </button>
@endif

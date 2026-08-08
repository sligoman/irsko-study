@props([
  'title' => null,
  'text' => null,
  'variant' => 'light',
])

<article data-component="feature-card" @class([
  'rounded-[16px] p-6 transition duration-300 hover:-translate-y-1 hover:shadow-lg',
  'bg-brand-light-gray' => $variant !== 'dark',
  'bg-brand-dark-green text-white' => $variant === 'dark',
])>
  <div @class([
    'mb-8 h-1 w-16 rounded-full',
    'bg-brand-orange' => $variant !== 'dark',
    'bg-brand-light-green' => $variant === 'dark',
  ])></div>
  <h3 @class([
    'type-display-xs',
    'text-brand-dark-green' => $variant !== 'dark',
    'text-white' => $variant === 'dark',
  ])>{{ $title }}</h3>
  <p @class([
    'type-text-md mt-3',
    'text-brand-dark-green' => $variant !== 'dark',
    'text-white/85' => $variant === 'dark',
  ])>{{ $text }}</p>
</article>

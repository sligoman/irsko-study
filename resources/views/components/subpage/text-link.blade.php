@props([
  'href' => null,
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'group type-input-label inline-flex items-center gap-2 text-brand-dark-green underline underline-offset-4 transition-color-figma hover:text-[#041b19]']) }}>
  <span>{{ $slot }}</span>
  <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true" class="h-5 w-5 shrink-0 transition-move-figma group-hover:translate-x-[2px]">
    <rect width="20" height="20" rx="10" fill="#9BCC57" />
    <path d="M8.5 7L11.5 10L8.5 13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
  </svg>
</a>

@props([
  'heading' => null,
  'items' => [],
  'button' => null,
])

<section data-component="summary-list" class="rounded-[16px] bg-brand-light-gray p-6 md:p-10 lg:p-12">
  <h2 class="type-display-lg text-brand-dark-green">{{ $heading }}</h2>
  <div class="mt-8 grid gap-4 md:grid-cols-2">
    @foreach($items as $item)
      <div class="flex gap-3 rounded-[12px] bg-white p-4">
        <span class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brand-orange text-sm text-white">✓</span>
        <p class="type-text-md text-brand-dark-green">{{ $item }}</p>
      </div>
    @endforeach
  </div>
  @if($button)
    <a href="{{ $button['href'] }}" class="type-input-label transition-color-figma mt-8 inline-flex items-center justify-center rounded-[8px] bg-brand-light-green px-8 py-4 text-brand-dark-green hover:bg-[#7db709]">{{ $button['label'] }}</a>
  @endif
</section>

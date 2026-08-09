@props([
  'eyebrow' => null,
  'title' => null,
  'accent' => null,
  'text' => null,
  'stats' => [],
])

<section data-component="subpage-hero-light" class="bg-base-white pt-12 md:pt-16">
  <div class="layout-container">
    <div class="max-w-[940px]">
      @if($eyebrow)
        <p class="type-text-md-semibold text-brand-orange">{{ $eyebrow }}</p>
      @endif
      <h1 class="type-display-xl mt-3 text-brand-dark-green">
        {{ $title }} @if($accent)<span class="text-brand-orange">{{ $accent }}</span>@endif
      </h1>
      @if($text)
        <p class="type-text-lg mt-4 max-w-[760px] text-brand-dark-green">{{ $text }}</p>
      @endif
    </div>

    @if(count($stats))
      <div class="mt-10 grid max-w-[940px] gap-4 sm:grid-cols-3">
        @foreach($stats as $stat)
          <div class="rounded-[12px] bg-brand-light-gray p-5">
            <p class="type-display-xs text-brand-dark-green">{{ $stat['value'] }}</p>
            <p class="type-text-sm mt-1 text-brand-dark-green">{{ $stat['label'] }}</p>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>

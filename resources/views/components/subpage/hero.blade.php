@props([
  'eyebrow' => null,
  'title' => null,
  'accent' => null,
  'text' => null,
  'stats' => [],
])

<section data-component="subpage-hero" class="relative overflow-hidden rounded-b-[24px] bg-brand-dark-green text-white">
  <div class="layout-container relative py-20 md:py-28">
    <div class="max-w-[940px]">
      @if($eyebrow)
        <p class="type-text-md-semibold inline-flex rounded-full bg-white/10 px-4 py-2 text-white ring-1 ring-white/15">{{ $eyebrow }}</p>
      @endif
      <h1 class="type-display-2xl mt-6 text-white">
        {{ $title }} @if($accent)<span class="text-brand-orange">{{ $accent }}</span>@endif
      </h1>
      @if($text)
        <p class="type-text-lg mt-6 max-w-[760px] text-white/85">{{ $text }}</p>
      @endif
    </div>

    @if(count($stats))
      <div class="mt-12 grid max-w-[940px] gap-4 sm:grid-cols-3">
        @foreach($stats as $stat)
          <div class="rounded-[12px] bg-white/10 p-5 ring-1 ring-white/15">
            <p class="type-display-xs text-white">{{ $stat['value'] }}</p>
            <p class="type-text-sm mt-1 text-white/80">{{ $stat['label'] }}</p>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>

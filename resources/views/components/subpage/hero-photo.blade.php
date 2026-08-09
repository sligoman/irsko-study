@props([
  'eyebrow' => null,
  'title' => null,
  'accent' => null,
  'text' => null,
  'stats' => [],
  'photo' => ['src' => null, 'sizes' => '(max-width: 768px) 100vw, 1280px', 'alt' => ''],
])

@php
  $imageExtension = strtolower((string) pathinfo($photo['src'] ?? '', PATHINFO_EXTENSION));
  $imageBase = $imageExtension !== ''
      ? substr((string) $photo['src'], 0, -(strlen($imageExtension) + 1))
      : (string) $photo['src'];
  $imageBase = preg_replace('/-(?:1|2|3|4)x$/', '', $imageBase ?? '');
  $src = asset($imageBase . '-2x.' . $imageExtension);
  $srcset = collect([1 => 640, 2 => 960, 3 => 1200, 4 => 1600])
    ->map(fn ($width, $scale) => asset($imageBase . '-' . $scale . 'x.' . $imageExtension) . ' ' . $width . 'w')
    ->implode(', ');
@endphp

<section data-component="subpage-hero-photo" class="relative overflow-hidden rounded-b-[24px] bg-brand-dark-green text-white">
  @if(!empty($photo['src']))
    <img
      src="{{ $src }}"
      srcset="{{ $srcset }}"
      sizes="{{ $photo['sizes'] }}"
      alt="{{ $photo['alt'] ?? '' }}"
      class="absolute inset-0 h-full w-full object-cover"
      loading="eager"
      decoding="async"
    >
  @endif
  <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-black/35 to-black/60"></div>

  <div class="layout-container relative pb-16 pt-[104px] md:pb-20 md:pt-28">
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

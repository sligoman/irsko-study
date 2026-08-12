@props([
  'schoolId' => null,
  'name' => null,
  'imgClass' => 'h-64 w-full object-cover md:h-80',
])

@php
  $imageMap = ['tr' => 'trinity', 'gy' => 'galway'];
  $base = $imageMap[$schoolId] ?? $schoolId;
  $src = null;

  foreach (['jpg', 'jpeg', 'png'] as $ext) {
      $candidate = public_path("img/blog/large/uni-{$base}.{$ext}");
      if (file_exists($candidate)) {
          $src = asset("img/blog/large/uni-{$base}.{$ext}");
          break;
      }
  }

  if (!$src) {
      foreach (['jpg', 'jpeg', 'png'] as $ext) {
          $candidate = public_path("img/universities/uni-{$base}.{$ext}");
          if (file_exists($candidate)) {
              $src = asset("img/universities/uni-{$base}.{$ext}");
              break;
          }
      }
  }
@endphp

@if($src)
  <img src="{{ $src }}" alt="{{ $name ?? 'Univerzita' }}" class="rounded-[16px] {{ $imgClass }}" loading="lazy" decoding="async">
@endif

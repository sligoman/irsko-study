@extends('layouts.app')

@section('title', ($post->title ?? 'Článek') . ' - Blog')
@php
  $__sitemap_desc = null;
  if (!empty($post->excerpt)) {
    $__sitemap_desc = strip_tags($post->excerpt);
  } elseif (!empty($post->content)) {
    $__sitemap_desc = \Illuminate\Support\Str::limit(strip_tags($post->content), 150);
  }

  $blogImageSet = function ($article, $preferredScale = 3) {
    $image1x = \App\Support\ResponsiveImageResolver::urlForScale($article, 'blog', 1);
    $image2x = \App\Support\ResponsiveImageResolver::urlForScale($article, 'blog', 2);
    $image3x = \App\Support\ResponsiveImageResolver::urlForScale($article, 'blog', 3);
    $image4x = \App\Support\ResponsiveImageResolver::urlForScale($article, 'blog', 4);

    $src = $preferredScale === 3
      ? ($image3x ?? $image2x ?? $image1x ?? $image4x)
      : ($image2x ?? $image3x ?? $image1x ?? $image4x);

    return [
      'src' => $src,
      'srcset' => collect([
        $image1x ? "{$image1x} 640w" : null,
        $image2x ? "{$image2x} 960w" : null,
        $image3x ? "{$image3x} 1200w" : null,
        $image4x ? "{$image4x} 1600w" : null,
      ])->filter()->implode(', '),
    ];
  };
@endphp
@section('meta_description', $__sitemap_desc ?? 'Článek na blogu IrskoStudy o studiu v Irsku')

@section('content')
  <div class="max-w-3xl mx-auto py-12 px-6">
    <a href="{{ route('blog') }}" class="text-sm text-gray-500 hover:underline">← Zpět na blog</a>

    <article class="bg-white p-6 rounded-lg shadow mt-4 prose lg:prose-xl">
      <h1 class="text-2xl font-bold text-[color:var(--color-primary)]">{{ $post->title }}</h1>
      <div class="text-sm text-gray-500 mt-2">{{ optional($post->created_at)->format('j. n. Y') }}</div>

      @if(!empty($post->featured_image))
        @php($postImage = $blogImageSet($post, 3))
        @if(!empty($postImage['src']))
          <img src="{{ $postImage['src'] }}" @if(!empty($postImage['srcset'])) srcset="{{ $postImage['srcset'] }}" @endif sizes="(max-width: 768px) 100vw, 768px" alt="{{ $post->title }}" title="{{ $post->title }}" class="mt-4 w-full h-48 sm:h-64 object-cover rounded">
        @endif
      @endif

      <div class="prose prose-sm mt-6 text-gray-800">
        {!! $post->content !!}
      </div>
    </article>
  </div>
@endsection

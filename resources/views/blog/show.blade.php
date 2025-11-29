@extends('layouts.app')

@section('title', ($post->title ?? 'Článek') . ' - Blog')
@php
  // Build a short meta description from excerpt or content
  $__sitemap_desc = null;
  if (!empty($post->excerpt)) {
    $__sitemap_desc = strip_tags($post->excerpt);
  } elseif (!empty($post->content)) {
    $__sitemap_desc = \Illuminate\Support\Str::limit(strip_tags($post->content), 150);
  }
@endphp
@section('meta_description', $__sitemap_desc ?? 'Článek na blogu IrskoStudy o studiu v Irsku')

@section('content')
  <div class="max-w-3xl mx-auto py-12 px-6">
    <a href="{{ route('blog') }}" class="text-sm text-gray-500 hover:underline">← Zpět na blog</a>

    <article class="bg-white p-6 rounded-lg shadow mt-4 prose lg:prose-xl">
      <h1 class="text-2xl font-bold text-[color:var(--color-primary)]">{{ $post->title }}</h1>
      <div class="text-sm text-gray-500 mt-2">{{ optional($post->created_at)->format('j. n. Y') }}</div>

      @if(!empty($post->featured_image))
        <picture class="block mt-4 rounded overflow-hidden">
          <source media="(max-width: 640px)" srcset="{{ asset('img/blog/medium/' . $post->featured_image) }}">
          <source media="(min-width: 641px)" srcset="{{ asset('img/blog/large/' . $post->featured_image) }}">
          <img src="{{ asset('img/blog/large/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 sm:h-64 object-cover">
        </picture>
      @endif

      <div class="prose prose-sm mt-6 text-gray-800">
        {!! $post->content !!}
      </div>
    </article>
  </div>
@endsection

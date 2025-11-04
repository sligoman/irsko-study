@extends('layouts.app')

@section('title', ($post->title ?? 'Článek') . ' - Blog')

@section('content')
  <div class="max-w-3xl mx-auto py-12 px-6">
    <a href="{{ route('blog') }}" class="text-sm text-gray-500 hover:underline">← Zpět na blog</a>

    <article class="bg-white p-6 rounded-lg shadow mt-4 prose lg:prose-xl">
      <h1 class="text-2xl font-bold text-[color:var(--color-primary)]">{{ $post->title }}</h1>
      <div class="text-sm text-gray-500 mt-2">{{ optional($post->created_at)->format('j. n. Y') }}</div>

      @if(!empty($post->featured_image))
        <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-64 object-cover rounded mt-4">
      @endif

      <div class="prose prose-sm mt-6 text-gray-800">
        {!! $post->content !!}
      </div>
    </article>
  </div>
@endsection

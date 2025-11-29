@extends('layouts.app')

@section('title', 'Sitemap - IrskoStudy')

@section('content')
  <div class="max-w-4xl mx-auto py-12 px-6">
    <h1 class="text-3xl font-bold text-[color:var(--color-primary)] mb-6">Sitemap</h1>

    <div class="bg-white p-6 rounded shadow">
      <h2 class="font-semibold">Stránky</h2>
      <ul class="mt-3 list-disc list-inside">
        <li><a href="{{ url('/') }}" class="text-[color:var(--color-emerald)]">Domů</a></li>
        <li><a href="{{ route('about') }}" class="text-[color:var(--color-emerald)]">O nás</a></li>
        <li><a href="{{ route('why') }}" class="text-[color:var(--color-emerald)]">Proč Irsko</a></li>
        <li><a href="{{ route('universities') }}" class="text-[color:var(--color-emerald)]">Vysoké školy</a></li>
        <li><a href="{{ route('services') }}" class="text-[color:var(--color-emerald)]">Služby</a></li>
        <li><a href="{{ route('faq') }}" class="text-[color:var(--color-emerald)]">FAQ</a></li>
        <li><a href="{{ route('contact') }}" class="text-[color:var(--color-emerald)]">Kontakt</a></li>
        <li><a href="{{ url('/sitemap.xml') }}" class="text-[color:var(--color-emerald)]">Sitemap (XML)</a></li>
      </ul>

      <h2 class="font-semibold mt-6">Blog</h2>
      @if($posts->count())
        <ul class="mt-3 space-y-2">
          @foreach($posts as $post)
            <li><a href="{{ route('blog.show', $post->slug) }}" class="text-[color:var(--color-emerald)]">{{ $post->title }}</a></li>
          @endforeach
        </ul>
      @else
        <div class="mt-3 text-gray-600">Žádné články k zobrazení.</div>
      @endif
    </div>
  </div>
@endsection

@extends('layouts.app')

@section('title', 'Sitemap - Irsko Study')
@section('meta_description', 'Mapa stránek IrskoStudy — seznam veřejných stránek a článků pro snadnou orientaci.')

@section('content')
  <div class="max-w-4xl mx-auto py-12 px-6">
    <h1 class="text-3xl font-bold text-[color:var(--color-brand-dark-green)] mb-6">Sitemap</h1>

    <div class="bg-white p-6 rounded shadow">
      <h2 class="font-semibold">Stránky</h2>
      <ul class="mt-3 list-disc list-inside">
        <li><a href="{{ url('/') }}" class="text-[color:var(--color-brand-light-green)]">Domů</a></li>
        <li><a href="{{ route('about') }}" class="text-[color:var(--color-brand-light-green)]">O nás</a></li>
        <li><a href="{{ route('why') }}" class="text-[color:var(--color-brand-light-green)]">Proč Irsko</a></li>
        <li><a href="{{ route('universities') }}" class="text-[color:var(--color-brand-light-green)]">Vysoké školy</a></li>
        <li><a href="{{ route('services') }}" class="text-[color:var(--color-brand-light-green)]">Služby</a></li>
        <li><a href="{{ route('faq') }}" class="text-[color:var(--color-brand-light-green)]">FAQ</a></li>
        <li><a href="{{ route('contact') }}" class="text-[color:var(--color-brand-light-green)]">Kontakt</a></li>
        <li><a href="{{ url('/sitemap.xml') }}" class="text-[color:var(--color-brand-light-green)]">Sitemap (XML)</a></li>
      </ul>

      <h2 class="font-semibold mt-6">Blog</h2>
      @if($posts->count())
        <ul class="mt-3 space-y-2">
          @foreach($posts as $post)
            <li><a href="{{ route('blog.show', $post->slug) }}" class="text-[color:var(--color-brand-light-green)]">{{ $post->title }}</a></li>
          @endforeach
        </ul>
      @else
        <div class="mt-3 text-gray-600">Žádné články k zobrazení.</div>
      @endif
    </div>
  </div>
@endsection
@extends('layouts.app')

@section('title', 'Sitemap - IrskoStudy')

@section('content')
  <div class="max-w-4xl mx-auto py-12 px-6">
    <h1 class="text-3xl font-bold text-[color:var(--color-brand-dark-green)] mb-6">Sitemap</h1>

    <div class="bg-white p-6 rounded shadow">
      <h2 class="font-semibold">Stránky</h2>
      <ul class="mt-3 list-disc list-inside">
        <li><a href="{{ url('/') }}" class="text-[color:var(--color-brand-light-green)]">Domů</a></li>
        <li><a href="{{ route('about') }}" class="text-[color:var(--color-brand-light-green)]">O nás</a></li>
        <li><a href="{{ route('why') }}" class="text-[color:var(--color-brand-light-green)]">Proč Irsko</a></li>
        <li><a href="{{ route('universities') }}" class="text-[color:var(--color-brand-light-green)]">Vysoké školy</a></li>
        <li><a href="{{ route('services') }}" class="text-[color:var(--color-brand-light-green)]">Služby</a></li>
        <li><a href="{{ route('faq') }}" class="text-[color:var(--color-brand-light-green)]">FAQ</a></li>
        <li><a href="{{ route('contact') }}" class="text-[color:var(--color-brand-light-green)]">Kontakt</a></li>
        <li><a href="{{ url('/sitemap.xml') }}" class="text-[color:var(--color-brand-light-green)]">Sitemap (XML)</a></li>
      </ul>

      <h2 class="font-semibold mt-6">Blog</h2>
      @if($posts->count())
        <ul class="mt-3 space-y-2">
          @foreach($posts as $post)
            <li><a href="{{ route('blog.show', $post->slug) }}" class="text-[color:var(--color-brand-light-green)]">{{ $post->title }}</a></li>
          @endforeach
        </ul>
      @else
        <div class="mt-3 text-gray-600">Žádné články k zobrazení.</div>
      @endif
    </div>
  </div>
@endsection

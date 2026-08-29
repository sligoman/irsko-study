@extends('layouts.app')

@section('title', 'Praktický průvodce - IrskoStudy')
@section('meta_description', 'Praktický průvodce s aktuálními články a novinky o studiu v Irsku — poradíme s přihláškami, ubytováním a adaptací.')

@php
  $studyKeywords = ['stud', 'student', 'přihl', 'šk', 'obor', 'anglič', 'ubyt', 'univerzit', 'čes', 'kolej'];
  $studyRelevance = function ($article) use ($studyKeywords) {
    $text = \Illuminate\Support\Str::lower(strip_tags(($article->title ?? '') . ' ' . ($article->excerpt ?? '')));

    return collect($studyKeywords)->sum(fn ($keyword) => str_contains($text, $keyword) ? 1 : 0);
  };

  $articles = collect($posts->items())->sortByDesc($studyRelevance)->values();
  $featured = $articles->first();
  $rest = $articles->skip(1);

  $blogImageSet = function ($article, $preferredScale = 2) {
    $image1x = \App\Support\ResponsiveImageResolver::urlForScale($article, 'blog', 1);
    $image2x = \App\Support\ResponsiveImageResolver::urlForScale($article, 'blog', 2);
    $image3x = \App\Support\ResponsiveImageResolver::urlForScale($article, 'blog', 3);
    $image4x = \App\Support\ResponsiveImageResolver::urlForScale($article, 'blog', 4);

    $src = $preferredScale === 4
      ? ($image4x ?? $image3x ?? $image2x ?? $image1x)
      : ($preferredScale === 3
        ? ($image3x ?? $image2x ?? $image1x ?? $image4x)
        : ($image2x ?? $image3x ?? $image1x ?? $image4x));

    return [
      'src' => $src,
      // Blog cards intentionally use the highest-quality variant to avoid blurry thumbnails.
      'srcset' => $image4x ? "{$image4x} 1600w" : null,
    ];
  };

  $posts->onEachSide(1);
  $guideCanonical = $posts->currentPage() > 1 ? $posts->url($posts->currentPage()) : route('blog');
  $plainExcerpt = fn ($article, $limit = 150) => \Illuminate\Support\Str::limit(strip_tags($article->excerpt ?? $article->content ?? ''), $limit);
@endphp

@section('canonical', $guideCanonical)
@section('head')
  @if($posts->currentPage() > 1)
    <link rel="prev" href="{{ $posts->previousPageUrl() }}">
  @endif
  @if($posts->hasMorePages())
    <link rel="next" href="{{ $posts->nextPageUrl() }}">
  @endif
@endsection

@section('content')
  <main data-redesign-page="blog-index" class="pb-16">
    <section data-redesign-section="blog-guide-hero" class="pb-12 pt-8 md:pt-12">
      <div class="layout-container">
        <p class="type-text-sm text-utility-text-placeholder-dark">
          <a href="{{ route('home') }}" class="hover:text-brand-dark-green">Domů</a> › Praktický průvodce
        </p>

        <div class="mt-6 rounded-[16px] bg-white p-6 md:p-10">
          <h1 class="type-display-xl text-center text-brand-dark-green">
            Praktický průvodce
            <span class="type-display-xl-decorative text-brand-orange">pro</span>
            studium v Irsku
          </h1>
          <p class="type-text-lg mt-6 max-w-3xl text-center text-utility-text-placeholder-dark">
            Praktické informace pro studenty z Česka, kteří plánují studium v Irsku: výběr školy, přihláška, angličtina, bydlení i první týdny na místě.
          </p>

          <div class="mt-10 grid gap-4 md:grid-cols-3">
            <article class="rounded-[12px] bg-brand-light-gray p-5">
              <img src="{{ asset('img/svg/suitcase-rolling.svg') }}" alt="" class="h-8 w-8" aria-hidden="true">
              <h2 class="type-display-xs mt-4 text-brand-dark-green">Výběr školy a oboru</h2>
              <p class="type-text-md mt-3 text-utility-text-placeholder-dark">Jak vybrat školu, obor a město, které odpovídají tvému cíli.</p>
            </article>
            <article class="rounded-[12px] bg-brand-light-gray p-5">
              <img src="{{ asset('img/svg/lightbulb-thin.svg') }}" alt="" class="h-8 w-8" aria-hidden="true">
              <h2 class="type-display-xs mt-4 text-brand-dark-green">Přihláška z Česka</h2>
              <p class="type-text-md mt-3 text-utility-text-placeholder-dark">Termíny, dokumenty, angličtina a kroky od prvního rozhodnutí po přijetí.</p>
            </article>
            <article class="rounded-[12px] bg-brand-light-gray p-5">
              <img src="{{ asset('img/svg/book-open-text.svg') }}" alt="" class="h-8 w-8" aria-hidden="true">
              <h2 class="type-display-xs mt-4 text-brand-dark-green">Život studenta v Irsku</h2>
              <p class="type-text-md mt-3 text-utility-text-placeholder-dark">Bydlení, doprava, finance a zkušenosti z běžného studentského života.</p>
            </article>
          </div>
        </div>
      </div>
    </section>

    @if($featured)
      <section data-redesign-section="blog-featured" class="pb-12">
        <div class="layout-container">
          <div class="grid gap-6 rounded-[16px] bg-white p-6 md:grid-cols-[1.2fr_0.8fr] md:p-8">
            <article>
              @php($featuredImage = $blogImageSet($featured, 4))
              <a href="{{ route('blog.show', $featured->slug) }}" class="block h-[320px] overflow-hidden rounded-[8px] bg-brand-light-gray">
                @if(!empty($featuredImage['src']))
                  <img src="{{ $featuredImage['src'] }}" @if(!empty($featuredImage['srcset'])) srcset="{{ $featuredImage['srcset'] }}" @endif sizes="(max-width: 768px) 100vw, 66vw" alt="{{ $featured->title }}" title="{{ $featured->title }}" class="h-full w-full object-cover" loading="eager" decoding="async">
                @else
                  <div class="flex h-[320px] items-end p-6">
                    <span class="type-display-md text-brand-dark-green">Irsko<span class="text-brand-orange">Study</span></span>
                  </div>
                @endif
              </a>
              <div class="mt-5">
                <h2 class="type-display-md text-brand-dark-green">{{ $featured->title }}</h2>
                @if(!empty($featured->excerpt))
                  <p class="type-text-md mt-3 text-utility-text-placeholder-dark">{{ $plainExcerpt($featured, 180) }}</p>
                @endif
                <x-subpage.text-link href="{{ route('blog.show', $featured->slug) }}" class="mt-8">Číst článek</x-subpage.text-link>
              </div>
            </article>

            <aside class="rounded-[12px] bg-brand-light-gray p-6">
              <h3 class="type-display-xs text-brand-dark-green">Pro budoucí studenty</h3>
              <ul class="mt-4 space-y-3">
                @foreach($articles->take(5) as $item)
                  <li>
                    <a href="{{ route('blog.show', $item->slug) }}" class="type-text-md text-brand-dark-green underline decoration-brand-light-green underline-offset-4">{{ $item->title }}</a>
                  </li>
                @endforeach
              </ul>
            </aside>
          </div>
        </div>
      </section>
    @endif

    <section data-redesign-section="blog-grid" class="pb-12">
      <div class="layout-container">
        @if($posts->count())
          <div class="flex items-end justify-between gap-4">
            <h2 class="type-display-lg text-brand-dark-green">Další články</h2>
          </div>

          <div class="mt-8 grid gap-6 md:grid-cols-3">
            @foreach($rest as $article)
              @php($articleImage = $blogImageSet($article, 4))
              <article class="flex h-full flex-col overflow-hidden rounded-[12px] bg-white">
                <a href="{{ route('blog.show', $article->slug) }}" class="block h-56 bg-brand-light-gray">
                  @if(!empty($articleImage['src']))
                    <img src="{{ $articleImage['src'] }}" @if(!empty($articleImage['srcset'])) srcset="{{ $articleImage['srcset'] }}" @endif sizes="(max-width: 768px) 100vw, 33vw" alt="{{ $article->title }}" title="{{ $article->title }}" class="h-full w-full object-cover" loading="lazy" decoding="async">
                  @else
                    <div class="flex h-48 items-end p-5">
                      <span class="type-display-xs text-brand-dark-green">Irsko<span class="text-brand-orange">Study</span></span>
                    </div>
                  @endif
                </a>
                <div class="flex flex-1 flex-col p-4">
                  <div class="flex flex-wrap gap-2">
                    @foreach(($article->categories ?? collect())->take(2) as $category)
                      <span class="type-text-sm rounded bg-brand-light-gray px-2 py-1 text-brand-dark-green">{{ $category->name }}</span>
                    @endforeach
                  </div>
                  <h3 class="type-display-sm mt-3 text-brand-dark-green">{{ $article->title }}</h3>
                  <p class="type-text-sm mt-2 text-utility-text-placeholder-dark">{{ $plainExcerpt($article, 120) }}</p>
                  <x-subpage.text-link href="{{ route('blog.show', $article->slug) }}" class="mt-auto pt-6">Přečíst</x-subpage.text-link>
                </div>
              </article>
            @endforeach
          </div>

          @if($posts->hasPages())
            <div class="mt-10">
              {{ $posts->links('vendor.pagination.guide') }}
            </div>
          @endif
        @else
          <div class="rounded-[16px] bg-white p-8 text-center">
            <h2 class="type-display-sm text-brand-dark-green">Zatím nejsou žádné příspěvky.</h2>
            <p class="type-text-md mt-3 text-utility-text-placeholder-dark">Brzy tady najdeš nové články o studiu a životě v Irsku.</p>
          </div>
        @endif
      </div>
    </section>
  </main>
@endsection

@extends('layouts.app')

@section('title', 'Blog - IrskoStudy')
@section('meta_description', 'Aktuální články a novinky o studiu v Irsku — poradíme s přihláškami, ubytováním a adaptací.')

@php
  $articles = collect($posts->items());
  $featured = $articles->first();
  $rest = $articles->skip(1);

  $blogImageSet = function ($article, $preferredScale = 2) {
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

  $plainExcerpt = fn ($article, $limit = 150) => \Illuminate\Support\Str::limit(strip_tags($article->excerpt ?? $article->content ?? ''), $limit);
@endphp

@section('content')
  <main data-redesign-page="blog-index" class="pb-16">
    <section data-redesign-section="blog-guide-hero" class="pb-12 pt-8 md:pt-12">
      <div class="layout-container">
        <div class="rounded-[16px] bg-white p-6 md:p-10">
          <p class="type-text-sm-semibold text-brand-orange">Blog</p>
          <h1 class="type-display-xl mt-3 text-center text-brand-dark-green md:text-left">
            Praktický průvodce
            <span class="type-display-xl-decorative text-brand-orange">pro</span>
            studium v Irsku
          </h1>
          <p class="type-text-lg mt-5 max-w-3xl text-utility-text-placeholder-dark">
            Přehledné články k výběru školy, přihláškám, ubytování i prvním dnům v Irsku. Prakticky, lidsky a bez zbytečné omáčky.
          </p>

          <div class="mt-10 grid gap-4 md:grid-cols-3">
            <article class="rounded-[12px] bg-brand-light-gray p-5">
              <div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-orange/15 text-brand-orange">01</div>
              <h2 class="type-display-xs mt-4 text-brand-dark-green">Před přihláškou</h2>
              <p class="type-text-md mt-3 text-utility-text-placeholder-dark">Jak vybrat obor, školu a termíny, které dávají smysl.</p>
            </article>
            <article class="rounded-[12px] bg-brand-light-gray p-5">
              <div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-orange/15 text-brand-orange">02</div>
              <h2 class="type-display-xs mt-4 text-brand-dark-green">Život v Irsku</h2>
              <p class="type-text-md mt-3 text-utility-text-placeholder-dark">Ubytování, doprava, finance a praktické kroky před odjezdem.</p>
            </article>
            <article class="rounded-[12px] bg-brand-light-gray p-5">
              <div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-orange/15 text-brand-orange">03</div>
              <h2 class="type-display-xs mt-4 text-brand-dark-green">Zkušenosti studentů</h2>
              <p class="type-text-md mt-3 text-utility-text-placeholder-dark">Co řeší studenti přímo na místě a co by udělali jinak.</p>
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
              @php($featuredImage = $blogImageSet($featured, 3))
              <a href="{{ route('blog.show', $featured->slug) }}" class="block overflow-hidden rounded-[8px] bg-base-white ring-1 ring-neutral-200">
                @if(!empty($featuredImage['src']))
                  <img src="{{ $featuredImage['src'] }}" @if(!empty($featuredImage['srcset'])) srcset="{{ $featuredImage['srcset'] }}" @endif sizes="(max-width: 768px) 100vw, 66vw" alt="{{ $featured->title }}" title="{{ $featured->title }}" class="h-[320px] w-full object-cover">
                @else
                  <div class="flex h-[320px] items-end bg-gradient-to-br from-brand-light-gray via-white to-brand-orange/20 p-6">
                    <span class="type-display-md text-brand-dark-green">Irsko<span class="text-brand-orange">Study</span></span>
                  </div>
                @endif
              </a>
              <div class="mt-5">
                <div class="flex flex-wrap items-center gap-2">
                  @foreach(($featured->categories ?? collect())->take(2) as $category)
                    <span class="type-text-sm rounded-full bg-neutral-200 px-3 py-1 text-brand-dark-green">{{ $category->name }}</span>
                  @endforeach
                  <span class="type-text-sm text-utility-text-placeholder-dark">{{ optional($featured->created_at)->format('j. n. Y') }}</span>
                </div>
                <h2 class="type-display-md mt-4 text-brand-dark-green">{{ $featured->title }}</h2>
                @if(!empty($featured->excerpt))
                  <p class="type-text-md mt-3 text-utility-text-placeholder-dark">{{ $plainExcerpt($featured, 180) }}</p>
                @endif
                <a href="{{ route('blog.show', $featured->slug) }}" class="type-text-md mt-4 inline-flex text-brand-dark-green underline decoration-brand-light-green underline-offset-4">Číst článek</a>
              </div>
            </article>

            <aside class="rounded-[12px] bg-white p-6 ring-1 ring-neutral-200">
              <h3 class="type-display-xs text-brand-dark-green">Nejnovější témata</h3>
              <ul class="mt-5 space-y-4">
                @foreach($articles->take(5) as $item)
                  <li class="border-b border-neutral-400/50 pb-4 last:border-b-0 last:pb-0">
                    <a href="{{ route('blog.show', $item->slug) }}" class="type-text-md text-brand-dark-green underline decoration-brand-light-green underline-offset-4">{{ $item->title }}</a>
                    <p class="type-text-sm mt-1 text-utility-text-placeholder-dark">{{ optional($item->created_at)->format('j. n. Y') }}</p>
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
              @php($articleImage = $blogImageSet($article, 3))
              <article class="overflow-hidden rounded-[12px] bg-white ring-1 ring-neutral-200 transition hover:-translate-y-1 hover:ring-brand-orange/30">
                <a href="{{ route('blog.show', $article->slug) }}" class="block bg-base-white">
                  @if(!empty($articleImage['src']))
                    <img src="{{ $articleImage['src'] }}" @if(!empty($articleImage['srcset'])) srcset="{{ $articleImage['srcset'] }}" @endif sizes="(max-width: 768px) 100vw, 33vw" alt="{{ $article->title }}" title="{{ $article->title }}" class="h-56 w-full object-cover">
                  @else
                    <div class="flex h-56 items-end bg-gradient-to-br from-brand-light-gray via-white to-brand-orange/20 p-5">
                      <span class="type-display-xs text-brand-dark-green">Irsko<span class="text-brand-orange">Study</span></span>
                    </div>
                  @endif
                </a>
                <div class="p-5">
                  <div class="flex flex-wrap gap-2">
                    @foreach(($article->categories ?? collect())->take(2) as $category)
                      <span class="type-text-sm rounded-full bg-neutral-200 px-3 py-1 text-brand-dark-green">{{ $category->name }}</span>
                    @endforeach
                  </div>
                  <h3 class="type-display-xs mt-4 text-brand-dark-green">{{ $article->title }}</h3>
                  <p class="type-text-sm mt-2 text-utility-text-placeholder-dark">{{ $plainExcerpt($article, 120) }}</p>
                  <a href="{{ route('blog.show', $article->slug) }}" class="type-text-sm mt-4 inline-flex text-brand-dark-green underline decoration-brand-light-green underline-offset-4">Přečíst</a>
                </div>
              </article>
            @endforeach
          </div>

          @if($posts->hasPages())
            <div class="mt-10 rounded-[12px] bg-white p-4">
              {{ $posts->links() }}
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

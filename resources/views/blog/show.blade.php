@extends('layouts.app')

@section('title', ($post->title ?? 'Článek') . ' - Praktický průvodce')
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
@endphp
@section('meta_description', $__sitemap_desc ?? 'Článek na blogu IrskoStudy o studiu v Irsku')

@section('content')
  <main data-redesign-page="blog-post" class="pb-16">
    <section data-redesign-section="blog-post-hero" class="pb-12 pt-8 md:pt-12">
      <div class="layout-container">
        <a href="{{ route('blog') }}" class="group type-input-label inline-flex items-center gap-2 text-brand-dark-green underline underline-offset-4 transition-color-figma hover:text-[#041b19]">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true" class="h-5 w-5 shrink-0 transition-move-figma group-hover:-translate-x-[2px]">
            <rect width="20" height="20" rx="10" fill="#9BCC57" />
            <path d="M11.5 7L8.5 10L11.5 13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span>Zpět na praktického průvodce</span>
        </a>

        <div class="mt-5 grid gap-6 rounded-[16px] bg-white p-6 md:grid-cols-[1fr_1fr] md:p-8">
          <div class="h-[320px] overflow-hidden rounded-[8px] bg-base-white ring-1 ring-neutral-200 md:h-[420px]">
            @php($postImage = $blogImageSet($post, 4))
            @if(!empty($postImage['src']))
              <img src="{{ $postImage['src'] }}" @if(!empty($postImage['srcset'])) srcset="{{ $postImage['srcset'] }}" @endif sizes="(max-width: 768px) 100vw, 50vw" alt="{{ $post->title }}" title="{{ $post->title }}" class="h-full w-full object-cover" loading="eager" decoding="async">
            @else
              <div class="flex h-[320px] items-end bg-brand-light-gray p-6 md:h-[420px]">
                <span class="type-display-md text-brand-dark-green">Irsko<span class="text-brand-orange">Study</span></span>
              </div>
            @endif
          </div>

          <div class="flex flex-col justify-center">
            <div class="mb-4 flex flex-wrap gap-2">
              @foreach(($post->categories ?? collect())->take(2) as $category)
                <span class="type-text-sm rounded-full bg-neutral-200 px-3 py-1 text-brand-dark-green">{{ $category->name }}</span>
              @endforeach
              <span class="type-text-sm rounded-full bg-brand-orange/15 px-3 py-1 text-brand-dark-green">{{ optional($post->created_at)->format('j. n. Y') }}</span>
            </div>

            <h1 class="type-display-lg text-brand-dark-green">{{ $post->title }}</h1>
            @if(!empty($post->excerpt))
              <p class="type-text-md mt-4 text-utility-text-placeholder-dark">{{ $post->excerpt }}</p>
            @endif
          </div>
        </div>
      </div>
    </section>

    <section data-redesign-section="blog-post-content" class="pb-12">
      <div class="layout-container">
        <article class="mx-auto max-w-[760px] rounded-[12px] bg-white p-6 md:p-8">
          <div class="blog-post type-text-md text-brand-dark-green">
            {!! $post->content !!}
          </div>
        </article>
      </div>
    </section>

    @if(isset($morePosts) && $morePosts->count() > 0)
      <section data-redesign-section="blog-post-more" class="pb-12">
        <div class="layout-container">
          <div class="flex flex-col justify-between gap-4 md:flex-row md:items-end">
            <h2 class="type-display-lg text-brand-dark-green">Další články</h2>
            <x-button href="{{ route('blog') }}" variant="secondary" class="w-full md:w-auto">Všechny články z průvodce</x-button>
          </div>

          <div class="mt-8 grid gap-6 md:grid-cols-3">
            @foreach($morePosts as $item)
              @php($morePostImage = $blogImageSet($item, 4))
              <article class="flex h-full flex-col overflow-hidden rounded-[12px] bg-white ring-1 ring-neutral-200 transition hover:-translate-y-1 hover:ring-brand-orange/30">
                <a href="{{ route('blog.show', $item->slug) }}" class="block h-56 bg-base-white">
                  @if(!empty($morePostImage['src']))
                    <img src="{{ $morePostImage['src'] }}" @if(!empty($morePostImage['srcset'])) srcset="{{ $morePostImage['srcset'] }}" @endif sizes="(max-width: 768px) 100vw, 33vw" alt="{{ $item->title }}" title="{{ $item->title }}" class="h-full w-full object-cover" loading="lazy" decoding="async">
                  @else
                    <div class="flex h-56 items-end bg-brand-light-gray p-5">
                      <span class="type-display-xs text-brand-dark-green">Irsko<span class="text-brand-orange">Study</span></span>
                    </div>
                  @endif
                </a>
                <div class="flex flex-1 flex-col p-5">
                  <div class="mb-3 flex flex-wrap items-center gap-2">
                    @foreach(($item->categories ?? collect())->take(2) as $category)
                      <span class="type-text-sm rounded-full bg-neutral-200 px-3 py-1 text-brand-dark-green">{{ $category->name }}</span>
                    @endforeach
                    <span class="type-text-sm text-utility-text-placeholder-dark">{{ optional($item->created_at)->format('j. n. Y') }}</span>
                  </div>
                  <h3 class="type-display-sm text-brand-dark-green">{{ $item->title }}</h3>
                  @if(!empty($item->excerpt))
                    <p class="type-text-sm mt-2 text-utility-text-placeholder-dark">{{ \Illuminate\Support\Str::limit(strip_tags($item->excerpt), 120) }}</p>
                  @endif
                  <x-subpage.text-link href="{{ route('blog.show', $item->slug) }}" class="mt-auto pt-6">Přečíst</x-subpage.text-link>
                </div>
              </article>
            @endforeach
          </div>
        </div>
      </section>
    @endif

    <section data-redesign-section="blog-post-guide" class="pb-12">
      <div class="layout-container rounded-[16px] bg-white p-6 md:p-8">
        <div class="flex flex-col justify-between gap-4 md:flex-row md:items-end">
          <div>
            <p class="type-text-sm-semibold text-brand-orange">Další čtení</p>
            <h2 class="type-display-lg mt-2 text-brand-dark-green">Praktický průvodce pro studenty z Česka</h2>
          </div>
          <x-button href="{{ route('blog') }}" variant="secondary" class="w-full md:w-auto">Všechny články z průvodce</x-button>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-3">
          <article class="rounded-[12px] bg-brand-light-gray p-5">
            <img src="{{ asset('img/svg/suitcase-rolling.svg') }}" alt="" class="h-8 w-8" aria-hidden="true">
            <h3 class="type-display-xs mt-4 text-brand-dark-green">Přihláška z Česka</h3>
            <p class="type-text-sm mt-3 text-utility-text-placeholder-dark">Dokumenty, termíny a praktické kroky před odjezdem.</p>
          </article>
          <article class="rounded-[12px] bg-brand-light-gray p-5">
            <img src="{{ asset('img/svg/book-open-text.svg') }}" alt="" class="h-8 w-8" aria-hidden="true">
            <h3 class="type-display-xs mt-4 text-brand-dark-green">Studium a školy</h3>
            <p class="type-text-sm mt-3 text-utility-text-placeholder-dark">Jak fungují přihlášky, obory a studijní podmínky.</p>
          </article>
          <article class="rounded-[12px] bg-brand-light-gray p-5">
            <img src="{{ asset('img/svg/lightbulb-thin.svg') }}" alt="" class="h-8 w-8" aria-hidden="true">
            <h3 class="type-display-xs mt-4 text-brand-dark-green">Život studenta v Irsku</h3>
            <p class="type-text-sm mt-3 text-utility-text-placeholder-dark">Ubytování, brigády, doprava a první týdny v Irsku.</p>
          </article>
        </div>
      </div>
    </section>
  </main>
@endsection

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
  <main data-redesign-page="blog-post" class="pb-16">
    <section data-redesign-section="blog-post-hero" class="pb-12 pt-8 md:pt-12">
      <div class="layout-container">
        <a href="{{ route('blog') }}" class="type-text-sm inline-flex text-brand-dark-green underline decoration-brand-light-green underline-offset-4">Zpět na blog</a>

        <div class="mt-5 grid gap-6 rounded-[16px] bg-white p-6 md:grid-cols-[1fr_1fr] md:p-8">
          <div class="overflow-hidden rounded-[8px] bg-base-white ring-1 ring-neutral-200">
            @php($postImage = $blogImageSet($post, 3))
            @if(!empty($postImage['src']))
              <img src="{{ $postImage['src'] }}" @if(!empty($postImage['srcset'])) srcset="{{ $postImage['srcset'] }}" @endif sizes="(max-width: 768px) 100vw, 50vw" alt="{{ $post->title }}" title="{{ $post->title }}" class="h-[320px] w-full object-cover md:h-[420px]">
            @else
              <div class="flex h-[320px] items-end bg-gradient-to-br from-brand-light-gray via-white to-brand-orange/20 p-6 md:h-[420px]">
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
            <x-button href="{{ route('blog') }}" variant="secondary" class="w-full md:w-auto">Všechny články</x-button>
          </div>

          <div class="mt-8 grid gap-6 md:grid-cols-3">
            @foreach($morePosts as $item)
              @php($morePostImage = $blogImageSet($item, 3))
              <article class="overflow-hidden rounded-[12px] bg-white ring-1 ring-neutral-200 transition hover:-translate-y-1 hover:ring-brand-orange/30">
                <a href="{{ route('blog.show', $item->slug) }}" class="block bg-base-white">
                  @if(!empty($morePostImage['src']))
                    <img src="{{ $morePostImage['src'] }}" @if(!empty($morePostImage['srcset'])) srcset="{{ $morePostImage['srcset'] }}" @endif sizes="(max-width: 768px) 100vw, 33vw" alt="{{ $item->title }}" title="{{ $item->title }}" class="h-56 w-full object-cover">
                  @else
                    <div class="flex h-56 items-end bg-gradient-to-br from-brand-light-gray via-white to-brand-orange/20 p-5">
                      <span class="type-display-xs text-brand-dark-green">Irsko<span class="text-brand-orange">Study</span></span>
                    </div>
                  @endif
                </a>
                <div class="p-5">
                  <div class="mb-3 flex flex-wrap items-center gap-2">
                    @foreach(($item->categories ?? collect())->take(2) as $category)
                      <span class="type-text-sm rounded-full bg-neutral-200 px-3 py-1 text-brand-dark-green">{{ $category->name }}</span>
                    @endforeach
                    <span class="type-text-sm text-utility-text-placeholder-dark">{{ optional($item->created_at)->format('j. n. Y') }}</span>
                  </div>
                  <h3 class="type-display-xs text-brand-dark-green">{{ $item->title }}</h3>
                  @if(!empty($item->excerpt))
                    <p class="type-text-sm mt-2 text-utility-text-placeholder-dark">{{ \Illuminate\Support\Str::limit(strip_tags($item->excerpt), 120) }}</p>
                  @endif
                  <a href="{{ route('blog.show', $item->slug) }}" class="type-text-sm mt-4 inline-flex text-brand-dark-green underline decoration-brand-light-green underline-offset-4">Přečíst</a>
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
            <h2 class="type-display-lg mt-2 text-brand-dark-green">Praktický průvodce ke studiu v Irsku</h2>
          </div>
          <x-button href="{{ route('blog') }}" variant="secondary" class="w-full md:w-auto">Všechny články</x-button>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-3">
          <article class="rounded-[12px] bg-brand-light-gray p-5">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-orange/15 text-brand-orange">01</div>
            <h3 class="type-display-xs mt-4 text-brand-dark-green">Co řešit před cestou</h3>
            <p class="type-text-sm mt-3 text-utility-text-placeholder-dark">Dokumenty, termíny a praktické kroky před odjezdem.</p>
          </article>
          <article class="rounded-[12px] bg-brand-light-gray p-5">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-orange/15 text-brand-orange">02</div>
            <h3 class="type-display-xs mt-4 text-brand-dark-green">Studium a školy</h3>
            <p class="type-text-sm mt-3 text-utility-text-placeholder-dark">Jak fungují přihlášky, obory a studijní podmínky.</p>
          </article>
          <article class="rounded-[12px] bg-brand-light-gray p-5">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-orange/15 text-brand-orange">03</div>
            <h3 class="type-display-xs mt-4 text-brand-dark-green">Život na místě</h3>
            <p class="type-text-sm mt-3 text-utility-text-placeholder-dark">Ubytování, brigády, doprava a první týdny v Irsku.</p>
          </article>
        </div>
      </div>
    </section>
  </main>
@endsection

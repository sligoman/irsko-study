@extends('layouts.app')

@section('title', 'Blog - IrskoStudy')
@section('meta_description', 'Aktuální články a novinky o studiu v Irsku — poradíme s přihláškami, ubytováním a adaptací.')

@php
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
@endphp

@section('content')
  <div class="max-w-4xl mx-auto py-12 px-6">
    <h1 class="text-3xl font-bold text-[color:var(--color-primary)] mb-6">Novinky a články</h1>

    @if($posts->count())
      <div class="space-y-6">
        @foreach($posts as $post)
          <article class="bg-white p-4 rounded-lg shadow">
            <div class="flex flex-col sm:flex-row items-start">
              @if(!empty($post->featured_image))
                @php($postImage = $blogImageSet($post, 2))
                @if(!empty($postImage['src']))
                  <div class="w-full sm:w-32 flex-shrink-0 mb-3 sm:mb-0 sm:mr-4">
                    <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden rounded">
                      <img src="{{ $postImage['src'] }}" @if(!empty($postImage['srcset'])) srcset="{{ $postImage['srcset'] }}" @endif sizes="(max-width: 640px) 100vw, 128px" alt="{{ $post->title }}" title="{{ $post->title }}" class="w-full h-20 sm:h-24 object-cover rounded" />
                    </a>
                  </div>
                @endif
              @endif

              <div class="flex-1">
                <h2 class="text-xl font-semibold text-[color:var(--color-primary)]">
                  <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                </h2>

                @if(!empty($post->excerpt))
                  <p class="text-gray-700 mt-2">{{ $post->excerpt }}</p>
                @endif

                <div class="mt-3 flex items-center justify-between text-sm text-gray-500">
                  <div>{{ optional($post->created_at)->format('j. n. Y') }}</div>
                  <a href="{{ route('blog.show', $post->slug) }}" class="text-[color:var(--color-emerald)]">Číst více →</a>
                </div>
              </div>
            </div>
          </article>
        @endforeach
      </div>

      <div class="mt-8">
        {{ $posts->links() }}
      </div>
    @else
      <div class="bg-white p-6 rounded shadow text-center text-gray-700">Zatím nejsou žádné příspěvky.</div>
    @endif
  </div>
@endsection

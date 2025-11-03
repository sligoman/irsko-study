@extends('layouts.app')

@section('title', 'Blog - IrskoStudy')

@section('content')
  <div class="max-w-4xl mx-auto py-12 px-6">
    <h1 class="text-3xl font-bold text-[color:var(--color-primary)] mb-6">Novinky a články</h1>

    @if($posts->count())
      <div class="space-y-6">
        @foreach($posts as $post)
          <article class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-semibold text-[color:var(--color-primary)]">{{ $post->title }}</h2>
            @if(!empty($post->excerpt))
              <p class="text-gray-700 mt-2">{{ $post->excerpt }}</p>
            @endif
            <div class="mt-3 flex items-center justify-between text-sm text-gray-500">
              <div>{{ optional($post->created_at)->format('j. n. Y') }}</div>
              <a href="{{ route('blog.show', $post->slug) }}" class="text-[color:var(--color-emerald)]">Číst více →</a>
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

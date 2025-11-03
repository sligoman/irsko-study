<section class="py-12">
  <div class="max-w-6xl mx-auto px-4">
    <div class="flex items-center justify-between mb-6">
      <h3 class="text-2xl font-semibold text-[color:var(--color-primary)]">Novinky & termíny</h3>
      <a href="{{ route('blog') }}" class="text-sm text-[color:var(--color-emerald)] hover:underline">Zobrazit všechny články →</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @php
        $postModel = '\\Sligoman\\AiblogApiWeb\\Models\\AiblogPost';
        $posts = class_exists($postModel) ? $postModel::orderBy('scheduled_at', 'desc')->limit(3)->get() : collect();
      @endphp

      @if($posts->count())
        @foreach($posts as $post)
          <article class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
            <div class="text-sm text-gray-500">{{ optional($post->scheduled_at ?: $post->created_at)->format('j. n. Y') }}</div>
            <h4 class="font-semibold mt-2">{{ $post->title }}</h4>
            <p class="text-sm text-gray-600 mt-2">{{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content ?? ''), 120) }}</p>
            <a href="{{ route('blog.show', $post->slug) }}" class="mt-4 inline-block text-[color:var(--color-primary)] font-medium">Číst článek →</a>
          </article>
        @endforeach
      @else
        <article class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
          <div class="text-sm text-gray-500">12. září 2025</div>
          <h4 class="font-semibold mt-2">Jak podat přihlášku</h4>
          <p class="text-sm text-gray-600 mt-2">Krátký průvodce krok za krokem — co připravit a jaký je časový plán.</p>
          <a href="{{ route('blog') }}" class="mt-4 inline-block text-[color:var(--color-primary)] font-medium">Číst článek →</a>
        </article>

        <article class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
          <div class="text-sm text-gray-500">1. srpen 2025</div>
          <h4 class="font-semibold mt-2">Stipendia pro zahraniční studenty</h4>
          <p class="text-sm text-gray-600 mt-2">Přehled dostupných možností a jak se o stipendium ucházet.</p>
          <a href="{{ route('blog') }}" class="mt-4 inline-block text-[color:var(--color-primary)] font-medium">Číst článek →</a>
        </article>

        <article class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
          <div class="text-sm text-gray-500">20. červen 2025</div>
          <h4 class="font-semibold mt-2">Termíny přijímaček</h4>
          <p class="text-sm text-gray-600 mt-2">Aktuální přehled důležitých termínů pro přihlášky a přijetí.</p>
          <a href="{{ route('blog') }}" class="mt-4 inline-block text-[color:var(--color-primary)] font-medium">Číst článek →</a>
        </article>
      @endif
    </div>
  </div>
</section>


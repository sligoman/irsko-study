<section data-redesign-section="news-grid" class="home-section pb-20">
  <div class="layout-container">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
      <div>
        <p class="type-text-md-semibold text-brand-orange">Novinky a termíny</p>
        <h2 class="type-display-lg mt-2 text-brand-dark-green">Aktuálně ke studiu v Irsku</h2>
      </div>
      <a href="{{ route('blog') }}" class="type-input-label text-brand-dark-green hover:text-brand-orange">Zobrazit všechny články →</a>
    </div>

    <div class="mt-10 grid gap-5 md:grid-cols-3">
      @php
        $postModel = '\\Sligoman\\AiblogApiWeb\\Models\\AiblogPost';
        $posts = class_exists($postModel) && \Illuminate\Support\Facades\Schema::hasTable('aiblog_posts')
          ? $postModel::orderBy('updated_at', 'desc')->limit(3)->get()
          : collect();
      @endphp

      @if($posts->count())
        @foreach($posts as $post)
          <article class="rounded-[16px] bg-brand-light-gray p-6 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="type-text-sm text-brand-orange">{{ optional($post->updated_at ?: $post->created_at)->format('j. n. Y') }}</div>
            <h3 class="type-display-xs mt-4 text-brand-dark-green">{{ $post->title }}</h3>
            <p class="type-text-md mt-3 text-brand-dark-green">{{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content ?? ''), 120) }}</p>
            <a href="{{ route('blog.show', $post->slug) }}" class="type-input-label mt-6 inline-block text-brand-dark-green hover:text-brand-orange">Číst článek →</a>
          </article>
        @endforeach
      @else
        @foreach([
          ['date' => '12. září 2025', 'title' => 'Jak podat přihlášku', 'text' => 'Krátký průvodce krok za krokem: co připravit a jaký je časový plán.'],
          ['date' => '1. srpen 2025', 'title' => 'Stipendia pro zahraniční studenty', 'text' => 'Přehled dostupných možností a jak se o stipendium ucházet.'],
          ['date' => '20. červen 2025', 'title' => 'Termíny přijímaček', 'text' => 'Aktuální přehled důležitých termínů pro přihlášky a přijetí.'],
        ] as $post)
          <article class="rounded-[16px] bg-brand-light-gray p-6 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="type-text-sm text-brand-orange">{{ $post['date'] }}</div>
            <h3 class="type-display-xs mt-4 text-brand-dark-green">{{ $post['title'] }}</h3>
            <p class="type-text-md mt-3 text-brand-dark-green">{{ $post['text'] }}</p>
            <a href="{{ route('blog') }}" class="type-input-label mt-6 inline-block text-brand-dark-green hover:text-brand-orange">Číst článek →</a>
          </article>
        @endforeach
      @endif
    </div>
  </div>
</section>

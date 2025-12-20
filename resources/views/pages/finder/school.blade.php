@extends('layouts.app')

@section('title', ($school->name ?? 'Univerzita') . ' — Irsko Study')
@section('meta_description', Str::limit(strip_tags($school->description_cs ?? $school->description_en ?? ''), 160))

@section('content')
  <div class="max-w-4xl mx-auto px-4 py-12">
    @php
      $heroSrc = null;
      if (!empty($school->school_id)) {
          $candidate = public_path('img/blog/large/uni-' . $school->school_id . '.jpg');
          if (file_exists($candidate)) {
              $heroSrc = asset('img/blog/large/uni-' . $school->school_id . '.jpg');
          }
      }
    @endphp

    @if($heroSrc)
      <div class="mb-6">
        <img src="{{ $heroSrc }}" alt="{{ $school->name }}" class="w-full h-64 md:h-96 object-cover rounded-lg shadow" loading="lazy">
      </div>
    @endif

    <article class="bg-white rounded-lg shadow p-6">
      <header class="mb-6">
        <h1 class="text-2xl font-bold mb-2">{{ $school->name }} @if($school->acronym) ({{ $school->acronym }}) @endif</h1>

        <div class="flex items-center justify-between text-sm text-gray-600">
          <div>
            <span class="font-medium">Počet programů:</span> {{ $school->courses ? $school->courses->count() : 0 }}
          </div>

          @if(!empty($school->link))
            <div>
              <a href="{{ $school->link }}" target="_blank" rel="noopener" class="text-emerald-600 hover:underline">Oficiální web školy</a>
            </div>
          @endif
        </div>
      </header>

      <section class="prose prose-sm max-w-none mb-6 text-gray-800">
        @if(!empty($school->description_cs))
          {!! $school->description_cs !!}
        @elseif(!empty($school->description_en))
          {!! $school->description_en !!}
        @else
          <p>Informace o škole nejsou dostupné.</p>
        @endif
      </section>

      {{-- Campus locations / maps --}}
      @if(!empty($school->locations) && $school->locations->count())
        <section class="mt-6">
          <h2 class="text-xl font-semibold mb-4">Kampusy</h2>
          <div class="space-y-8">
            @foreach($school->locations as $loc)
              <div class="bg-white p-4 rounded shadow">
                <h3 class="font-medium mb-2">{{ $loc->name }}</h3>

                @php
                  $embedHtml = null;
                  // Prefer `embed` column (may contain a full iframe src URL or HTML).
                  if (!empty($loc->embed)) {
                    $embedHtml = trim($loc->embed);
                  } elseif (!empty($loc->map)) {
                    $embedHtml = trim($loc->map);
                  }
                @endphp

                @if($embedHtml)
                  @if(str_contains($embedHtml, '<iframe') || str_contains($embedHtml, '<iframe'))
                    {{-- If the field already contains iframe HTML, render it safely --}}
                    <div class="aspect-w-16 aspect-h-9 overflow-hidden rounded">
                      {!! $embedHtml !!}
                    </div>
                  @else
                    {{-- Otherwise assume it's a Google Maps embed URL and use it as iframe src --}}
                    <div class="aspect-w-16 aspect-h-9 overflow-hidden rounded">
                      <iframe
                        src="{{ $embedHtml }}"
                        width="600"
                        height="450"
                        style="border:0;"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                      ></iframe>
                    </div>
                  @endif

                  <div class="mt-2 text-sm text-gray-600">
                    <a href="{{ $loc->map ?? $loc->embed }}" target="_blank" rel="noopener" class="text-emerald-600 hover:underline">Otevřít v Google Maps</a>
                  </div>
                @else
                  <div class="text-sm text-gray-600">K dispozici není mapa pro tuto pobočku.</div>
                @endif
              </div>
            @endforeach
          </div>
        </section>
      @endif

      <footer class="mt-6">
        <a href="{{ route('finder.courses', ['school' => $school->school_id ?? $school->id]) }}" class="inline-block px-4 py-2 bg-emerald-600 text-white rounded">Zobrazit všechny kurzy</a>
      </footer>
    </article>

    <div class="mt-8">
      <a href="{{ route('universities') }}" class="text-emerald-600 hover:underline">« Zpět na přehled škol</a>
    </div>

    @if(isset($school->courses) && $school->courses->count())
      <section class="mt-10">
        <h2 class="text-2xl font-bold mb-4">Programy na {{ $school->name }}</h2>
        <p class="text-gray-700 mb-6">Níže najdete vybrané programy spadající pod tuto školu.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          @foreach($school->courses as $c)
            @php
              $courseUrl = $c->url ?? null;
              if (empty($courseUrl)) {
                  if (!empty($c->school) && !empty($c->school->school_id) && !empty($c->code)) {
                      $courseUrl = strtolower($c->school->school_id . '-' . $c->code);
                  } else {
                      $courseUrl = $c->code ?? $c->id;
                  }
              }
            @endphp

            <article class="bg-white rounded-lg p-4 border shadow-sm">
              <h3 class="text-lg font-semibold mb-1">{{ $c->title_cs ?? $c->title_en }}</h3>
              <div class="text-sm text-gray-600 mb-2">{{ $c->level?->name ?? '' }}</div>
              <p class="text-gray-700 text-sm mb-3">{{ Str::limit(strip_tags($c->description_cs ?? $c->description_en), 140) }}</p>
              <div class="flex items-center justify-between">
                <a href="{{ route('finder.course.show', $courseUrl) }}" class="text-emerald-600 hover:underline">Zobrazit detail</a>
                <div class="text-sm text-gray-600">Kód: {{ $c->code }}</div>
              </div>
            </article>
          @endforeach
        </div>
      </section>
    @endif

  </div>

  @include('components.cta')
@endsection

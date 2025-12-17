@extends('layouts.app')

@section('title', ($course->title_cs ?? $course->title_en) . ' — Irsko Study')
@section('meta_description', Str::limit(strip_tags($course->description_cs ?? $course->description_en), 160))

@section('content')
  <div class="max-w-4xl mx-auto px-4 py-12">
    {{-- Hero image: university / school visual --}}
    @php
      // $schoolLogo = $course->school->logo ?? null;
      // If logo looks like an absolute URL or starts with a slash, use it directly; otherwise look in public img folder
      $heroSrc = null;
            
      $heroSrc = asset('img/blog/large/uni-'.$course->school->school_id.'.jpg');

      // check if file exists
      if (!file_exists(public_path('img/blog/large/uni-'.$course->school->school_id.'.jpg'))) {
        $heroSrc = null;
      }

    @endphp

    @if($heroSrc)
    <div class="mb-6">
      <img src="{{ $heroSrc }}" alt="{{ $course->school->name ?? 'Univerzita' }}" class="w-full h-72 md:h-96 object-cover rounded-lg shadow" loading="lazy">
    </div>
    @endif

    <article class="bg-white rounded-lg shadow p-6">
      <header class="mb-6">
        <h1 class="text-2xl font-bold mb-2">{{ $course->title_cs ?? $course->title_en }}</h1>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-gray-600 text-sm">
          <div>
            <span class="font-medium">Škola: </span>
            @if($course->school)
              <a href="{{ $course->school->link }}" target="_blank" rel="noopener" class="text-emerald-600 hover:underline">{{ $course->school->name }}</a>
            @else
              <span>—</span>
            @endif
          </div>

          <div>
            <span class="font-medium">Úroveň studia:</span>
            {{ $course->level?->name ?? '—' }}
          </div>

          <div>
            <span class="font-medium">Délka:</span>
            @if($course->duration_length)
              @php
                $len = intval($course->duration_length);
                $unit = $course->duration_unit ?? '';
                $unit_local = $unit;
                if (is_string($unit)) {
                    $u = strtolower($unit);
                    if (in_array($u, ['years', 'year', 'yrs', 'yr'])) {
                        if ($len === 1) {
                            $unit_local = 'rok';
                        } elseif ($len >= 2 && $len <= 4) {
                            $unit_local = 'roky';
                        } else {
                            $unit_local = 'let';
                        }
                    }
                }
              @endphp

              {{ $course->duration_length }} {{ $unit_local }}
            @else
              —
            @endif
          </div>
        </div>
      </header>

      <section class="prose prose-sm max-w-none mb-6 text-gray-800">
        {{-- Use Czech description when available --}}
        @if(!empty($course->description_cs))
          {!! $course->description_cs !!}
        @elseif(!empty($course->description_en))
          {!! $course->description_en !!}
        @else
          <p>Popis kurzu není dostupný.</p>
        @endif
      </section>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        {{-- <div class="bg-gray-50 p-4 rounded">
          <h3 class="font-semibold mb-2">Informace o kurzu</h3>
          <ul class="text-sm text-gray-700 space-y-2">
            <li><span class="font-medium">Požadované body:</span> {{ $course->points_required ?? '—' }}</li>
            <li><span class="font-medium">Portfolio vyžadováno:</span> {{ $course->portfolio_required ? 'Ano' : 'Ne' }}</li>
            <li><span class="font-medium">Studijní režim:</span> {{ $course->study_mode ?? '—' }}</li>
            <li><span class="font-medium">Další poznámky:</span> {{ $course->additional_notes_cs ?? $course->additional_notes_en ?? '—' }}</li>
          </ul>
        </div> --}}

        <div class="bg-gray-50 p-4 rounded">
          <h3 class="font-semibold mb-2">Kategorie a umístění</h3>
          <div class="text-sm text-gray-700 space-y-2">
            <div>
              <span class="font-medium">Obory:</span>
              @if($course->fields && $course->fields->count())
                {{ $course->fields->pluck('name')->join(', ') }}
              @else
                —
              @endif
            </div>

            {{-- <div>
              <span class="font-medium">Lokace:</span>
              @if($course->locations && $course->locations->count())
                {{ $course->locations->pluck('name')->join(', ') }}
              @else
                —
              @endif
            </div> --}}
          </div>
        </div>
      </div>

      <footer class="flex flex-col md:flex-row items-center justify-between mt-6">
        <div>
          @if(!empty($course->link))
            <a target="_blank" rel="noopener" href="{{ $course->link }}" class="inline-block px-4 py-2 bg-emerald-600 text-white rounded hover:opacity-95">Přejít na stránku kurzu</a>
          @endif
        </div>

        <div class="text-sm text-gray-600">Kód kurzu: {{ $course->code }}</div>
      </footer>
    </article>

    <div class="mt-8">
      <a href="{{ route('finder.courses') }}" class="text-emerald-600 hover:underline">« Zpět na seznam kurzů</a>
    </div>
  
  @if(isset($relatedCourses) && $relatedCourses->count())
    <section class="max-w-4xl mx-auto px-4 py-12">
      <h2 class="text-2xl font-bold mb-4">Podobné kurzy</h2>
      <p class="text-gray-700 mb-6">Další kurzy, které spadají do stejných oborů jako tento kurz.</p>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($relatedCourses as $rc)
          <article class="bg-white rounded-lg p-4 border shadow-sm">
            <h3 class="text-lg font-semibold mb-1">{{ $rc->title_cs ?? $rc->title_en }}</h3>
            <div class="text-sm text-gray-600 mb-2">{{ $rc->school?->name ?? '' }}</div>
            <p class="text-gray-700 text-sm mb-3">{{ Str::limit(strip_tags($rc->description_cs ?? $rc->description_en), 140) }}</p>
            <div class="flex items-center justify-between">
              <a href="{{ route('finder.course.show', $rc->id) }}" class="text-emerald-600 hover:underline">Zobrazit</a>
              {{-- <div class="text-sm text-gray-600">ID: {{ $rc->id }}</div> --}}
            </div>
          </article>
        @endforeach
      </div>
    </section>
  @endif
  </div>
@endsection

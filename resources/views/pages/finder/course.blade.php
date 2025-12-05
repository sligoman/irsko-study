@extends('layouts.app')

@section('title', ($course->title_cs ?? $course->title_en) . ' — Irsko Study')
@section('meta_description', Str::limit(strip_tags($course->description_cs ?? $course->description_en), 160))

@section('content')
  <div class="max-w-4xl mx-auto px-4 py-12">
    <article class="bg-white rounded-lg shadow p-6">
      <header class="mb-6">
        <h1 class="text-2xl font-bold mb-2">{{ $course->title_cs ?? $course->title_en }}</h1>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-gray-600 text-sm">
          <div>
            <span class="font-medium">Škola:</span>
            @if($course->school)
              <a href="{{ route('finder.school.show', $course->school->id) }}" class="text-emerald-600 hover:underline">{{ $course->school->name }}</a>
            @else
              <span>—</span>
            @endif
          </div>

          <div>
            <span class="font-medium">Úroveň:</span>
            {{ $course->level?->name ?? '—' }}
          </div>

          <div>
            <span class="font-medium">Délka:</span>
            @if($course->duration_length)
              {{ $course->duration_length }} {{ $course->duration_unit ?? '' }}
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
        <div class="bg-gray-50 p-4 rounded">
          <h3 class="font-semibold mb-2">Informace o kurzu</h3>
          <ul class="text-sm text-gray-700 space-y-2">
            <li><span class="font-medium">Požadované body:</span> {{ $course->points_required ?? '—' }}</li>
            <li><span class="font-medium">Portfolio vyžadováno:</span> {{ $course->portfolio_required ? 'Ano' : 'Ne' }}</li>
            <li><span class="font-medium">Studijní režim:</span> {{ $course->study_mode ?? '—' }}</li>
            <li><span class="font-medium">Další poznámky:</span> {{ $course->additional_notes_cs ?? $course->additional_notes_en ?? '—' }}</li>
          </ul>
        </div>

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

            <div>
              <span class="font-medium">Lokace:</span>
              @if($course->locations && $course->locations->count())
                {{ $course->locations->pluck('name')->join(', ') }}
              @else
                —
              @endif
            </div>
          </div>
        </div>
      </div>

      <footer class="flex items-center justify-between mt-6">
        <div>
          @if(!empty($course->link))
            <a target="_blank" rel="noopener" href="{{ $course->link }}" class="inline-block px-4 py-2 bg-emerald-600 text-white rounded hover:opacity-95">Přejít na stránku kurzu</a>
          @endif
        </div>

        <div class="text-sm text-gray-600">ID kurzu: {{ $course->id }}</div>
      </footer>
    </article>

    <div class="mt-8">
      <a href="{{ route('finder.courses') }}" class="text-emerald-600 hover:underline">« Zpět na seznam kurzů</a>
    </div>
  </div>
@endsection

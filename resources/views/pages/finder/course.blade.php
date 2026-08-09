@extends('layouts.app')

@section('title', ($course->title_cs ?? $course->title_en) . ' — Irsko Study')
@section('meta_description', Str::limit(strip_tags($course->description_cs ?? $course->description_en), 160))

@section('content')
  @php
    $courseTitle = $course->title_cs ?? $course->title_en;
    $duration = null;
    if ($course->duration_length) {
        $len = intval($course->duration_length);
        $unit = strtolower((string) ($course->duration_unit ?? ''));
        $unitLocal = match (true) {
            in_array($unit, ['years', 'year', 'yrs', 'yr']) && $len === 1 => 'rok',
            in_array($unit, ['years', 'year', 'yrs', 'yr']) && $len >= 2 && $len <= 4 => 'roky',
            in_array($unit, ['years', 'year', 'yrs', 'yr']) => 'let',
            default => $unit,
        };
        $duration = trim("$len $unitLocal");
    }
    $fields = $course->fields && $course->fields->count()
        ? $course->fields->pluck('name')->join(', ')
        : null;
  @endphp

  <main data-redesign-page="course-detail" class="bg-base-white">
    <section data-redesign-section="course-detail-hero" class="relative overflow-hidden rounded-b-[24px] bg-brand-dark-green text-white">
      <div class="absolute inset-0">
        <x-subpage.university-image
          :school-id="$course->school->school_id ?? null"
          :name="$course->school->name ?? 'Univerzita'"
          img-class="absolute inset-0 h-full w-full object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-black/35 to-black/60"></div>
      </div>
      <div class="layout-container relative pb-16 pt-[104px] md:pb-24 md:pt-28">
        <div class="grid items-center gap-10 lg:grid-cols-[1fr_400px]">
          <div>
            <p class="type-text-md-semibold inline-flex rounded-full bg-white/10 px-4 py-2 text-white ring-1 ring-white/15">{{ $course->school?->name ?? 'Univerzita' }}</p>
            <h1 class="type-display-xl mt-6 max-w-[820px] text-white">{{ $courseTitle }}</h1>
            @if(!empty($course->title_en) && $course->title_en !== $courseTitle)
              <p class="type-text-lg mt-4 text-white/80">{{ $course->title_en }}</p>
            @endif
            <div class="mt-10 grid max-w-[940px] gap-4 sm:grid-cols-3">
              <div class="rounded-[12px] bg-white/10 p-5 ring-1 ring-white/15">
                <p class="type-display-xs text-white">{{ $course->level?->name ?? '—' }}</p>
                <p class="type-text-sm mt-1 text-white/80">Úroveň studia</p>
              </div>
              <div class="rounded-[12px] bg-white/10 p-5 ring-1 ring-white/15">
                <p class="type-display-xs text-white">{{ $duration ?? '—' }}</p>
                <p class="type-text-sm mt-1 text-white/80">Délka</p>
              </div>
              <div class="rounded-[12px] bg-white/10 p-5 ring-1 ring-white/15">
                <p class="type-display-xs text-white">{{ $course->code }}</p>
                <p class="type-text-sm mt-1 text-white/80">Kód kurzu</p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <section data-redesign-section="course-detail-content" class="home-section">
      <div class="layout-container">
        <div class="grid gap-8 lg:grid-cols-[1fr_360px]">
          <div>
            @if(!empty($course->description_cs) || !empty($course->description_en))
              <div class="blog-post type-text-md text-brand-dark-green">
                {!! $course->description_cs ?: $course->description_en !!}
              </div>
            @else
              <p class="type-text-lg text-brand-dark-green">Popis kurzu není dostupný.</p>
            @endif
          </div>

          <aside class="space-y-4">
            <div class="rounded-[16px] bg-brand-light-gray p-6">
              <h2 class="type-display-xs text-brand-dark-green">Obor a umístění</h2>
              <p class="type-text-md mt-3 text-brand-dark-green">{{ $fields ?? '—' }}</p>
            </div>

            <div class="rounded-[16px] bg-brand-light-gray p-6">
              <h2 class="type-display-xs text-brand-dark-green">Studuj na {{ $course->school?->name ?? 'škole' }}</h2>
              @if(!empty($course->school->link))
                <a href="{{ $course->school->link }}" target="_blank" rel="noopener" class="type-text-md mt-3 inline-block text-brand-dark-green underline underline-offset-4 hover:text-brand-orange">Oficiální web školy →</a>
              @endif
            </div>

            @if(!empty($course->link))
              <a href="{{ $course->link }}" target="_blank" rel="noopener" class="type-input-label transition-color-figma inline-flex w-full items-center justify-center rounded-[8px] bg-brand-light-green px-8 py-4 text-brand-dark-green hover:bg-[#7db709]">Přejít na stránku kurzu</a>
            @endif
            <a href="{{ route('contact') }}" class="type-input-label transition-color-figma inline-flex w-full items-center justify-center rounded-[8px] border border-brand-dark-green px-8 py-4 text-brand-dark-green hover:bg-white">Poradit se se studiem</a>
          </aside>
        </div>
      </div>
    </section>

    @if(isset($relatedCourses) && $relatedCourses->count())
      <section data-redesign-section="course-detail-related" class="home-section pb-20">
        <div class="layout-container">
          <h2 class="type-display-lg text-brand-dark-green">Podobné kurzy</h2>
          <p class="type-text-lg mt-3 text-brand-dark-green">Další programy, které spadají do stejných oborů.</p>
          <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach($relatedCourses as $rc)
              @php
                $rcSlug = $rc->url;
                if (empty($rcSlug) && !empty($rc->school->school_id) && !empty($rc->code)) {
                    $rcSlug = strtolower($rc->school->school_id . '-' . $rc->code);
                } elseif (empty($rcSlug)) {
                    $rcSlug = $rc->code ?? $rc->id;
                }
              @endphp
              <article class="flex h-full flex-col overflow-hidden rounded-[16px] bg-brand-light-gray transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="flex flex-1 flex-col p-6">
                  <p class="type-text-sm text-brand-orange">{{ $rc->school?->name ?? 'Kurz' }}</p>
                  <h3 class="type-display-xs mt-2 text-brand-dark-green">{{ $rc->title_cs ?? $rc->title_en }}</h3>
                  <p class="type-text-md mt-3 text-brand-dark-green">{{ Str::limit(strip_tags($rc->description_cs ?? $rc->description_en), 120) }}</p>
                  <a href="{{ route('finder.course.show', $rcSlug) }}" class="type-text-sm mt-4 text-brand-dark-green hover:text-brand-orange">Zobrazit detail →</a>
                </div>
              </article>
            @endforeach
          </div>
        </div>
      </section>
    @endif

    @include('components.cta')
  </main>
@endsection

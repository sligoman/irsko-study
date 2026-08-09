@extends('layouts.app')

@section('title', ($school->name ?? 'Univerzita') . ' — Irsko Study')
@section('meta_description', Str::limit(strip_tags($school->description_cs ?? $school->description_en ?? ''), 160))

@section('content')
  <main data-redesign-page="school-detail" class="bg-base-white">
    <section data-redesign-section="school-detail-hero" class="relative overflow-hidden rounded-b-[24px] bg-brand-dark-green text-white">
      <div class="absolute inset-0">
        <x-subpage.university-image
          :school-id="$school->school_id ?? null"
          :name="$school->name ?? 'Univerzita'"
          img-class="absolute inset-0 h-full w-full object-cover"
        />
        <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-black/35 to-black/60"></div>
      </div>
      <div class="layout-container relative py-16 md:py-24">
        <div class="grid items-center gap-10 lg:grid-cols-[1fr_400px]">
          <div>
            <p class="type-text-md-semibold inline-flex rounded-full bg-white/10 px-4 py-2 text-white ring-1 ring-white/15">Vysoká škola</p>
            <h1 class="type-display-xl mt-6 max-w-[820px] text-white">{{ $school->name }}@if($school->acronym) ({{ $school->acronym }})@endif</h1>
            @if(!empty($school->description_cs) || !empty($school->description_en))
              <p class="type-text-lg mt-4 max-w-[760px] text-white/85">{{ Str::limit(strip_tags($school->description_cs ?: $school->description_en), 220) }}</p>
            @endif
            <div class="mt-10 grid max-w-[940px] gap-4 sm:grid-cols-3">
              <div class="rounded-[12px] bg-white/10 p-5 ring-1 ring-white/15">
                <p class="type-display-xs text-white">{{ $school->courses ? $school->courses->count() : 0 }}</p>
                <p class="type-text-sm mt-1 text-white/80">programů v nabídce</p>
              </div>
              <div class="rounded-[12px] bg-white/10 p-5 ring-1 ring-white/15">
                <p class="type-display-xs text-white">{{ $school->locations && $school->locations->count() ? $school->locations->count() : 0 }}</p>
                <p class="type-text-sm mt-1 text-white/80">kampusů</p>
              </div>
              <div class="rounded-[12px] bg-white/10 p-5 ring-1 ring-white/15">
                <p class="type-display-xs text-white">EN</p>
                <p class="type-text-sm mt-1 text-white/80">výuka v angličtině</p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <section data-redesign-section="school-detail-content" class="home-section">
      <div class="layout-container">
        <div class="grid gap-8 lg:grid-cols-[1fr_360px]">
          <div>
            <div class="blog-post type-text-md text-brand-dark-green">
              @if(!empty($school->description_cs))
                {!! $school->description_cs !!}
              @elseif(!empty($school->description_en))
                {!! $school->description_en !!}
              @else
                <p>Informace o škole nejsou dostupné.</p>
              @endif
            </div>
          </div>

          <aside class="space-y-4">
            <div class="rounded-[16px] bg-brand-light-gray p-6">
              <h2 class="type-display-xs text-brand-dark-green">O škole</h2>
              @if(!empty($school->link))
                <a href="{{ $school->link }}" target="_blank" rel="noopener" class="type-text-md mt-3 inline-block text-brand-dark-green underline underline-offset-4 hover:text-brand-orange">Oficiální web školy →</a>
              @endif
            </div>
            <a href="{{ route('finder.courses', ['school' => $school->school_id ?? $school->id]) }}" class="type-input-label transition-color-figma inline-flex w-full items-center justify-center rounded-[8px] bg-brand-light-green px-8 py-4 text-brand-dark-green hover:bg-[#7db709]">Zobrazit všechny kurzy</a>
            <a href="{{ route('contact') }}" class="type-input-label transition-color-figma inline-flex w-full items-center justify-center rounded-[8px] border border-brand-dark-green px-8 py-4 text-brand-dark-green hover:bg-white">Poradit se se studiem</a>
          </aside>
        </div>
      </div>
    </section>

    @if(isset($school->courses) && $school->courses->count())
      <section data-redesign-section="school-detail-courses" class="home-section">
        <div class="layout-container">
          <h2 class="type-display-lg text-brand-dark-green">Programy na {{ $school->name }}</h2>
          <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach($school->courses as $c)
              @php
                $courseUrl = $c->url;
                if (empty($courseUrl) && !empty($c->school->school_id) && !empty($c->code)) {
                    $courseUrl = strtolower($c->school->school_id . '-' . $c->code);
                } elseif (empty($courseUrl)) {
                    $courseUrl = $c->code ?? $c->id;
                }
              @endphp
              <article class="flex h-full flex-col overflow-hidden rounded-[16px] bg-brand-light-gray transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="flex flex-1 flex-col p-6">
                  <p class="type-text-sm text-brand-orange">{{ $c->level?->name ?? 'Program' }}</p>
                  <h3 class="type-display-xs mt-2 text-brand-dark-green">{{ $c->title_cs ?? $c->title_en }}</h3>
                  <p class="type-text-md mt-3 text-brand-dark-green">{{ Str::limit(strip_tags($c->description_cs ?? $c->description_en), 120) }}</p>
                  <div class="mt-6 flex items-center justify-between gap-3">
                    <a href="{{ route('finder.course.show', $courseUrl) }}" class="type-text-sm text-brand-dark-green hover:text-brand-orange">Zobrazit detail →</a>
                    <span class="type-text-sm text-brand-dark-green">Kód: {{ $c->code }}</span>
                  </div>
                </div>
              </article>
            @endforeach
          </div>
        </div>
      </section>
    @endif

    @if(!empty($school->locations) && $school->locations->count())
      <section data-redesign-section="school-detail-campuses" class="home-section pb-20">
        <div class="layout-container">
          <h2 class="type-display-lg text-brand-dark-green">Kampusy</h2>
          <div class="mt-8 grid gap-5 md:grid-cols-2">
            @foreach($school->locations as $loc)
              <div class="rounded-[16px] bg-brand-light-gray p-6">
                <h3 class="type-display-xs text-brand-dark-green">{{ $loc->name }}</h3>
                @php
                  $embedHtml = trim($loc->embed ?? $loc->map ?? '');
                @endphp
                @if($embedHtml)
                  <div class="mt-4 overflow-hidden rounded-[8px]">
                    @if(str_contains($embedHtml, '<iframe'))
                      {!! $embedHtml !!}
                    @else
                      <iframe src="{{ $embedHtml }}" width="100%" height="300" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                    @endif
                  </div>
                  <a href="{{ $loc->map ?? $loc->embed }}" target="_blank" rel="noopener" class="type-text-sm mt-3 inline-block text-brand-dark-green underline underline-offset-4 hover:text-brand-orange">Otevřít v Google Maps</a>
                @else
                  <p class="type-text-md mt-3 text-brand-dark-green">K dispozici není mapa pro tuto pobočku.</p>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      </section>
    @endif

    @include('components.cta')
  </main>
@endsection

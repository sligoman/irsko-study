@extends('layouts.app')

@section('title', 'Kurzy a programy v Irsku')
@section('meta_description', 'Přehled kurzů a programů — filtrovat podle školy, oboru a úrovně. Najděte kurz, který vám sedí.')

@section('content')
  <main data-redesign-page="courses-finder" class="bg-base-white">
    <x-subpage.hero-photo
      eyebrow="Kurzy a programy"
      title="Najdi kurz, který"
      accent="sedne právě tobě"
      text="Procházej programy irských univerzit a škol. Filtruj podle školy, oboru a úrovně studia — výsledky se aktualizují bez načítání stránky."
      :photo="[
        'src' => 'img/static/irsko_dublin_hero.jpg',
        'sizes' => '(max-width: 768px) 100vw, 1280px',
        'alt' => 'Dublin, Irsko',
      ]"
      :stats="[
        ['value' => '1 000+', 'label' => 'programů v databázi'],
        ['value' => '25+', 'label' => 'škol a univerzit'],
        ['value' => 'CS', 'label' => 'české popisy oborů'],
      ]"
    />

    <section data-redesign-section="course-finder" class="home-section pb-20">
      <div class="layout-container">
        <div class="overflow-hidden rounded-[16px] bg-brand-light-gray p-4 md:p-8">
          <course-finder :initial-data='@json($initialData)'></course-finder>
        </div>
      </div>
    </section>

    @include('components.cta')
  </main>
@endsection

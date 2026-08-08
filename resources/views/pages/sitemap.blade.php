@extends('layouts.app')

@section('title', 'Sitemap — Irsko Study')
@section('meta_description', 'Mapa stránek IrskoStudy — seznam veřejných stránek a článků pro snadnou orientaci.')

@section('content')
  <main data-redesign-page="sitemap" class="bg-base-white">
    <x-subpage.hero
      eyebrow="Mapa stránek"
      title="Najdeš tady"
      accent="vše"
      text="Přehled všech důležitých stránek a článků na IrskoStudy. Klikni na odkaz a pokračuj tam, kam potřebuješ."
      :stats="[
        ['value' => '10+', 'label' => 'hlavních stránek'],
        ['value' => 'CS', 'label' => 'přehledné členění'],
        ['value' => 'Blog', 'label' => 'články z praxe'],
      ]"
    />

    <section data-redesign-section="sitemap-links" class="home-section pb-20">
      <div class="layout-container">
        <div class="grid gap-5 lg:grid-cols-2">
          <div class="rounded-[16px] bg-brand-light-gray p-6 md:p-10">
            <h2 class="type-display-lg text-brand-dark-green">Hlavní stránky</h2>
            <ul class="mt-6 space-y-3">
              @foreach([
                ['label' => 'Domů', 'href' => route('home')],
                ['label' => 'O nás', 'href' => route('about')],
                ['label' => 'Proč Irsko', 'href' => route('why')],
                ['label' => 'Vysoké školy', 'href' => route('universities')],
                ['label' => 'Kurzy a programy', 'href' => route('finder.courses')],
                ['label' => 'Služby', 'href' => route('services')],
                ['label' => 'FAQ', 'href' => route('faq')],
                ['label' => 'Kontakt', 'href' => route('contact')],
                ['label' => 'Ochrana osobních údajů', 'href' => route('privacy.cs')],
                ['label' => 'Sitemap (XML)', 'href' => url('/sitemap.xml')],
              ] as $link)
                <li>
                  <a href="{{ $link['href'] }}" class="type-text-md text-brand-dark-green underline underline-offset-4 hover:text-brand-orange">{{ $link['label'] }}</a>
                </li>
              @endforeach
            </ul>
          </div>

          <div class="rounded-[16px] bg-brand-light-gray p-6 md:p-10">
            <h2 class="type-display-lg text-brand-dark-green">Blog a novinky</h2>
            @if($posts->count())
              <ul class="mt-6 space-y-3">
                @foreach($posts as $post)
                  <li>
                    <a href="{{ route('blog.show', $post->slug) }}" class="type-text-md text-brand-dark-green underline underline-offset-4 hover:text-brand-orange">{{ $post->title }}</a>
                  </li>
                @endforeach
              </ul>
            @else
              <p class="type-text-md mt-6 text-brand-dark-green">Žádné články k zobrazení.</p>
            @endif
          </div>
        </div>
      </div>
    </section>

    @include('components.cta')
  </main>
@endsection

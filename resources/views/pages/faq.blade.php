@extends('layouts.app')

@section('title', 'FAQ — Irsko Study')
@section('meta_description', 'Často kladené otázky k přihláškám, studiu a životu v Irsku — rychlé odpovědi na nejčastější dotazy studentů z ČR a SK.')

@section('content')
  <main data-redesign-page="faq" class="bg-base-white">
    <x-subpage.hero-light
      eyebrow="Časté dotazy"
      title="Na co se nás"
      accent="nejčastěji ptáte"
      text="Připravili jsme odpovědi na nejčastější dotazy k přihláškám, dokumentům, financování a životu v Irsku. Pokud tu nenajdeš odpověď, napiš nám."
      :stats="[
        ['value' => '10+', 'label' => 'odpovědí na časté dotazy'],
        ['value' => '0 Kč', 'label' => 'úvodní konzultace'],
        ['value' => 'CS', 'label' => 'rady od týmu v Irsku'],
      ]"
    />

    <section data-redesign-section="faq-list" class="home-section pb-20">
      <div class="layout-container">
        <div class="grid gap-8 lg:grid-cols-[400px_1fr] lg:gap-16">
          <div class="max-w-[400px]">
            <p class="type-text-md-semibold text-brand-orange">Rychlá navigace</p>
            <h2 class="type-display-lg mt-2 text-brand-dark-green">Co tě nejvíc zajímá</h2>
            <p class="type-text-lg mt-4 text-brand-dark-green">Odpovědi jsou řazené od základů po praktické detaily. Klikni na otázku a odpověď se rozbalí.</p>
          </div>

          <div class="grid gap-4">
            @forelse($items as $faq)
              <details class="group rounded-[8px] bg-brand-light-gray px-6 py-5 transition-color-figma hover:bg-brand-light-green/15" @if($loop->first) open @endif>
                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 marker:hidden">
                  <span class="type-display-xs text-brand-dark-green">{{ $faq['question'] }}</span>
                  <span class="mt-1 inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-light-green text-brand-dark-green transition-transform duration-200 group-open:rotate-180">
                    <svg viewBox="0 0 16 16" class="h-4 w-4" fill="none" aria-hidden="true">
                      <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </span>
                </summary>
                <div class="type-text-md mt-3 text-brand-dark-green [&_p]:mt-3 [&_p]:text-brand-dark-green">
                  {!! $faq['answer'] !!}
                </div>
              </details>
            @empty
              <div class="rounded-[16px] bg-brand-light-gray p-8">
                <p class="type-text-lg text-brand-dark-green">Zatím zde nejsou žádné otázky k zobrazení.</p>
              </div>
            @endforelse
          </div>
        </div>
      </div>
    </section>

    @include('components.cta')
  </main>
@endsection

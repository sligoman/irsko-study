@extends('layouts.app')

@section('title', 'Stránka nenalezena | IrskoStudy')
@section('meta_description', 'Stránka bohužel nebyla nalezena.')
@section('robots', 'noindex')

@section('content')
  <main data-redesign-page="404" class="bg-base-white">
    <section class="pb-20 pt-8 md:pt-10">
      <div class="layout-container">
        <nav class="type-text-sm mb-4 flex items-center gap-2 text-brand-dark-green" aria-label="Drobečková navigace">
          <a href="{{ route('home') }}" class="transition-color-figma hover:underline">Domů</a>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" aria-hidden="true" class="h-4 w-4 shrink-0 text-brand-light-green">
            <path d="m12 6 10 10-10 10" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
          </svg>
          <span class="type-text-sm-semibold" aria-current="page">404</span>
        </nav>

        <div class="mt-8 grid items-stretch gap-4 lg:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)]">
          <div class="rounded-[24px] bg-brand-light-gray p-6 md:p-10">
            <p class="type-text-md text-brand-orange">Chyba 404</p>
            <h1 class="type-display-xl mt-3 text-brand-dark-green">Stránka nenalezena</h1>
            <p class="type-text-lg mt-4 max-w-[720px] text-brand-dark-green">
              Odkaz může být neplatný nebo už stránka neexistuje. Pokračujte na úvodní stránku, nebo si vyberte některou z hlavních sekcí webu.
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
              <x-button :href="route('home')">
                Zpět na úvod
              </x-button>
              <x-button :href="route('contact')" variant="secondary">
                Nezávazná konzultace
              </x-button>
            </div>
          </div>

          <aside class="redesign-footer-pattern rounded-[24px] p-6 text-white md:p-10">
            <p class="type-text-md text-brand-light-green">Kam dál</p>
            <div class="mt-6 space-y-4">
              <a href="{{ route('universities') }}" class="block rounded-[16px] border border-white/15 bg-white/10 px-5 py-4 transition-color-figma hover:bg-white/20">
                <span class="type-display-xs block text-white">Vysoké školy</span>
                <span class="type-text-md mt-2 block text-white/80">Prohlédněte si školy a možnosti studia v Irsku.</span>
              </a>
              <a href="{{ route('finder.courses') }}" class="block rounded-[16px] border border-white/15 bg-white/10 px-5 py-4 transition-color-figma hover:bg-white/20">
                <span class="type-display-xs block text-white">Kurzy</span>
                <span class="type-text-md mt-2 block text-white/80">Najděte kurz angličtiny nebo studijní program podle svých plánů.</span>
              </a>
              <a href="{{ route('services') }}" class="block rounded-[16px] border border-white/15 bg-white/10 px-5 py-4 transition-color-figma hover:bg-white/20">
                <span class="type-display-xs block text-white">Naše služby</span>
                <span class="type-text-md mt-2 block text-white/80">Pomůžeme vám s přihláškou, ubytováním i začátkem v Irsku.</span>
              </a>
            </div>
          </aside>
        </div>
      </div>
    </section>
  </main>
@endsection

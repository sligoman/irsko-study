@extends('layouts.app')

@section('title', 'O nás — Irsko Study')
@section('meta_description', 'O nás — kdo jsme, proč se specializujeme na Irsko a jak pomáháme studentům z ČR a SK s přestupem na irské vysoké školy.')

@section('content')
  <main data-redesign-page="about" class="bg-base-white">
    <section data-redesign-section="about-hero" class="relative overflow-hidden rounded-b-[24px] bg-brand-dark-green text-white">
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_14%_22%,rgba(255,120,46,0.26),transparent_28%),radial-gradient(circle_at_88%_18%,rgba(156,204,87,0.2),transparent_30%)]"></div>
      <div class="layout-container relative py-20 md:py-28">
        <div class="max-w-[900px]">
          <p class="type-text-md-semibold inline-flex rounded-full bg-white/10 px-4 py-2 text-white ring-1 ring-white/15">O nás</p>
          <h1 class="type-display-2xl mt-6 text-white">Irsko známe <span class="text-brand-orange">zevnitř</span>, ne z katalogu</h1>
          <p class="type-text-lg mt-6 max-w-[720px] text-white/85">Pomáháme českým a slovenským studentům splnit sen o studiu v Irsku. Studium v Irsku pro nás není jen téma, ale vlastní zkušenost a každodenní realita.</p>
        </div>

        <div class="mt-12 grid max-w-[900px] gap-4 sm:grid-cols-3">
          <div class="rounded-[12px] bg-white/10 p-5 ring-1 ring-white/15">
            <p class="type-display-xs text-white">20 let</p>
            <p class="type-text-sm mt-1 text-white/80">života v Irsku</p>
          </div>
          <div class="rounded-[12px] bg-white/10 p-5 ring-1 ring-white/15">
            <p class="type-display-xs text-white">1 země</p>
            <p class="type-text-sm mt-1 text-white/80">specializujeme se na Irsko</p>
          </div>
          <div class="rounded-[12px] bg-white/10 p-5 ring-1 ring-white/15">
            <p class="type-display-xs text-white">A-Z</p>
            <p class="type-text-sm mt-1 text-white/80">podpora před i po příjezdu</p>
          </div>
        </div>
      </div>
    </section>

    <section data-redesign-section="about-values" class="home-section">
      <div class="layout-container">
        <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-end">
          <div>
            <p class="type-text-md-semibold text-brand-orange">Kdo jsme a proč právě Irsko</p>
            <h2 class="type-display-lg mt-2 text-brand-dark-green">Jsme průvodci, ne klasická agentura</h2>
          </div>
          <p class="type-text-lg text-brand-dark-green">V Irsku žijeme, studovali jsme zde a rozumíme systému, kultuře i výzvám, které studenty čekají. Na rozdíl od agentur, které pokrývají celý svět, se specializujeme pouze na Irsko.</p>
        </div>

        <div class="mt-12 grid gap-5 md:grid-cols-3">
          <article class="rounded-[16px] bg-brand-light-gray p-6">
            <div class="mb-8 h-1 w-16 rounded-full bg-brand-orange"></div>
            <h3 class="type-display-xs text-brand-dark-green">Naše filozofie</h3>
            <p class="type-text-md mt-3 text-brand-dark-green">Neslibujeme nemožné. Připravíme jasné kroky, vysvětlíme celý proces a zůstaneme v kontaktu i po příjezdu.</p>
          </article>
          <article class="rounded-[16px] bg-brand-dark-green p-6 text-white">
            <div class="mb-8 h-1 w-16 rounded-full bg-brand-light-green"></div>
            <h3 class="type-display-xs text-white">Naše mise</h3>
            <p class="type-text-md mt-3 text-white/85">Otevírat studentům z ČR a SK dveře ke kvalitnímu vzdělání v Irsku lidsky, osobně a s hlubokou znalostí prostředí.</p>
          </article>
          <article class="rounded-[16px] bg-brand-light-gray p-6">
            <div class="mb-8 h-1 w-16 rounded-full bg-brand-orange"></div>
            <h3 class="type-display-xs text-brand-dark-green">Proč my</h3>
            <p class="type-text-md mt-3 text-brand-dark-green">Pomůžeme s výběrem školy, ubytováním, bankou i orientací ve městě. Opíráme se o reálné zkušenosti z Irska.</p>
          </article>
        </div>
      </div>
    </section>

    <section data-redesign-section="about-team" class="home-section">
      <div class="layout-container">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
          <div>
            <p class="type-text-md-semibold text-brand-orange">Náš tým</p>
            <h2 class="type-display-lg mt-2 text-brand-dark-green">Lidé, kteří tě povedou</h2>
          </div>
          <p class="type-text-lg max-w-[560px] text-brand-dark-green">Každý z nás má vlastní zahraniční zkušenost. Díky tomu rozumíme praktickým otázkám i emocím, které se při odjezdu objeví.</p>
        </div>

        <div class="mt-10 grid gap-6 lg:grid-cols-2">
          <article class="overflow-hidden rounded-[16px] bg-brand-light-gray">
            <img src="{{ asset('img/team/david_fiala.jpg') }}" alt="David Fiala" class="h-[360px] w-full object-cover" loading="lazy" decoding="async">
            <div class="p-6 md:p-8">
              <p class="type-text-sm text-brand-orange">David Fiala, majitel</p>
              <h3 class="type-display-sm mt-2 text-brand-dark-green">Žiju v Irsku téměř 20 let, studoval jsem v USA, Německu a Irsku</h3>
              <div class="mt-5 space-y-4 text-brand-dark-green type-text-md">
                <p>Na prvních výměnných kurzech jsem byl už ve 14 letech v Holandsku a Belgii. Později jsem studoval v USA, v Německu na Erasmu a po studiích odešel do Irska hledat práci.</p>
                <p>V Irsku jsem pracoval pro Ebay, studoval účetnictví a finance na Dublin Business School a později vedl španělský tým v Paddy Power. Tyto zkušenosti mi ukázaly, jak velký rozdíl dokáže udělat jazyk, zahraniční praxe a odvaha vyjet ven.</p>
                <p>Irsko mě získalo lidmi, přírodou a životním stylem. Usadil jsem se tu s rodinou a dnes pomáhám studentům, aby jejich cesta byla jasnější a klidnější než moje první kroky v zahraničí.</p>
              </div>
            </div>
          </article>

          <article class="overflow-hidden rounded-[16px] bg-brand-light-gray">
            <img src="{{ asset('img/team/michal_chupik.jpeg') }}" alt="Michal Chupík" class="h-[360px] w-full object-cover" loading="lazy" decoding="async">
            <div class="p-6 md:p-8">
              <p class="type-text-sm text-brand-orange">Michal Chupík, koordinátor</p>
              <h3 class="type-display-sm mt-2 text-brand-dark-green">Student TU Dublin, letecké technologie a koordinace</h3>
              <div class="mt-5 space-y-4 text-brand-dark-green type-text-md">
                <p>Jsem z Olomouce a momentálně žiju a studuju v Dublinu technický obor letecké technologie na TU Dublin. Irsko jsem si vybral kvůli studiu v angličtině a dobré dostupnosti z domova.</p>
                <p>Můj první rok v Irsku je plný objevování: výuka, administrativa, bydlení, orientace ve městě i hledání rovnováhy mezi školou a volným časem. Díky tomu přesně vím, co studenti řeší v praxi.</p>
                <p>Baví mě letadla, drony, technologie, doprava a cestování. Jako koordinátor chci studentům pomáhat tak, aby měli hladký start a mohli se soustředit na studium i poznávání nové země.</p>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>

    <section data-redesign-section="about-contact" class="home-section pb-20">
      <div class="layout-container">
        <div class="grid overflow-hidden rounded-[16px] bg-brand-light-gray lg:grid-cols-[1fr_0.8fr]">
          <div class="p-6 md:p-10 lg:p-12">
            <p class="type-text-md-semibold text-brand-orange">Kontakt</p>
            <h2 class="type-display-lg mt-2 text-brand-dark-green">Máš otázky ke studiu v Irsku?</h2>
            <p class="type-text-lg mt-6 max-w-[620px] text-brand-dark-green">Napiš nám nebo zavolej. Rádi poradíme, jestli je Irsko pro tebe dobrá volba a jaký první krok dává smysl.</p>
          </div>
          <div class="bg-white p-6 md:p-10 lg:p-12">
            <div class="space-y-4 type-text-md text-brand-dark-green">
              <p>E-mail: <a href="mailto:{{ config('contacts.email') }}" class="text-brand-orange hover:underline">{{ config('contacts.email') }}</a></p>
              <p>Mobil: <a href="tel:{{ config('contacts.mobile') }}" class="text-brand-orange hover:underline">{{ config('contacts.mobile') }}</a></p>
              <a href="{{ route('contact') }}" class="type-input-label transition-color-figma inline-flex items-center justify-center rounded-[8px] bg-brand-light-green px-8 py-4 text-brand-dark-green hover:bg-[#7db709]">Domluv si konzultaci</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    @include('components.cta')
  </main>
@endsection

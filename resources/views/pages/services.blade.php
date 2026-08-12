@extends('layouts.app')

@section('title', 'Služby - Irsko Study')
@section('meta_description', 'Naše služby zahrnují poradentství při přihláškách, pomoc s ubytováním a podpůrné služby po příjezdu do Irska. Podívejte se, jak vám můžeme pomoci.')

@section('content')
  <main data-redesign-page="services" class="bg-base-white">
    <x-subpage.hero
      eyebrow="Naše služby"
      title="Studium v Irsku"
      accent="bez chaosu"
      text="Na cestě za studiem v Irsku nejsi sám. Pomůžeme ti krok za krokem od prvotní konzultace, přes výběr školy a přihlášky, až po praktickou podporu po příjezdu."
      :stats="[
        ['value' => '01', 'label' => 'jasný plán a výběr školy'],
        ['value' => '02', 'label' => 'přihlášky a dokumenty bez chyb'],
        ['value' => '03', 'label' => 'praktická podpora v Irsku'],
      ]"
    />

    <section data-redesign-section="services-process" class="home-section">
      <div class="layout-container">
        <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-end">
          <div>
            <p class="type-text-md-semibold text-brand-orange">Spolupráce krok za krokem</p>
            <h2 class="type-display-lg mt-2 text-brand-dark-green">Každý krok má jasný účel</h2>
          </div>
          <p class="type-text-lg text-brand-dark-green">S námi máš jistotu, že nic nepřehlédneš a celý proces bude srozumitelný. Vysvětlíme možnosti, připravíme dokumenty a zůstaneme s tebou i po příjezdu.</p>
        </div>

        <div class="mt-12 grid gap-5 md:grid-cols-3">
          <x-subpage.feature-card title="Úvodní konzultace" text="Probereme akademické výsledky, zájmy a osobní cíle. Vysvětlíme specifika irského vysokého školství a nastavíme realistický plán dalších kroků." />
          <x-subpage.feature-card variant="dark" title="Výběr oboru a univerzity" text="Pomůžeme vybrat směr, který odpovídá tvým cílům, životnímu stylu i kariérním možnostem. Zohledníme lokalitu, náročnost studia i požadavky škol." />
          <x-subpage.feature-card title="Překlady a dokumenty" text="Pomůžeme s přepisy, oficiálními překlady, doporučeními a výběrem jazykového testu. Zkontrolujeme, že přihláška bude kompletní a bez chyb." />
        </div>
      </div>
    </section>

    <section class="home-section">
      <div class="layout-container">
        <div class="grid gap-5 lg:grid-cols-3">
          <x-subpage.feature-card title="Vyplnění přihlášky" text="Vytvoříme účet na portálech univerzit, pomůžeme s vyplněním údajů a zkontrolujeme termíny, poplatky i specifické požadavky jednotlivých škol." />
          <x-subpage.feature-card title="Příprava před odjezdem" text="Poradíme s pojištěním, ubytováním, plánem cesty a prvními praktickými kroky. Sdílíme tipy od studentů, kteří v Irsku opravdu žijí." />
          <x-subpage.feature-card title="Podpora po příjezdu" text="Po příjezdu nejsi sám. Pomůžeme s orientací ve městě a kampusu a jsme k dispozici během prvních měsíců, pokud se objeví otázky nebo problémy." />
        </div>
      </div>
    </section>

    <section class="home-section">
      <div class="layout-container">
        <div class="grid overflow-hidden rounded-[16px] bg-brand-dark-green text-white lg:grid-cols-[0.85fr_1.15fr]">
          <div class="p-6 md:p-10 lg:p-12">
            <p class="type-text-md-semibold text-brand-orange">Rychlý start</p>
            <h2 class="type-display-lg mt-2 text-white">Začni konzultací a odjeď s jasným plánem</h2>
          </div>
          <div class="bg-white/10 p-6 md:p-10 lg:p-12">
            <p class="type-text-lg text-white/85">Na úvodní konzultaci zjistíme, kde teď jsi, co chceš studovat a jaké termíny musíš hlídat. Potom dostaneš konkrétní doporučení pro další postup.</p>
            <div class="mt-8 flex flex-col gap-4 sm:flex-row">
              <a href="{{ route('contact') }}" class="type-input-label transition-color-figma inline-flex items-center justify-center rounded-[8px] bg-brand-light-green px-8 py-4 text-brand-dark-green hover:bg-[#7db709]">Domluv konzultaci</a>
              <a href="{{ route('faq') }}" class="type-input-label transition-color-figma inline-flex items-center justify-center rounded-[8px] border border-white/40 px-8 py-4 text-white hover:bg-white/10">Nejčastější dotazy</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section data-redesign-section="services-principles" class="home-section pb-20">
      <div class="layout-container">
        <x-subpage.summary-list
          heading="Na čem si zakládáme"
          :items="[
            'Osobní přístup: každý student je jedinečný a pracujeme individuálně',
            'Zkušenost: náš tým sám prošel procesem studia a života v zahraničí',
            'Praktičnost: dáváme rady, které fungují v reálném životě',
            'Dlouhodobá podpora: jsme tu i po tvém příjezdu do Irska',
            'Jasný proces: víš, co se bude dít a proč',
            'Lokální znalost: Irsko známe zevnitř, ne jen z webů univerzit',
          ]"
          :button="['label' => 'Začít plánovat studium', 'href' => route('contact')]"
        />
      </div>
    </section>

    @include('components.cta')
  </main>
@endsection

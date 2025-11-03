@extends('layouts.app')

@section('title', 'Služby - IrskoStudy')

@section('content')
  <div class="max-w-6xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <main class="lg:col-span-2">
        <h1 class="text-3xl font-bold mb-4">Naše služby</h1>
        <p class="text-gray-700 mb-6">Na cestě za studiem v Irsku nejsi sám. Pomůžeme ti krok za krokem — od prvotní konzultace, přes výběr školy a přihlášky, až po praktickou podporu po příjezdu.</p>

        <section class="mb-8 bg-gray-50 p-6 rounded-lg">
          <h2 class="text-2xl font-semibold mb-3">Jak probíhá spolupráce krok za krokem</h2>
          <ol class="list-decimal list-inside space-y-4 text-gray-700">
            <li>
              <strong>Úvodní konzultace</strong> — zjistíme tvé zájmy, akademické výsledky a možnosti, vysvětlíme systém irského vysokého školství a další kroky.
            </li>
            <li>
              <strong>Výběr oboru a univerzity</strong> — poradíme, které obory a školy odpovídají tvým cílům, zohledníme lokalitu, náročnost a perspektivy po studiu.
            </li>
            <li>
              <strong>Překlady a příprava dokumentů</strong> — pomůžeme s přepisy, oficiálními překlady, doporučeními a výběrem jazykového testu (IELTS, Duolingo apod.).
            </li>
            <li>
              <strong>Vyplnění přihlášky</strong> — vytvoření účtu, vyplnění údajů, volba škol a kontrola termínů a poplatků.
            </li>
            <li>
              <strong>Praktické poradenství před odjezdem</strong> — pojištění, ubytování, plán cesty a tipy od studentů, kteří mají zkušenost.
            </li>
            <li>
              <strong>Podpora po příjezdu</strong> — osobní setkání, pomoc s PPS, bankovním účtem, SIM kartou a orientací ve městě.
            </li>
          </ol>
        </section>

        {{-- <section class="mb-8">
          <h2 class="text-2xl font-semibold mb-4">Naše balíčky</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow">
              <h3 class="font-semibold text-xl mb-2">Balíček START</h3>
              <p class="text-gray-700 mb-3">Vhodné pro samostatné studenty, kteří chtějí mít proces pod kontrolou s odbornou podporou v klíčových bodech.</p>
              <ul class="text-gray-600 list-disc list-inside space-y-1 mb-4">
                <li>Úvodní konzultace</li>
                <li>Pomoc s výběrem školy a oboru</li>
                <li>Průvodce systémem CAO a termíny</li>
                <li>Pravidelný dohled nad postupem přihlášky</li>
                <li>Osobní setkání po příjezdu</li>
              </ul>
              <a href="{{ route('contact') }}" class="inline-block px-4 py-2 bg-[color:var(--color-emerald)] text-white rounded">Zjistit více / objednat</a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
              <h3 class="font-semibold text-xl mb-2">Balíček PREMIUM</h3>
              <p class="text-gray-700 mb-3">Kompletní servis „na klíč“ pro ty, kteří chtějí vše nechat na nás — od přihlášek po osobní podporu v Irsku.</p>
              <ul class="text-gray-600 list-disc list-inside space-y-1 mb-4">
                <li>Detailní vstupní konzultace</li>
                <li>Komplexní vedení při vyplňování přihlášek</li>
                <li>Kontrola a úprava dokumentů, překlady</li>
                <li>Individuální poradenství a vedení</li>
                <li>Osobní podpora a setkání po příjezdu</li>
              </ul>
              <a href="{{ route('contact') }}" class="inline-block px-4 py-2 bg-[color:var(--color-emerald)] text-white rounded">Objednat PREMIUM</a>
            </div>
          </div>
        </section> --}}

        <section>
          <h2 class="text-2xl font-semibold mb-3">Na čem si zakládáme</h2>
          <ul class="list-disc list-inside text-gray-700 space-y-2">
            <li><strong>Osobní přístup</strong> — každý student je pro nás jedinečný, pracujeme individuálně.</li>
            <li><strong>Zkušenost</strong> — náš tým sám prošel procesem studia v Irsku.</li>
            <li><strong>Praktičnost</strong> — dáváme rady, které fungují v reálném životě.</li>
            <li><strong>Dlouhodobá podpora</strong> — jsme tu i po tvém příjezdu.</li>
          </ul>
        </section>
      </main>

      <aside class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow">
          <h4 class="font-semibold mb-2">Rychlé kroky</h4>
          <p class="text-gray-600 mb-2">Domluv konzultaci a začni plánovat svůj odjezd s jasnými kroky.</p>
          <a href="{{ route('contact') }}" class="inline-block px-4 py-2 bg-[color:var(--color-emerald)] text-white rounded">Domluv konzultaci</a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
          <h4 class="font-semibold mb-2">Co tě zajímá</h4>
          <p class="text-gray-600">Máte otázky ohledně studia, pojištění nebo ubytování? Podívejte se na <a href="{{ route('faq') }}" class="text-[color:var(--color-emerald)]">nejčastější dotazy</a> nebo nám napište.</p>
        </div>

        {{-- <div class="bg-white p-6 rounded-lg shadow">
          <h4 class="font-semibold mb-2">Balíčky</h4>
          <p class="text-gray-600">START — vedení krok za krokem<br>PREMIUM — kompletní servis</p>
        </div> --}}
      </aside>
    </div>
  </div>
  @include('components.cta')
@endsection



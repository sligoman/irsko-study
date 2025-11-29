@extends('layouts.app')

@section('title', 'Služby - Irsko Study')
@section('meta_description', 'Naše služby zahrnují poradentství při přihláškách, pomoc s ubytováním a podpůrné služby po příjezdu do Irska. Podívejte se, jak vám můžeme pomoci.')

@section('content')
  <div class="max-w-6xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <main class="lg:col-span-2">
        <h1 class="text-3xl font-bold mb-4">Naše služby</h1>
        <p class="text-gray-700 mb-6">Na cestě za studiem v Irsku nejsi sám. Pomůžeme ti krok za krokem — od prvotní konzultace, přes výběr školy a přihlášky, až po praktickou podporu po příjezdu.</p>

        <section class="mb-8 bg-gray-50 p-6 rounded-lg">
          <h2 class="text-2xl font-semibold mb-3">Jak probíhá spolupráce krok za krokem</h2>

          <p class="text-gray-700 mb-4">Na cestě za studiem v Irsku nejsi sám. Pomůžeme ti krok za krokem – od prvotní konzultace, přes výběr školy a přihlášky, až po praktickou podporu po příjezdu. S námi máš jistotu, že nic nepřehlédneš a všechno proběhne hladce. Každý krok je navržen tak, aby byl srozumitelný, přehledný a aby ti pomohl udělat nejlepší rozhodnutí pro tvou budoucnost.</p>

          <div class="space-y-6 text-gray-700">
            <div>
              <h3 class="text-xl font-semibold">Úvodní konzultace — poznáváme se a plánujeme</h3>
              <p class="mt-2">Na začátku je důležité zjistit, co přesně od studia v Irsku očekáváš. Probereme tvé akademické výsledky, zájmy a osobní cíle. Vysvětlíme ti specifika irského vysokého školství, jak fungují přijímací procesy a jaké možnosti studia existují.</p>
              <ul class="list-disc list-inside mt-2 text-gray-700">
                <li>Společně nastavíme realistický plán dalších kroků, aby ses mohl připravovat bez zbytečného stresu.</li>
                <li>Sdílíme své vlastní zkušenosti a tipy, které ti ušetří čas i starosti.</li>
              </ul>
            </div>

            <div>
              <h3 class="text-xl font-semibold">Výběr oboru a univerzity</h3>
              <p class="mt-2">Pomůžeme ti vybrat směr, který odpovídá tvým cílům i životnímu stylu. Poradíme s výběrem oboru, který tě bude bavit a zároveň otevírá zajímavé možnosti pro budoucí kariéru.</p>
              <p class="mt-2">Zohledníme lokalitu univerzity, náročnost studia a další faktory, které mohou ovlivnit tvůj každodenní život. Probereme specifické požadavky škol a oborů a připravíme tě na všechny administrativní kroky.</p>
            </div>

            <div>
              <h3 class="text-xl font-semibold">Překlady a příprava dokumentů</h3>
              <p class="mt-2">Správné dokumenty jsou základ úspěšné přihlášky. Pomůžeme ti s přepisy, oficiálními překlady a doporučujícími dopisy a poradíme, jak vybrat vhodný jazykový test.</p>
              <p class="mt-2">Ujistíme se, že všechny dokumenty odpovídají požadavkům univerzit, aby tvá přihláška byla kompletní a bez chyb.</p>
            </div>

            <div>
              <h3 class="text-xl font-semibold">Vyplnění přihlášky a formality</h3>
              <p class="mt-2">Podání přihlášky bývá často komplikované, ale s námi máš jistotu. Vytvoříme účet na portálech univerzit a pomůžeme ti s vyplněním všech údajů.</p>
              <p class="mt-2">Zkontrolujeme termíny, poplatky a specifické požadavky jednotlivých škol. Poradíme s žádostí o stipendia a granty, aby studium bylo co nejdostupnější.</p>
            </div>

            <div>
              <h3 class="text-xl font-semibold">Praktická příprava před odjezdem</h3>
              <p class="mt-2">Než vyrazíš do Irska, připravíme tě na život tam. Poradíme s pojištěním, ubytováním a plánem cesty a sdílíme tipy od studentů s reálnými zkušenostmi – od orientace ve městě, přes dopravu, až po praktické triky pro každodenní život.</p>
              <p class="mt-2">Pomůžeme ti s přípravou na první týdny v zahraničí, aby ses cítil bezpečně a sebevědomě.</p>
            </div>

            <div>
              <h3 class="text-xl font-semibold">Podpora po příjezdu</h3>
              <p class="mt-2">Po příjezdu nejsi sám ani na chvíli. Osobně se setkáme a poradíme ti, jak se rychle zorientovat ve městě a univerzitním kampusu.</p>
              <p class="mt-2">Budeme k dispozici během prvních měsíců, pokud se objeví otázky, problémy nebo potřebuješ tipy, jak zvládnout studium i život v Irsku.</p>
            </div>
          </div>
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



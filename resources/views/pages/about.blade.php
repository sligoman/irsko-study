@extends('layouts.app')

@section('title', 'O nás — IrskoStudy')

@section('content')
  <div class="max-w-6xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
      <div class="md:col-span-2">
        <h1 class="text-3xl font-bold mb-4">O nás</h1>

        <p class="text-gray-700 mb-4">Pomáháme českým a slovenským studentům splnit sen o studiu v Irsku. Studium v Irsku pro nás není jen téma — je to naše vlastní zkušenost a každodenní realita.</p>

        <h2 class="text-xl font-semibold mt-6 mb-2">Kdo jsme a proč právě Irsko</h2>
        <p class="text-gray-600 mb-4">V Irsku žijeme, studovali jsme zde, a proto dokonale rozumíme systému, kultuře i výzvám, které studenty čekají. Na rozdíl od agentur, které se snaží pokrýt celý svět, se specializujeme pouze na Irsko — díky tomu dáváme nejaktuálnější a nejkvalitnější poradenství.</p>

        <h2 class="text-xl font-semibold mt-6 mb-2">Naše filozofie</h2>
        <p class="text-gray-600 mb-4">Nejsme klasická agentura — jsme průvodci. Neslibujeme nemožné, ale nabízíme jistotu, zkušenost a realistický přístup. Pomůžeme ti pochopit celý proces, připravíme jasné kroky a zůstaneme s tebou v kontaktu i po příjezdu.</p>

        <h2 class="text-xl font-semibold mt-6 mb-2">Naše mise</h2>
        <p class="text-gray-600 mb-6">Pomáhat českým a slovenským studentům otevřít dveře ke kvalitnímu vzdělání v Irsku — lidsky, osobně a s hlubokou znalostí prostředí, ve kterém sami žijeme.</p>

        <div class="bg-white p-6 rounded-lg shadow">
          <h3 class="font-semibold mb-2">Domluvte si konzultaci</h3>
          <p class="text-gray-600 mb-4">Každé velké rozhodnutí začíná rozhovorem. Nabízíme nezávaznou konzultaci, kde zjistíme tvé cíle, vysvětlíme fungování přihlášek a doporučíme konkrétní první kroky.</p>
          <a href="{{ route('contact') }}" class="inline-block mt-2 px-4 py-2 bg-[color:var(--color-emerald)] text-white rounded">Rezervovat konzultaci</a>
        </div>
      </div>

      <aside class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow">
          <h4 class="font-semibold mb-2">Náš tým</h4>
          <ul class="text-gray-600">
            <li class="mb-2"><strong>David Fiala</strong> — Zakladatel IrskoStudy.cz</li>
            <li class="mb-2"><strong>Michal Chupík</strong> — Poradce pro studium a přihlášky</li>
          </ul>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
          <h4 class="font-semibold mb-2">Kontakt</h4>
          <p class="text-gray-600">E-mail: <em>[doplnit]</em><br>Telefon: <em>[doplnit]</em><br>Adresa: <em>[doplnit]</em></p>
          <div class="mt-4">
            <a href="#" class="text-[color:var(--color-emerald)] mr-3">Instagram</a>
            <a href="#" class="text-[color:var(--color-emerald)]">YouTube</a>
          </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
          <h4 class="font-semibold mb-2">Proč my</h4>
          <p class="text-gray-600">Zůstáváme s tebou i po příjezdu — pomůžeme s ubytováním, bankou a orientací ve městě. Nabízíme osobní přístup a reálné zkušenosti z Irska.</p>
        </div>
      </aside>
    </div>
  </div>
  @include('components.cta')
@endsection

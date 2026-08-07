@extends('layouts.app')

@section('title', 'Proč studovat v Irsku — Irsko Study')
@section('meta_description', 'Proč studovat v Irsku — informace o výhodách irského vzdělávacího systému, životních podmínkách a možnostech po dokončení studia.')

@section('content')
  <div class="layout-container layout-section">
    <h1 class="type-display-xl mb-4 text-brand-dark-green">Vysoké školy v Irsku — studium, které tě posune dál</h1>

    <p class="type-text-md mb-6 text-brand-dark-green">Irsko kombinuje tradiční univerzitní systém s moderním přístupem ke vzdělávání. Důraz je kladen na kritické myšlení, samostatnost a praktické zkušenosti — to vše v anglickém jazyce a v přátelském mezinárodním prostředí.</p>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="type-display-sm mb-2 text-brand-dark-green">Vzdělání s reálnou hodnotou</h3>
        <p class="text-gray-600">Irské univerzity nabízejí moderní kurikula, malé třídy a silné propojení s praxí. Studium přináší reálné dovednosti a přípravu pro mezinárodní kariéru.</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="type-display-sm mb-2 text-brand-dark-green">Studium bez školného pro studenty z EU</h3>
        <p class="text-gray-600">Díky Free Fees Initiative mohou studenti z EU studovat bez klasického školného (platí se obvykle pouze student contribution fee ~3 000–3 500 €). To dělá Irsko dostupnou volbou pro kvalitní anglicky vedené studium.</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="type-display-sm mb-2 text-brand-dark-green">Možnost práce během studia</h3>
        <p class="text-gray-600">Studenti z EU mohou pracovat až 20 hodin týdně během semestru a více o prázdninách. To pomáhá zlepšit jazyk, získat zkušenosti a snížit náklady na život.</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="type-display-sm mb-2 text-brand-dark-green">Univerzity světové úrovně</h3>
        <p class="text-gray-600">Trinity College Dublin, University College Dublin, Cork nebo Limerick patří k nejuznávanějším institucím. Studenti mají přístup k moderním kampusům, laboratořím, výzkumu a mezinárodním partnerstvím.</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="type-display-sm mb-2 text-brand-dark-green">Studium v angličtině — jazyk bez bariér</h3>
        <p class="text-gray-600">Studium v anglicky mluvícím prostředí přirozeně zlepší tvoji jazykovou úroveň a otevírá cestu k pracovním příležitostem po celém světě.</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="type-display-sm mb-2 text-brand-dark-green">Bezpečná země s přátelskými lidmi</h3>
        <p class="text-gray-600">Irsko je známé svou pohostinností a otevřeností. Pro studenty z Česka a Slovenska je to bezpečné prostředí s atmosférou, kde se snadno zapojíš do místní komunity.</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="type-display-sm mb-2 text-brand-dark-green">Výborná dostupnost a jednoduché přestěhování</h3>
        <p class="text-gray-600">Přímé lety z regionu a členství v EU znamenají jednoduchý přesun bez víz. Po příjezdu si vyřídíš PPS number a můžeš se soustředit na studium a práci.</p>
      </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow mb-8">
      <h3 class="type-display-sm mb-2 text-brand-dark-green">Perspektiva po studiu</h3>
      <p class="text-gray-600">Irsko má silný trh práce v IT, farmacii, financích a dalších sektorech. Mnoho absolventů zde najde uplatnění nebo získá pracovní zkušenost vedoucí k mezinárodní kariéře.</p>
    </div>

    <div class="mb-8">
      <h3 class="type-display-md mb-3 text-brand-dark-green">Shrnutí — proč je Irsko skvělou volbou</h3>
      <ul class="list-disc list-inside text-gray-700 space-y-2">
        <li>Kvalitní univerzity s mezinárodním uznáním</li>
        <li>Studium výhradně v angličtině</li>
        <li>Bez školného pro studenty z EU</li>
        <li>Možnost práce při studiu</li>
        <li>Bezpečné, přátelské a otevřené prostředí</li>
        <li>Snadná dostupnost z Česka i Slovenska</li>
        <li>Skvělé pracovní možnosti po absolvování</li>
      </ul>
    </div>

    <div class="text-center">
      <a href="{{ route('contact') }}" class="inline-block px-6 py-3 bg-[color:var(--color-brand-light-green)] text-white rounded">Zjisti, jak můžeš studovat v Irsku</a>
    </div>

  </div>
  @include('components.cta')
@endsection

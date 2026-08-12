@extends('layouts.app')

@section('title', 'Proč studovat v Irsku — Irsko Study')
@section('meta_description', 'Proč studovat v Irsku — informace o výhodách irského vzdělávacího systému, životních podmínkách a možnostech po dokončení studia.')

@section('content')
  <main data-redesign-page="why-ireland" class="bg-base-white">
    <x-subpage.hero
      eyebrow="Proč Irsko"
      title="Studium, které tě"
      accent="posune dál"
      text="Irsko kombinuje tradiční univerzitní systém s moderním přístupem ke vzdělávání. Důraz je kladen na kritické myšlení, samostatnost a praktické zkušenosti v angličtině a v přátelském mezinárodním prostředí."
      :stats="[
        ['value' => 'EU', 'label' => 'jednoduchý přesun bez víz'],
        ['value' => '20 h', 'label' => 'práce týdně během semestru'],
        ['value' => 'EN', 'label' => 'studium i běžný život v angličtině'],
      ]"
    />

    <section data-redesign-section="why-ireland-benefits" class="home-section">
      <div class="layout-container">
        <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-end">
          <div>
            <p class="type-text-md-semibold text-brand-orange">Hlavní výhody</p>
            <h2 class="type-display-lg mt-2 text-brand-dark-green">Proč dává Irsko smysl pro české a slovenské studenty</h2>
          </div>
          <p class="type-text-lg text-brand-dark-green">Kvalitní školy, angličtina, dostupnost z domova a možnost pracovat při studiu vytváří kombinaci, která studentům pomáhá růst akademicky i osobně.</p>
        </div>

        <div class="mt-12 grid gap-5 md:grid-cols-3">
          <x-subpage.feature-card title="Vzdělání s reálnou hodnotou" text="Irské univerzity nabízejí moderní kurikula, menší třídy a silné propojení s praxí. Studium přináší reálné dovednosti pro mezinárodní kariéru." />
          <x-subpage.feature-card variant="dark" title="Studium bez školného pro studenty z EU" text="Díky Free Fees Initiative mohou studenti z EU studovat bez klasického školného. Obvykle se platí jen student contribution fee okolo 3 000 až 3 500 EUR." />
          <x-subpage.feature-card title="Možnost práce během studia" text="Studenti z EU mohou pracovat až 20 hodin týdně během semestru a více o prázdninách. To pomáhá s jazykem, zkušenostmi i náklady." />
        </div>
      </div>
    </section>

    <section class="home-section">
      <div class="layout-container">
        <div class="grid gap-5 lg:grid-cols-2">
          <x-subpage.feature-card title="Univerzity světové úrovně" text="Trinity College Dublin, University College Dublin, Cork nebo Limerick patří k uznávaným institucím s moderními kampusy, výzkumem a mezinárodními partnery." />
          <x-subpage.feature-card title="Studium v angličtině bez bariér" text="Studium v anglicky mluvícím prostředí přirozeně zlepší jazykovou úroveň a otevře cestu k pracovním příležitostem po celém světě." />
          <x-subpage.feature-card title="Bezpečná země s přátelskými lidmi" text="Irsko je známé pohostinností a otevřeností. Pro studenty z Česka a Slovenska nabízí bezpečné prostředí, kde se snadno zapojí do komunity." />
          <x-subpage.feature-card title="Výborná dostupnost a jednoduché přestěhování" text="Přímé lety z regionu a členství v EU znamenají jednodušší přesun bez víz. Po příjezdu si vyřídíš PPS number a můžeš se soustředit na studium." />
        </div>
      </div>
    </section>

    <section class="home-section">
      <div class="layout-container">
        <div class="grid overflow-hidden rounded-[16px] bg-brand-dark-green text-white lg:grid-cols-[0.9fr_1.1fr]">
          <div class="p-6 md:p-10 lg:p-12">
            <p class="type-text-md-semibold text-brand-orange">Perspektiva po studiu</p>
            <h2 class="type-display-lg mt-2 text-white">Irsko je dobrý start pro mezinárodní kariéru</h2>
          </div>
          <div class="bg-white/10 p-6 md:p-10 lg:p-12">
            <p class="type-text-lg text-white/85">Irsko má silný trh práce v IT, farmacii, financích a dalších sektorech. Mnoho absolventů zde najde uplatnění nebo získá pracovní zkušenost, která jim pomůže v další mezinárodní kariéře.</p>
          </div>
        </div>
      </div>
    </section>

    <section data-redesign-section="why-ireland-summary" class="home-section pb-20">
      <div class="layout-container">
        <x-subpage.summary-list
          heading="Shrnutí: proč je Irsko skvělou volbou"
          :items="[
            'Kvalitní univerzity s mezinárodním uznáním',
            'Studium výhradně v angličtině',
            'Bez školného pro studenty z EU',
            'Možnost práce při studiu',
            'Bezpečné, přátelské a otevřené prostředí',
            'Snadná dostupnost z Česka i Slovenska',
            'Skvělé pracovní možnosti po absolvování',
          ]"
          :button="['label' => 'Zjisti, jak můžeš studovat v Irsku', 'href' => route('contact')]"
        />
      </div>
    </section>

    @include('components.cta')
  </main>
@endsection

<section data-redesign-section="steps-timeline" class="home-section">
  <div class="layout-container">
    <div class="grid gap-10 lg:grid-cols-[0.8fr_1.2fr] lg:items-start">
      <div>
        <p class="type-text-md-semibold text-brand-orange">Postup spolupráce</p>
        <h2 class="type-display-lg mt-2 text-brand-dark-green">Jak to funguje krok za krokem</h2>
        <p class="type-text-lg mt-5 text-brand-dark-green">Z komplexního rozhodnutí uděláme jasný plán. Každý krok víš dopředu a máš u něj konkrétní podporu.</p>
      </div>

      <div class="grid gap-8 md:grid-cols-2 md:gap-x-6 md:gap-y-10">
        @foreach([
          ['number' => '01', 'title' => 'První konzultace', 'text' => 'Probereme cíle, obor, očekávání a připravíme osobní studijní plán.'],
          ['number' => '02', 'title' => 'Výběr školy', 'text' => 'Vybereme univerzitu a program podle akademických možností, lokality i studentského života.'],
          ['number' => '03', 'title' => 'Přihláška', 'text' => 'Pomůžeme s dokumenty, termíny, doporučeními a kontrolou přihlášky před odesláním.'],
          ['number' => '04', 'title' => 'Příprava odjezdu', 'text' => 'Vysvětlíme ubytování, pojištění, dopravu a praktické kroky pro hladký start.'],
          ['number' => '05', 'title' => 'Podpora po příjezdu', 'text' => 'Po příjezdu pomůžeme s orientací, bankou, ubytováním a každodenními otázkami.'],
          ['number' => '06', 'title' => 'Adaptace', 'text' => 'Pomůžeme zapojit se do studentské komunity a využít příležitosti během studia.'],
        ] as $step)
          <article class="pt-1">
            <div class="flex h-[72px] items-end gap-2">
              <div class="type-decorative text-[60px] leading-[72px] text-brand-light-green">{{ $step['number'] }}</div>
              <div class="mb-[15px] h-px flex-1 border-t border-dashed border-brand-light-green/70"></div>
            </div>
            <h3 class="type-display-xs mt-2 text-brand-dark-green">{{ $step['title'] }}</h3>
            <p class="type-text-md mt-2 text-brand-dark-green">{{ $step['text'] }}</p>
          </article>
        @endforeach
      </div>
    </div>
  </div>
</section>

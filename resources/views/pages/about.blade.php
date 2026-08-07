@extends('layouts.app')

@section('title', 'O nás — Irsko Study')
@section('meta_description', 'O nás — kdo jsme, proč se specializujeme na Irsko a jak pomáháme studentům z ČR a SK s přestupem na irské vysoké školy.')

@section('content')
  <div class="layout-container layout-section">
    {{-- Hero / intro --}}
    <header class="mb-10 text-center md:text-left">
      <h1 class="type-display-xl mb-3 text-brand-dark-green">O nás</h1>
      <p class="type-text-lg max-w-4xl text-brand-dark-green">Pomáháme českým a slovenským studentům splnit sen o studiu v Irsku. Studium v Irsku pro nás není jen téma — je to naše vlastní zkušenost a každodenní realita.</p>
    </header>

      <h2 class="type-display-md mb-3 text-brand-dark-green">Kdo jsme a proč právě Irsko</h2>
      <div class="prose prose-lg prose-slate text-center md:text-left">
      <p>V Irsku žijeme, studovali jsme zde, a proto dokonale rozumíme systému, kultuře i výzvám, které studenty čekají. Na rozdíl od agentur, které se snaží pokrýt celý svět, se specializujeme pouze na Irsko — díky tomu dáváme nejaktuálnější a nejkvalitnější poradenství.</p>

      <h3>Naše filozofie</h3>
      <p>Nejsme klasická agentura — jsme průvodci. Neslibujeme nemožné, ale nabízíme jistotu, zkušenost a realistický přístup. Pomůžeme ti pochopit celý proces, připravíme jasné kroky a zůstaneme s tebou v kontaktu i po příjezdu.</p>

      <h3>Naše mise</h3>
      <p>Pomáhat českým a slovenským studentům otevřít dveře ke kvalitnímu vzdělání v Irsku — lidsky, osobně a s hlubokou znalostí prostředí, ve kterém sami žijeme.</p>

      <h3>Proč my</h3>
      <p>Zůstáváme s tebou i po příjezdu — pomůžeme s ubytováním, bankou a orientací ve městě. Nabízíme osobní přístup a reálné zkušenosti z Irska.</p>
      </div>

      {{-- <hr class="my-8" /> --}}

      {{-- Team cards --}}
      <h2 class="type-display-md mb-3 mt-8 text-brand-dark-green">Náš tým</h2>
      <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <article class="bg-white p-6 rounded-lg shadow">
          <div class="flex flex-col items-start gap-4">
            <div class="flex items-center justify-center text-xl font-semibold text-gray-500 md:h-96 w-full"><img src="{{ asset('img/team/david_fiala.jpg') }}" alt="David Fiala" class="rounded-sm h-full w-full object-cover" /></div>
            <div>
              <div class="text-sm text-gray-500">David Fiala (majitel)</div>
              <h4 class="text-lg font-semibold mt-1">Žiju v Irsku téměř 20 let, studoval jsem v USA, Německu a Irsku</h4>
            </div>
          </div>

          <div class="mt-4 text-gray-700 space-y-2 prose prose-lg prose-slate">
          <p>Na svých prvních výměnných kurzech jsem byl ve 14 letech, kdy mě máma poslala do Holandska a Belgie. V 16 jsem o prázdninách vyrazil do Berlína, kde jsem 2 týdny žil v německé rodině a docházel na jazykový kurz němčiny. A během stejného léta jsem ještě vyjel do Anglie a chodil tam na jazykovou školu na kurz angličtiny. Byly to pro mě neuvěřitelné zkušenosti. Vyzkoušel jsem si, jaké to je být sám v cizí zemi, poznal jsem nová prostředí, našel kamarády z jiných zemí a zjistil, jak to funguje v zahraničních rodinách. Dodalo mi to sebevědomí a samozřejmě i jazykovou výbavu.</p>
          <p>Následující rok jsem vyjel na půl roku studovat do USA. Bydlel jsem na Floridě u místní rodiny a chodil na  střední školu. Našel jsem si tam i brigádu na víkendy a vydělal první peníze. Odjížděl jsem se skvělou angličtinou, super zážitky a poznáním, že to není ta země, ve které bych chtěl jednou žít.</p>
          <p>Díky angličtině jsem brzy po maturitě našel brigádu v renomované CK, kde jsem po 4 roky organizoval incomingové zahraniční skupiny a kongresy.</p>
          <p>Během studií na právech jsem vycestoval na Erasmus do Drážďan a rok studoval na právnické fakultě. Ze začátku to bylo náročné kvůli odborné němčině, ale po pár měsících jsem se chytil. Začal jsem se zde i učit španělsky, abych se dorozuměl se Španěli, kteří nebyli v jazycích zrovna silní . Nemčinu jsem si natolik zlepšil, že jsem si našel práci překladatele v jedné místní firmě. Byl to nejen nejlepší rok mého života, ale též přelomový pro moje další životní kroky.</p>
          <p>Po návratu do Čech jsem se přihlásil, že se budu starat o studenty, kteří přijeli do ČR na Erasmus. Potkával jsem se tak se studenty z celého světa. Nejblíže jsem měl k lidem z Irska, zaujali mě svou přátelskostí a srdečností. </p>
          <p>A tak jsem po studiích, to mi bylo 25 let, odjel do Irska hledat práci. Už po třech dnech jsem byl na pohovoru v mezinárodní společnosti Ebay a vzali mě ve finále na lepší pozici, než jsem se hlásil. Pomohla mi k tomu skvělá angličtina a znalost němčiny. V rámci zaučení mě poslali na 3 týdny do Izraele na stáž. Byl jsem součástí německého týmu, který vedl Izraelec a já v rámci projektu vedl 5 lidí. Strávil jsem tam báječných 2,5 let. </p>
          <p>Během práce jsem rok studoval na Dublin Business School účetnictví a finance. Následně si ještě udělal certifikát na projektového manažera. Vše s cílem, abych se v Irsku mohl stát manažerem. </p>
          <p>Díky praxi z Ebay, studiím v Irskua jazykovým znalostem jsem získal práci v největší irské sázkové kanceláři Paddy Power, ve které jsem vedl celý španělský tým. Nutno podotknout, že s praxí pouze v ČR bych nikdy takovou práci nezískal.</p>
          <p>Po 3 letech v Paddy Power jsem se rozhodl, že začnu podnikat. Založil jsem se dvěma společníky firmu Praga Medica, která organizuje lékařskou péči v ČR pro cizince. </p>
          <p>Irsko mě učarovalo skvělými lidmi, nádhernou přírodou, možností chodit denně surfovat a pohodovou prací s vysokými platy…, takže jsem se tu usadil. Našel jsem si tu ženu, která pochází z Litvy, a máme spolu 2 děti. Ty mluví 3 jazyky. Chceme v Irsku zůstat také kvůli nim, aby měly angličtinu jako mateřský jazyk a měly jednodušší start v životě, než jsme měli my. </p>
          </div>
        </article>

        <article class="bg-white p-6 rounded-lg shadow">
          <div class="flex flex-col items-start gap-4">
            <div class="flex items-center justify-center text-xl font-semibold text-gray-500 md:h-96 w-full"><img src="{{ asset('img/team/michal_chupik.jpeg') }}" alt="Michal Chupík" class="rounded-sm h-full w-full object-cover" /></div>
            <div>
              <div class="text-sm text-gray-500">Michal Chupík (koordinátor)</div>
              <h4 class="text-lg font-semibold mt-1">Student TU Dublin — letecké technologie & koordinace</h4>
            </div>
          </div>

          <div class="mt-4 text-gray-700 space-y-3 prose prose-lg prose-slate">
            <p>Jmenuju se Michal Chupík, je mi 20 let a pocházím z Olomouce. Momentálně žiju a studuju v Dublinu, kde se věnuju technickému oboru letecké technologie na TU Dublin. Vybral jsem si Irsko, protože jsem studoval šestileté dvojjazyčné gymnázium Olomouc – Hejčín a chtěl jsem pokračovat ve studiu v angličtině, ale zároveň zůstat relativně blízko domovu. TU Dublin mě zaujala, protože nabízí přesně obor, který mě baví, a zároveň mi umožňuje poznávat nové prostředí a zemi.</p>

            <p>Můj první rok v Irsku je plný objevování. Musím si zvyknout na nový systém výuky, administrativu a každodenní život v Dublinu. Každý krok je pro mě zkušeností od hledání vhodného bydlení přes orientaci ve městě až po organizaci volného času. Rád poznávám nová místa a zemi, ve které žiju, takže každý víkend je pro mě příležitostí objevovat Dublin a jeho okolí i vzdálenější kouty Irska.</p>

            <p>Každé léto jezdím na brigádu do Holandska na tulipánovou farmu, kde pracuju, poznávám nové lidi a zároveň vydělávám peníze na studium a cestování. Tyto zkušenosti mi pomáhají chápat, jak náročné je skloubit studium, práci a život v cizí zemi, a díky tomu dokážu studentům poradit z vlastní zkušenosti.</p>

            <p>Miluju letadla, drony, technologie, dopravu a cestování a právě tuto vášeň přenáším i do své práce koordinátora. Vím, co je pro studenty při přihlašování a adaptaci na novou zemi důležité a proto chci pomáhat každému, kdo se rozhodne studovat v Irsku. Mým cílem je, aby studenti měli hladký start, mohli se soustředit na studium a zároveň poznávat novou kulturu a životní prostředí.</p>

            <p>Irsko pro mě není jen země studia, je to místo, kde můžu rozvíjet své zájmy, poznávat nové lidi a připravovat se na budoucí kariéru. Proto je pro mě důležité být tu pro studenty, kteří se chtějí vydat stejnou cestou, a podporovat je na každém kroku.</p>
          </div>
        </article>
      </section>

      {{-- Contact card --}}
      <section class="bg-brand-light-gray p-8 rounded-[12px]">
        <h3 class="type-display-sm mb-2 text-brand-dark-green">Kontakt</h3>
        <p class="text-gray-600 mb-4">Máte otázky? Napište nám nebo zavolejte — rádi poradíme.</p>
        <p class="text-gray-700">E-mail: <a href="mailto:{{ config('contacts.email') }}" class="text-[color:var(--color-brand-light-green)]">{{ config('contacts.email') }}</a><br>
        Mobil: <a href="tel:{{ config('contacts.mobile') }}" class="text-[color:var(--color-brand-light-green)]">{{ config('contacts.mobile') }}</a></p>
      </section>

    @include('components.cta')

    </div>

@endsection

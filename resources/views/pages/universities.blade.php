@extends('layouts.app')

@section('title', 'Vysoké školy - Irsko Study')
@section('meta_description', 'Přehled vybraných irských vysokých škol a programů vhodných pro studenty z ČR a SK. Najděte univerzitu, která vám sedne.')

@section('content')
  <main data-redesign-page="universities" class="bg-base-white">
    <x-subpage.hero
      eyebrow="Vysoké školy"
      title="Najdi univerzitu, která"
      accent="sedne právě tobě"
      text="Studium v Irsku patří k nejlepším investicím do budoucnosti. Irské univerzity nabízejí světovou úroveň vzdělání, silné propojení s praxí a přátelské prostředí pro studenty z ČR a SK."
      :stats="[
        ['value' => 'TOP', 'label' => 'vybrané irské univerzity'],
        ['value' => 'EN', 'label' => 'programy v angličtině'],
        ['value' => 'A-Z', 'label' => 'pomoc s výběrem i přihláškou'],
      ]"
    />

    <section data-redesign-section="universities-grid" class="home-section">
      <div class="layout-container">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
          <div>
            <p class="type-text-md-semibold text-brand-orange">Přehled škol</p>
            <h2 class="type-display-lg mt-2 text-brand-dark-green">Top univerzity v Irsku</h2>
          </div>
          <p class="type-text-lg max-w-[620px] text-brand-dark-green">Níže najdeš přehled významných škol a odkazy na kurzy. Pokud si nejsi jistý výběrem, pomůžeme ti zúžit možnosti podle profilu a cílů.</p>
        </div>

        @if(isset($schools) && $schools->count())
          <div class="mt-10 grid gap-6 lg:grid-cols-3">
            @foreach($schools as $school)
              @if($school->courses_count === 0)
                @continue
              @endif

              @php
                $imgSrc = null;
                if (!empty($school->school_id)) {
                    $candidate = public_path('img/blog/large/uni-' . $school->school_id . '.jpg');
                    if (file_exists($candidate)) {
                        $imgSrc = asset('img/blog/large/uni-' . $school->school_id . '.jpg');
                    }
                }

                $schoolDesc = $school->description_cs ?? $school->description_en ?? null;
                $schoolSlug = $school->url ?? $school->school_id ?? $school->id;
              @endphp

              <x-subpage.school-card
                :school="$school"
                :image="$imgSrc"
                :description="$schoolDesc"
                :courses-href="route('finder.courses', ['school' => $school->school_id ?? $school->id])"
                :profile-href="route('finder.school.show', $schoolSlug)"
              />
            @endforeach
          </div>
        @else
          <div class="mt-10 rounded-[16px] bg-brand-light-gray p-8">
            <p class="type-text-lg text-brand-dark-green">Zatím zde nejsou žádné školy k zobrazení.</p>
          </div>
        @endif
      </div>
    </section>

    <section data-redesign-section="universities-guide" class="home-section">
      <div class="layout-container">
        <div class="grid gap-5 lg:grid-cols-3">
          <x-subpage.feature-card title="Jak si vybrat správný obor" text="Výběr oboru závisí na tvých cílech a kariérních záměrech. Zvaž, jaké předměty tě baví, jaké jsou tvoje silné stránky a kde jsou reálné pracovní příležitosti." />
          <x-subpage.feature-card variant="dark" title="Požadavky na české studenty" text="Typicky potřebuješ maturitní vysvědčení přeložené do angličtiny, přehled známek, přihlášku a někdy potvrzení úrovně angličtiny nebo motivační dopis." />
          <x-subpage.feature-card title="Přihláška bez zbytečných chyb" text="Je důležité dodržet termíny, správně seřadit priority a dodat všechny dokumenty. S tím ti pomůžeme krok za krokem." />
        </div>
      </div>
    </section>

    <section class="home-section pb-20">
      <div class="layout-container">
        <x-subpage.summary-list
          heading="Co spolu vyřešíme při výběru školy"
          :items="[
            'Výběr univerzity podle oboru, města a životních nákladů',
            'Kontrolu vstupních požadavků a dokumentů',
            'Doporučení programů podle tvého profilu',
            'Termíny a priority přihlášek',
            'Reálné možnosti práce a života během studia',
            'Další kroky po přijetí na školu',
          ]"
          :button="['label' => 'Domluv konzultaci', 'href' => route('contact')]"
        />
      </div>
    </section>

    @include('components.cta')
  </main>
@endsection

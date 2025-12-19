@extends('layouts.app')

@section('title', 'Vysoké školy - Irsko Study')
@section('meta_description', 'Přehled vybraných irských vysokých škol a programů vhodných pro studenty z ČR a SK. Najděte univerzitu, která vám sedne.')

@section('content')
  <div class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-4">Přehled top univerzit v Irsku</h1>

    <p class="text-gray-700 mb-6">Studium v Irsku patří k nejlepším investicím do budoucnosti. Irské univerzity nabízejí světovou úroveň vzdělání a silné propojení s praxí. Níže najdeš přehled nejvýznamnějších škol a tipy, jak si vybrat obor.</p>

    @if(isset($schools) && $schools->count())
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 items-stretch">
        @foreach($schools as $school)

          @if($school->courses_count === 0)
            @continue
          @endif

          @php
            // Determine image path using the same pattern as course page: img/blog/large/uni-{school_id}.jpg
            $imgSrc = null;
            if (!empty($school->school_id)) {
                $candidate = public_path('img/blog/large/uni-' . $school->school_id . '.jpg');
                if (file_exists($candidate)) {
                    $imgSrc = asset('img/blog/large/uni-' . $school->school_id . '.jpg');
                }
            }

            $schoolDesc = $school->description_cs ?? $school->description_en ?? null;
          @endphp

          <div class="bg-white p-6 rounded-lg shadow flex flex-col h-full">
            @if($imgSrc)
              <div class="mb-4 overflow-hidden rounded">
                <img src="{{ $imgSrc }}" alt="{{ $school->name }}" class="w-full h-36 object-cover">
              </div>
            @endif

            <h3 class="font-semibold mb-2">{{ $school->name }}@if($school->acronym) ({{ $school->acronym }})@endif</h3>

            @if(!empty($schoolDesc))
              <p class="text-gray-700 mb-3">{{ \Illuminate\Support\Str::limit(strip_tags($schoolDesc), 180) }}</p>
            @endif

            @if(!empty($school->link))
              <p class="text-gray-600 mb-2"><a href="{{ $school->link }}" target="_blank" rel="noopener" class="hover:underline">Oficiální web školy</a></p>
            @endif

            <p class="text-gray-600 mb-4">Počet programů: <strong>{{ $school->courses_count ?? 0 }}</strong></p>

            @php
              $schoolSlug = $school->url ?? $school->school_id ?? $school->id;
            @endphp
            <div class="mt-4 flex items-center gap-3 mt-auto">
              <a href="{{ route('finder.courses', ['school' => $school->school_id ?? $school->id]) }}" class="inline-block px-4 py-2 bg-emerald-600 text-white rounded">Prohlédnout kurzy</a>
              <a href="{{ route('finder.school.show', $schoolSlug) }}" class="inline-block px-3 py-2 border border-gray-200 rounded text-sm text-gray-700 hover:bg-gray-50">Profil školy</a>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="mb-8">
        <p class="text-gray-700">Zatím zde nejsou žádné školy k zobrazení.</p>
      </div>
    @endif

    <section class="mb-8">
      <h2 class="text-2xl font-semibold mb-3">Jak si vybrat správný obor</h2>
      <p class="text-gray-700">Výběr oboru závisí na tvých cílech a kariérních záměrech. Zvaž, jaké předměty tě baví, jaké jsou tvoje silné stránky a jaké obory mají reálné pracovní příležitosti po studiu. Pokud si nevíš rady, poradíme s výběrem na základě tvého profilu.</p>
    </section>

    <section class="mb-8 bg-gray-50 p-6 rounded-lg">
      <h2 class="text-2xl font-semibold mb-3">Požadavky na české studenty</h2>
      <ul class="list-disc list-inside text-gray-700 space-y-2">
        <li>Maturitní vysvědčení přeložené do angličtiny (úřední překlad dle požadavků školy).</li>
        <li>Potvrzení o znalosti angličtiny (IELTS, Duolingo, Cambridge), pokud je vyžadováno.</li>
        <li>Vyplněná přihláška a přehled známek z posledních let.</li>
        <li>U vybraných programů může být požadován motivační dopis nebo doporučení.</li>
      </ul>
    </section>

    <section class="mb-8">
      <h2 class="text-2xl font-semibold mb-3">Přihláška</h2>
      <p class="text-gray-700">Je důležité dodržet termíny, správně seřadit priority a dodat všechny požadované dokumenty. S tím ti pomůžeme krok za krokem.</p>
    </section>

    <div class="bg-white p-6 rounded-lg shadow mb-8">
      <h3 class="font-semibold mb-2">Chceš studovat v angličtině a získat titul uznávaný v Evropě?</h3>
      <p class="text-gray-600">Irsko nabízí nejen špičkové univerzity, ale i přátelské prostředí a reálné pracovní příležitosti. Vybereme školu, která sedne právě tobě a pomůžeme s celým procesem přihlášky.</p>
    </div>

    <div class="text-center">
      <a href="{{ route('contact') }}" class="inline-block px-6 py-3 bg-[color:var(--color-emerald)] text-white rounded">Domluv konzultaci</a>
    </div>

  </div>
  @include('components.cta')
@endsection

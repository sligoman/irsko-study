@extends('layouts.app')

@section('title', 'Vysoké školy - IrskoStudy')

@section('content')
  <div class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-4">Přehled top univerzit v Irsku</h1>

    <p class="text-gray-700 mb-6">Studium v Irsku patří k nejlepším investicím do budoucnosti. Irské univerzity nabízejí světovou úroveň vzdělání a silné propojení s praxí. Níže najdeš přehled nejvýznamnějších škol a tipy, jak si vybrat obor.</p>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="font-semibold mb-2">Trinity College Dublin (TCD)</h3>
        <p class="text-gray-600">Nejstarší a nejprestižnější irská univerzita se silným výzkumným zázemím. Ideální pro humanitní i přírodní vědy, IT a podnikání.</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="font-semibold mb-2">University College Dublin (UCD)</h3>
        <p class="text-gray-600">Jedna z největších univerzit v zemi s širokou nabídkou oborů, moderním kampusem a velkou mezinárodní komunitou.</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="font-semibold mb-2">University College Cork (UCC)</h3>
        <p class="text-gray-600">Silné zaměření na výzkum a udržitelnost; skvělé prostředí pro studenty hledající vyvážený studentský život.</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="font-semibold mb-2">University of Galway</h3>
        <p class="text-gray-600">Univerzita na západním pobřeží s kvalitními programy v medicíně, technologiích a humanitních oborech.</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="font-semibold mb-2">Dublin City University (DCU)</h3>
        <p class="text-gray-600">Moderní univerzita se zaměřením na inovace, technologie a média; silné propojení s průmyslem.</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="font-semibold mb-2">Technological University Dublin (TUD)</h3>
        <p class="text-gray-600">Technická a prakticky orientovaná výuka — skvělá volba pro technické a designové obory.</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="font-semibold mb-2">University of Limerick (UL)</h3>
        <p class="text-gray-600">Důraz na propojení s praxí, často také praktické stáže během studia a silné vazby na průmysl.</p>
      </div>
    </div>

    <section class="mb-8">
      <h2 class="text-2xl font-semibold mb-3">Jak si vybrat správný obor</h2>
      <p class="text-gray-700">Výběr oboru závisí na tvých cílech a kariérních záměrech. Zvaž, jaké předměty tě baví, jaké jsou tvoje silné stránky a jaké obory mají reálné pracovní příležitosti po studiu. Pokud si nevíš rady, poradíme s výběrem na základě tvého profilu.</p>
    </section>

    <section class="mb-8 bg-gray-50 p-6 rounded-lg">
      <h2 class="text-2xl font-semibold mb-3">Požadavky na české studenty</h2>
      <ul class="list-disc list-inside text-gray-700 space-y-2">
        <li>Maturitní vysvědčení přeložené do angličtiny (úřední překlad dle požadavků školy).</li>
        <li>Potvrzení o znalosti angličtiny (IELTS, Duolingo, Cambridge), pokud je vyžadováno.</li>
        <li>Vyplněná přihláška (často přes CAO) a přehled známek z posledních let.</li>
        <li>U vybraných programů může být požadován motivační dopis nebo doporučení.</li>
      </ul>
    </section>

    <section class="mb-8">
      <h2 class="text-2xl font-semibold mb-3">CAO a přihlášky</h2>
      <p class="text-gray-700">Mnoho irských programů se přihlašuje prostřednictvím CAO (Central Applications Office). Je důležité dodržet termíny, správně seřadit priority a dodat všechny požadované dokumenty. S tím ti pomůžeme krok za krokem.</p>
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

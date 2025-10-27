@extends('layouts.app')

@section('title', 'Proč Irsko - IrskoStudy')

@section('content')
  <div class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold mb-4">Proč studovat v Irsku</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white p-6 rounded shadow">Kvalitní vzdělání a příležitosti po studiu.</div>
      <div class="bg-white p-6 rounded shadow">Angličtina, mezinárodní prostředí a přátelská atmosféra.</div>
    </div>
  </div>
@endsection
@extends('layouts.app')

@section('title', 'Proč Irsko — IrskoStudy')

@section('content')
  <h1 class="text-2xl font-bold">Proč studovat v Irsku</h1>
  <div class="mt-4 grid md:grid-cols-2 gap-4">
    <div class="p-4 border rounded">Kvalitní vzdělání</div>
    <div class="p-4 border rounded">Práce při studiu</div>
    <div class="p-4 border rounded">Bezpečné prostředí</div>
    <div class="p-4 border rounded">Příležitosti po studiu</div>
  </div>
  @include('components.cta')
@endsection

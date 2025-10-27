@extends('layouts.app')

@section('title', 'Služby - IrskoStudy')

@section('content')
  <div class="max-w-5xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold mb-4">S čím pomáháme</h1>
    <p>Od výběru školy až po první den v Irsku — nabízíme balíčky START a PREMIUM.</p>
    @include('components.steps')
  </div>
@endsection
@extends('layouts.app')

@section('title', 'Služby — IrskoStudy')

@section('content')
  <h1 class="text-2xl font-bold">Naše služby</h1>
  <div class="mt-4">
    <h3 class="font-semibold">START</h3>
    <p>Základní vedení a poradenství.</p>
    <h3 class="mt-4 font-semibold">PREMIUM</h3>
    <p>Kompletní servis od A do Z.</p>
  </div>
  @include('components.cta')
@endsection

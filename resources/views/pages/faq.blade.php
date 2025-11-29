@extends('layouts.app')

@section('title', 'FAQ — Irsko Study')
@section('meta_description', 'Často kladené otázky k přihláškám, studiu a životu v Irsku — rychlé odpovědi na nejčastější dotazy studentů z ČR a SK.')

@section('content')
  <div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold mb-4">Často kladené otázky</h1>
    <p class="mb-6 text-gray-600">Nejčastější dotazy k přihláškám, dokumentům, financování a životu v Irsku. Pokud tu nenajdeš odpověď, napiš nám.</p>

  <faq-accordion></faq-accordion>

    <div class="mt-8">
      @include('components.cta')
    </div>
  </div>
@endsection

@extends('layouts.app')

@section('title', 'FAQ — Irsko Study')
@section('meta_description', 'Často kladené otázky k přihláškám, studiu a životu v Irsku — rychlé odpovědi na nejčastější dotazy studentů z ČR a SK.')

@section('content')
  <div class="layout-container layout-section max-w-4xl mx-auto">
    <h1 class="type-display-xl text-brand-dark-green mb-4">Často kladené otázky</h1>
    <p class="type-text-md mb-6 text-brand-dark-green">Nejčastější dotazy k přihláškám, dokumentům, financování a životu v Irsku. Pokud tu nenajdeš odpověď, napiš nám.</p>

  <faq-accordion :items="{{ json_encode($items) }}"></faq-accordion>

    <div class="mt-8">
      @include('components.cta')
    </div>
  </div>
@endsection

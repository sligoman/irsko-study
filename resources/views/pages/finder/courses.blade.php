@extends('layouts.app')

@section('title', 'Kurzy - Irsko Study')
@section('meta_description', 'Přehled kurzů a programů — filtrovat podle školy, oboru a úrovně. Najděte kurz, který vám sedí.')

@section('content')
  <div class="max-w-7xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-4">Přehled kurzů a programů</h1>
    <p class="text-gray-700 mb-6">Filtrovat podle školy, oboru nebo úrovně. Výsledky aktualizují bez načítání stránky.</p>

    <div id="app">
      <course-finder :initial-data='@json($initialData)'></course-finder>
    </div>

  </div>
  @include('components.cta')
@endsection

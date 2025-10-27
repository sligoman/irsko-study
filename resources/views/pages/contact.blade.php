@extends('layouts.app')

@section('title', 'Kontakt - IrskoStudy')

@section('content')
  <h1 class="text-2xl font-bold">Kontakt</h1>
  <p class="mt-2">Napište nám a domluvte si konzultaci.</p>

  <contact-form />

  @include('components.cta')
@endsection

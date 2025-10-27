@extends('layouts.app')

@section('title', 'FAQ - IrskoStudy')

@section('content')
  <div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold mb-4">Často kladené otázky</h1>
    <p class="mb-6">Níže najdete odpovědi na nejčastější dotazy.</p>
    <faq-accordion />
  </div>
@endsection
@extends('layouts.app')

@section('title', 'FAQ — IrskoStudy')

@section('content')
  <h1 class="text-2xl font-bold">FAQ</h1>
  <p class="mt-2">Nejčastější otázky o studiu v Irsku.</p>

  <faq-accordion />

  @include('components.cta')
@endsection

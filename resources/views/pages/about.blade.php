@extends('layouts.app')

@section('title', 'O nás - IrskoStudy')

@section('content')
  <div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold mb-4">O nás</h1>
    <p>Pomáháme českým a slovenským studentům splnit sen o studiu v Irsku. Jsme agentura se sídlem v Irsku — známe místní prostředí.</p>
  </div>
  @include('components.cta')
@endsection
@extends('layouts.app')

@section('title', 'O nás — IrskoStudy')

@section('content')
  <h1 class="text-2xl font-bold">O nás</h1>
  <p class="mt-4">Pomáháme českým a slovenským studentům splnit sen o studiu v Irsku.</p>
  <p class="mt-4">Jsme lokální tým, žijeme v Irsku a rozumíme tamnímu systému.</p>
  @include('components.cta')
@endsection

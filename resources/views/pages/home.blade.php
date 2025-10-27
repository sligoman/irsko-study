@extends('layouts.app')

@section('title', 'Domů - IrskoStudy')

@section('content')
  @include('components.hero')

  @include('components.usp-strip')

  @include('components.reasons')

  <!-- Krok za krokem (steps komponenta) -->
  @include('components.steps')

  <!-- Aktuální termíny a novinky (blog-preview) -->
  @include('components.blog-preview')

  <!-- Reference studentů -->
  @include('components.testimonials')

  <div class="text-center mt-6">
    <a href="{{ route('contact') }}" class="inline-block bg-[color:var(--color-primary)] text-white px-6 py-3 rounded-lg">Chci být jedním z úspěšných studentů</a>
  </div>

  <!-- Silné CTA (cta komponenta) -->
  @include('components.cta')

@endsection

@extends('layouts.app')

@section('title', 'Domů - IrskoStudy')

@section('content')
  @include('components.hero')

  {{-- Pointer gradient test component (for visual debugging) --}}
  {{-- <pointer-gradient-test></pointer-gradient-test> --}}

  @include('components.usp-strip')

  <!-- Reference studentů -->
  @include('components.testimonials')
  
  
  @include('components.reasons')

  <!-- Aktuální termíny a novinky (blog-preview) -->
  @include('components.blog-preview')
  
  
  <!-- Krok za krokem (steps komponenta) -->
  @include('components.steps')

  {{-- <div class="text-center mt-6">
    <a href="{{ route('contact') }}" class="inline-block btn-cta text-white px-6 py-3 rounded-lg">Chci být jedním z úspěšných studentů</a>
  </div> --}}

  <!-- Silné CTA (cta komponenta) -->
  @include('components.cta')

@endsection

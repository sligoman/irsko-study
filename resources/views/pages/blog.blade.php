@extends('layouts.app')

@section('title', 'Blog - IrskoStudy')

@section('content')
  <div class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold mb-6">Blog</h1>
    <blog-dynamic />
  </div>
@endsection
@extends('layouts.app')

@section('title', 'Blog — IrskoStudy')

@section('content')
  <h1 class="text-2xl font-bold">Blog</h1>
  <p class="mt-2">Aktuality a články o studiu v Irsku.</p>

  <blog-dynamic />

  @include('components.cta')
@endsection

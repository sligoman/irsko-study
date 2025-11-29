@extends('layouts.app')

@section('title', 'Blog - IrskoStudy')
@section('meta_description', 'Novinky a články o studiu v Irsku, tipech pro přihlášky, životě v Dublinu a zkušenostech studentů.')

@section('content')
  <div class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold mb-6">Blog</h1>
    <blog-dynamic />
  </div>
@endsection


@extends('layouts.app')

@section('title', 'Vysoké školy - IrskoStudy')

@section('content')
  <div class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold mb-6">Přehled univerzit</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white p-6 rounded shadow">Trinity College Dublin</div>
      <div class="bg-white p-6 rounded shadow">University College Dublin (UCD)</div>
      <div class="bg-white p-6 rounded shadow">University College Cork (UCC)</div>
      <div class="bg-white p-6 rounded shadow">National University of Ireland Galway</div>
    </div>
  </div>
@endsection
@extends('layouts.app')

@section('title', 'Vysoké školy — IrskoStudy')

@section('content')
  <h1 class="text-2xl font-bold">Přehled univerzit</h1>
  <div class="mt-4 grid md:grid-cols-2 gap-4">
    <div class="p-4 border rounded">Trinity — centrum výzkumu a historie</div>
    <div class="p-4 border rounded">UCD — velká univerzita v Dublinu</div>
    <div class="p-4 border rounded">UCC — Cork, silné programy</div>
    <div class="p-4 border rounded">NUI Galway — studentský život</div>
  </div>
  @include('components.cta')
@endsection

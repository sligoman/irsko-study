@extends('layouts.app')

@section('title', 'Kontakt - Irsko Study')
@section('meta_description', 'Kontaktujte IrskoStudy — napište nám nebo zavolejte, rádi poradíme se studiem v Irsku, přihláškami a ubytováním.')

@section('content')

<section class="py-12">

<div class="max-w-6xl mx-auto px-4">

    <h1 class="text-2xl font-bold">Kontakt</h1>
    <p class="mt-2">Napiš nám a domluv si konzultaci.</p>

      <contact-form />
</div>


  @include('components.cta')

</section>

@endsection

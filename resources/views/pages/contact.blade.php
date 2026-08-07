@extends('layouts.app')

@section('title', 'Kontakt - Irsko Study')
@section('meta_description', 'Kontaktujte IrskoStudy — napište nám nebo zavolejte, rádi poradíme se studiem v Irsku, přihláškami a ubytováním.')

@section('content')

<section class="layout-section">

<div class="layout-container">

    <h1 class="type-display-xl text-brand-dark-green">Kontakt</h1>
    <p class="type-text-md mt-2 text-brand-dark-green">Napiš nám a domluv si konzultaci.</p>

      <div class="mt-10"><contact-form /></div>
</div>


  @include('components.cta')

</section>

@endsection

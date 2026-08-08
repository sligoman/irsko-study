@extends('layouts.app')

@section('title', 'Kontakt — Irsko Study')
@section('meta_description', 'Kontaktujte IrskoStudy — napište nám, zavolejte nebo napište na WhatsApp. Rádi poradíme se studiem v Irsku, přihláškami a ubytováním.')

@section('content')
  <main data-redesign-page="contact" class="bg-base-white">
    <x-subpage.hero
      eyebrow="Kontakt"
      title="Napiš nám a domluv si"
      accent="konzultaci zdarma"
      text="Každé velké rozhodnutí začíná rozhovorem. Napiš nám, kde teď jsi a kam se chceš dostat — ozveme se s dalším krokem."
      :stats="[
        ['value' => '30 min', 'label' => 'úvodní rozhovor'],
        ['value' => '0 Kč', 'label' => 'nezávazný začátek'],
        ['value' => 'CS/SK', 'label' => 'komunikace v češtině'],
      ]"
    />

    <section data-redesign-section="contact-info" class="home-section">
      <div class="layout-container">
        <div class="grid gap-5 md:grid-cols-3">
          <a href="tel:{{ config('contacts.mobile') }}" class="rounded-[16px] bg-brand-light-gray p-6 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
            <p class="type-text-sm text-brand-orange">Telefon / WhatsApp</p>
            <p class="type-display-xs mt-2 text-brand-dark-green">{{ config('contacts.mobile') }}</p>
          </a>

          <a href="mailto:{{ config('contacts.email') }}" class="rounded-[16px] bg-brand-light-gray p-6 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
            <p class="type-text-sm text-brand-orange">E-mail</p>
            <p class="type-display-xs mt-2 break-all text-brand-dark-green">{{ config('contacts.email') }}</p>
          </a>

          <div class="rounded-[16px] bg-brand-light-gray p-6">
            <p class="type-text-sm text-brand-orange">Sídlo</p>
            <p class="type-display-xs mt-2 text-brand-dark-green">{{ config('contacts.address') }}</p>
          </div>
        </div>
      </div>
    </section>

    <section data-redesign-section="contact-form" class="home-section">
      <div class="layout-container">
        <div class="grid overflow-hidden rounded-[16px] bg-brand-light-gray lg:grid-cols-2">
          <div class="p-6 md:p-10 lg:p-12">
            <p class="type-text-md-semibold text-brand-orange">Bezplatná konzultace</p>
            <h2 class="type-display-lg mt-2 text-brand-dark-green">Napiš nám, kde teď jsi a kam se chceš dostat</h2>
            <p class="type-text-lg mt-6 text-brand-dark-green">Ozveme se s dalším krokem: doporučíme školy, vysvětlíme termíny a řekneme, co připravit jako první.</p>
          </div>
          <div class="bg-white p-6 md:p-10 lg:p-12">
            <contact-form position="bottom" page="{{ route('contact') }}"></contact-form>
          </div>
        </div>
      </div>
    </section>

    @include('components.cta')
  </main>
@endsection

@extends('layouts.app')

@section('title', 'Ochrana osobních údajů')
@section('meta_description', 'Informace o zpracování osobních údajů a ochraně soukromí na IrskoStudy — jak a proč shromažďujeme data.')

@php
  $sections = [
    [
      'title' => '1) Kdo je správcem osobních údajů',
      'paragraphs' => [
        'Správcem osobních údajů je provozovatel webu ' . config('contacts.company_name') . ', sídlo: ' . config('contacts.address') . '.',
        'Ve většině případů vystupujeme jako správce, protože určujeme, za jakým účelem a jakým způsobem budou údaje zpracovávány.',
      ],
    ],
    [
      'title' => '2) Jaké osobní údaje zpracováváme',
      'list' => [
        'Údaje z kontaktních formulářů: jméno, e-mail, telefon a zpráva.',
        'Informace o tvém studijním plánu, které nám sdělíš během konzultace.',
        'Technická metadata (IP adresa, typ prohlížeče) zaznamenaná v logu pro bezpečnost a analytiku.',
        'Cookies a nástroje pro analytiku (pokud jsou aktivní).',
      ],
    ],
    [
      'title' => '3) K čemu údaje používáme',
      'paragraphs' => [
        'Údaje slouží k odpovědi na dotazy, přípravě nabídky studia, komunikaci se školami a partnerům v Irsku, technickému provozu webu a zlepšování služeb.',
        'Kontaktní zprávy mohou být ukládány v logu nebo odeslány na e-mail správce.',
      ],
    ],
    [
      'title' => '4) Komu mohou být údaje předány',
      'list' => [
        'Místním partnerům v Irsku: vysokým školám, univerzitám a ubytovacím zařízením.',
        'Dodavatelům: účetním, IT a administrativním poskytovatelům.',
        'Technologickým partnerům: poskytovatelům analytických a cloudových nástrojů.',
      ],
    ],
    [
      'title' => '5) Jak dlouho údaje uchováváme',
      'paragraphs' => [
        'Údaje uchováváme pouze po dobu nutnou pro daný účel — typicky do vyřízení dotazu, po dobu trvání spolupráce, případně po dobu stanovenou zákonem (např. 10 let u daňových dokladů).',
      ],
    ],
    [
      'title' => '6) Jaká máte práva',
      'list' => [
        'Právo na přístup k údajům a informace o jejich zpracování.',
        'Právo na opravu nepřesných údajů.',
        'Právo na výmaz („právo být zapomenut“).',
        'Právo na omezení zpracování.',
        'Právo na přenositelnost údajů.',
        'Právo vznést námitku proti zpracování.',
        'Právo kdykoliv odvolat souhlas.',
      ],
    ],
    [
      'title' => '7) Jak můžete svá práva uplatnit',
      'paragraphs' => [
        'Své žádosti zasílejte na e-mail ' . config('contacts.email') . '. Na vaši žádost odpovíme bez zbytečného odkladu, nejpozději do jednoho měsíce.',
        'Pokud se domníváte, že vaše údaje zpracováváme v rozporu s předpisy, máte právo podat stížnost u dozorového úřadu (Data Protection Commission, Ireland).',
      ],
    ],
    [
      'title' => '8) Cookies a zabezpečení',
      'paragraphs' => [
        'Web používá HTTPS a přijímá rozumná technická opatření k ochraně dat. Nezbytné cookies používáme pro provoz webu, ostatní (analytické) pouze s vaším souhlasem.',
      ],
    ],
    [
      'title' => '9) Změny a aktualizace',
      'paragraphs' => [
        'Tento dokument můžeme průběžně aktualizovat. Aktuální verze je vždy dostupná na této stránce.',
      ],
    ],
  ];
@endphp

@section('content')
  <main data-redesign-page="privacy" class="bg-base-white">
    <x-subpage.hero
      eyebrow="Právní dokumenty"
      title="Ochrana osobních"
      accent="údajů"
      text="Vysvětlujeme, jaké osobní údaje zpracováváme, k jakým účelům, jak dlouho je uchováváme, komu je můžeme předat a jaká máte práva."
      :stats="[
        ['value' => 'GDPR', 'label' => 'zpracování v souladu s předpisy'],
        ['value' => 'CS', 'label' => 'aktuální znění dokumentu'],
        ['value' => '30 dní', 'label' => 'lhůta pro odpověď na žádost'],
      ]"
    />

    <section data-redesign-section="privacy-content" class="home-section pb-20">
      <div class="layout-container">
        <div class="mx-auto max-w-[920px] space-y-4">
          @foreach($sections as $section)
            <section class="rounded-[16px] bg-brand-light-gray p-6 md:p-8">
              <h2 class="type-display-sm text-brand-dark-green">{{ $section['title'] }}</h2>

              @foreach($section['paragraphs'] ?? [] as $paragraph)
                <p class="type-text-md mt-4 text-brand-dark-green">
                  @if(str_contains($paragraph, config('contacts.email')))
                    {!! str_replace(
                        config('contacts.email'),
                        '<a href="mailto:' . config('contacts.email') . '" class="underline underline-offset-4">' . config('contacts.email') . '</a>',
                        e($paragraph)
                    ) !!}
                  @else
                    {{ $paragraph }}
                  @endif
                </p>
              @endforeach

              @if(!empty($section['list']))
                <ul class="type-text-md mt-4 list-disc space-y-2 pl-6 text-brand-dark-green">
                  @foreach($section['list'] as $item)
                    <li>{{ $item }}</li>
                  @endforeach
                </ul>
              @endif
            </section>
          @endforeach

          <section class="rounded-[16px] bg-brand-light-gray p-6 md:p-8">
            <p class="type-text-md text-brand-dark-green">Poslední aktualizace: 8. srpna 2026</p>
          </section>
        </div>
      </div>
    </section>

    @include('components.cta')
  </main>
@endsection

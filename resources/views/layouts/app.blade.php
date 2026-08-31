<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $__env->yieldContent('title', 'Irsko STUDY') }}</title>
    <meta name="description" content="{{ $__env->yieldContent('meta_description', 'IrskoStudy — pomoc s přihláškami, ubytováním a studiem v Irsku pro studenty z ČR a SK') }}">

    <meta name="theme-color" content="#0E4A32">
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/svg/favicon.svg') }}?v=6">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/svg/favicon.ico') }}?v=6">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/svg/favicon-32x32.png') }}?v=6">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/svg/favicon-16x16.png') }}?v=6">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/svg/apple-touch-icon.png') }}?v=6">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('img/svg/android-chrome-192x192.png') }}?v=6">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('img/svg/android-chrome-512x512.png') }}?v=6">
    <link rel="manifest" href="{{ asset('img/site.webmanifest') }}?v=6">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    @yield('head')

    @if(app()->environment(['local', 'testing']))
        <meta name="robots" content="noindex,nofollow" />
    @endif
    @hasSection('robots')
        <meta name="robots" content="@yield('robots')" />
    @endif

    <script>
        window.__CONTACTS = {!! json_encode(config('contacts')) !!};
    </script>

    <style>[v-cloak]{display:none!important}</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-base-white">

    @php
    $style = 'dark';
    switch(Route::currentRouteName()) {
      case 'about':
      case 'why':
      case 'universities':

      case 'services':
      case 'faq':
      case 'contact':
        break;
      default:
        $style = 'light';
        break;
    }
    @endphp

    <div id="app" class="{{ $style == 'dark' ? 'bg-brand-dark-green' : '' }} overflow-hidden" v-cloak>

        @if(isset($heroImage))
        <div class="relative overflow-hidden">
          <img class="absolute inset-0 w-full h-full object-cover animate-zoom-slow"
               src="{{ asset('img/desktop/'.$heroImage) }}"
               srcset="{{ asset('img/mobile/'.$heroImage) }} 480w, {{ asset('img/desktop/'.$heroImage) }} 1024w, {{ asset('img/highres/'.$heroImage) }} 1920w"
               sizes="(min-width: 1366px) 1024px, (min-width: 1536px) 1920px, 100vw"
               alt="{{ $heroImageAlt ?? '' }}">
        </div>
        @endif

        @include('components.navbar')

        @yield('content')

        @if(Route::currentRouteName() != 'home')
        <x-back-to-top />
        @endif

        @include('components.footer')

        <x-floating-button></x-floating-button>

    </div>

</body>
</html>

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Irsko STUDY')</title>
    <meta name="description" content="@yield('meta_description', 'IrskoStudy — pomoc s přihláškami, ubytováním a studiem v Irsku pro studenty z ČR a SK')">

    <meta name="theme-color" content="#0E4A32">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    @if(app()->environment(['local', 'testing']))
        <meta name="robots" content="noindex,nofollow" />
    @endif

    <script>
        window.__CONTACTS = {!! json_encode(config('contacts')) !!};
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-base-white">

    @php
    $style = 'dark';
    switch(Route::currentRouteName()) {
      case 'about':
      case 'why':
      case 'universities':
      case 'blog':
      case 'blog.post':
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
        <div class="bg-brand-dark-green p-4">
            <a href="#app" class="flex flex-col gap-1 text-sm text-gray-300 hover:text-brand-orange items-center justify-center">
                <x-svg.chevron direction="up" />Skoč na začátek stránky
            </a>
        </div>
        @endif

        @include('components.footer')

        <x-floating-button></x-floating-button>

    </div>

</body>
</html>

<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'IrskoStudy')</title>
    {{-- Favicon / touch icons (use site logo in public/img/logo.png) --}}
    <link rel="icon" href="{{ asset('img/logo.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}" />
    <meta name="theme-color" content="#10B981">
    {{-- Expose contact/company config to client-side JS so Vue components can access it via `window.__CONTACTS` --}}
    <script>
        window.__CONTACTS = {!! json_encode(config('contacts')) !!};
    </script>
    {{-- Vite assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="description" content="@yield('meta_description', 'IrskoStudy — pomoc s přihláškami, ubytováním a studiem v Irsku pro studenty z ČR a SK')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body  id="app" class="antialiased font-sans bg-gray-50 text-gray-900">
    @include('components.navbar')

    <main class="min-h-screen">
            @yield('content')
    </main>

    <contact-form position="floating"></contact-form>

    @include('components.footer')

    

</body>
</html>


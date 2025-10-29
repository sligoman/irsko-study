<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'IrskoStudy')</title>
    {{-- Expose contact/company config to client-side JS so Vue components can access it via `window.__CONTACTS` --}}
    <script>
        window.__CONTACTS = {!! json_encode(config('contacts')) !!};
    </script>
    {{-- Vite assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="description" content="IrskoStudy — pomoc s přihláškami, ubytováním a studiem v Irsku pro studenty z ČR a SK">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body  id="app" class="antialiased font-sans bg-gray-50 text-gray-900">
    @include('components.navbar')

    <main class="min-h-screen">
            @yield('content')
    </main>

    @include('components.footer')

    

</body>
</html>


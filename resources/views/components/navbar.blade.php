<nav class="w-full z-40">
  <div class="backdrop-blur bg-white/60 border-b">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
      <div class="flex items-center justify-between h-16">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
          <img src="{{ asset('img/logo.png') }}" alt="{{ config('contacts.company_name') }}" class="h-10 w-auto">
          <span class="text-lg font-semibold text-[color:var(--color-primary)]">{{ config('contacts.company_suffix') }}</span>
        </a>

        <div class="hidden lg:flex items-center gap-8">
          <a href="{{ route('about') }}" class="text-gray-700 hover:text-[color:var(--color-primary)] hover:underline">O nás</a>
          <a href="{{ route('why') }}" class="text-gray-700 hover:text-[color:var(--color-primary)] hover:underline">Proč Irsko</a>
          <a href="{{ route('universities') }}" class="text-gray-700 hover:text-[color:var(--color-primary)] hover:underline">Vysoké školy</a>
          <a href="{{ route('services') }}" class="text-gray-700 hover:text-[color:var(--color-primary)] hover:underline">Služby</a>
          <a href="{{ route('faq') }}" class="text-gray-700 hover:text-[color:var(--color-primary)] hover:underline">Co tě zajímá</a>
        </div>

        <div class="hidden lg:flex items-center gap-4">
          <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 btn-cta text-white px-4 py-2 rounded-md shadow-md">Kontakt</a>
        </div>

        <!-- Mobile -->
        <div class="lg:hidden flex items-center">
          <button id="nav-toggle" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="lg:hidden hidden">
      <div class="px-4 pt-4 pb-6 space-y-2">
        <a href="{{ route('about') }}" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-100">O nás</a>
        <a href="{{ route('why') }}" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-100">Proč Irsko</a>
        <a href="{{ route('universities') }}" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-100">Vysoké školy</a>
        <a href="{{ route('services') }}" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-100">Služby</a>
        <a href="{{ route('faq') }}" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-100">FAQ</a>
        <a href="{{ route('contact') }}" class="block px-3 py-2 rounded bg-[color:var(--color-primary)] text-white">Kontakt</a>
      </div>
    </div>
  </div>
</nav>

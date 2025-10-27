<nav class="bg-white shadow">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16 items-center">
      <div class="flex items-center">
        <a href="{{ route('home') }}" class="flex items-center brand-link">
          <img src="{{ asset('img/logo.png') }}" alt="IrskoStudy" class="h-12 w-auto">
        </a>
      </div>

      <!-- Desktop links -->
      <div class="hidden md:flex space-x-6 items-center">
        <a href="{{ route('about') }}" class="text-gray-700 hover:text-[color:var(--color-primary)]">O nás</a>
        <a href="{{ route('why') }}" class="text-gray-700 hover:text-[color:var(--color-primary)]">Proč Irsko</a>
        <a href="{{ route('universities') }}" class="text-gray-700 hover:text-[color:var(--color-primary)]">Vysoké školy</a>
        <a href="{{ route('services') }}" class="text-gray-700 hover:text-[color:var(--color-primary)]">Služby</a>
        <a href="{{ route('faq') }}" class="text-gray-700 hover:text-[color:var(--color-primary)]">FAQ</a>
        <a href="{{ route('contact') }}" class="ml-4 inline-block bg-[color:var(--color-primary)] text-white px-4 py-2 rounded shadow">Kontakt</a>
      </div>

      <!-- Mobile toggle -->
      <div class="md:hidden">
        <button id="nav-toggle" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none">
          <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile menu, hidden by default -->
  <div id="mobile-menu" class="md:hidden hidden border-t">
    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
      <a href="{{ route('about') }}" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-100">O nás</a>
      <a href="{{ route('why') }}" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-100">Proč Irsko</a>
      <a href="{{ route('universities') }}" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-100">Vysoké školy</a>
      <a href="{{ route('services') }}" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-100">Služby</a>
      <a href="{{ route('faq') }}" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-100">FAQ</a>
      <a href="{{ route('contact') }}" class="block px-3 py-2 rounded bg-[color:var(--color-primary)] text-white">Kontakt</a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function(){
      const btn = document.getElementById('nav-toggle');
      const menu = document.getElementById('mobile-menu');
      if (btn && menu) btn.addEventListener('click', () => menu.classList.toggle('hidden'));
    });
  </script>
</nav>

<footer class="bg-white border-t mt-12">
  <div class="max-w-7xl mx-auto px-4 py-10 text-sm text-gray-600">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
      <div>
        <div class="flex items-center gap-3">
          <img src="{{ asset('img/logo.png') }}" alt="IrskoStudy" class="h-8">
          <span class="font-semibold">IrskoStudy.cz</span>
        </div>
        <div class="mt-2">&copy; {{ date('Y') }} • Všechna práva vyhrazena</div>
      </div>

      <div class="flex gap-4">
        <a href="/" class="text-gray-700 hover:text-[color:var(--color-primary)]">Domů</a>
        <a href="{{ route('faq') }}" class="text-gray-700 hover:text-[color:var(--color-primary)]">FAQ</a>
        <a href="{{ route('contact') }}" class="text-gray-700 hover:text-[color:var(--color-primary)]">Kontakt</a>
      </div>
    </div>
  </div>
</footer>


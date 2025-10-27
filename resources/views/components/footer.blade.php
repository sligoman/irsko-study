<footer class="bg-[color:var(--color-primary)] text-white mt-16">
  <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div>
        <div class="flex items-center gap-3">
          <img src="{{ asset('img/logo.png') }}" alt="IrskoStudy" class="h-10">
          <span class="font-semibold text-white">IrskoStudy.cz</span>
        </div>
        <p class="mt-4 text-sm text-white/80">Pomáháme studentům z ČR a SK najít a zařídit studium v Irsku — přihlášky, víza, ubytování.</p>
        <div class="mt-4 text-sm text-white/70">&copy; {{ date('Y') }} IrskoStudy.cz</div>
      </div>

      <div>
        <h4 class="font-semibold mb-3">Odkazy</h4>
        <ul class="space-y-2 text-sm text-white/90">
          <li><a href="/" class="hover:underline">Domů</a></li>
          <li><a href="{{ route('about') }}" class="hover:underline">O nás</a></li>
          <li><a href="{{ route('services') }}" class="hover:underline">Služby</a></li>
          <li><a href="{{ route('faq') }}" class="hover:underline">FAQ</a></li>
        </ul>
      </div>

      <div>
        <h4 class="font-semibold mb-3">Kontakt</h4>
        <div class="text-sm text-white/90">
          <div>hello@irskostudy.cz</div>
          <div class="mt-2">Telefon: +420 123 456 789</div>
        </div>

        <div class="mt-6 flex items-center gap-3">
          <a href="#" class="w-9 h-9 bg-white/10 rounded flex items-center justify-center hover:bg-white/20">F</a>
          <a href="#" class="w-9 h-9 bg-white/10 rounded flex items-center justify-center hover:bg-white/20">I</a>
          <a href="#" class="w-9 h-9 bg-white/10 rounded flex items-center justify-center hover:bg-white/20">L</a>
        </div>
      </div>
    </div>
  </div>
</footer>


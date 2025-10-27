<section class="relative overflow-hidden pt-28">
  <div class="absolute inset-0 bg-gradient-to-r from-[color:var(--color-primary)]/10 via-transparent to-[color:var(--color-emerald)]/5 pointer-events-none"></div>
  <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
      <div class="lg:col-span-7 reveal reveal-from-left">
        <div class="inline-block px-3 py-1 rounded-full bg-[color:var(--color-emerald)]/10 text-[color:var(--color-emerald)] font-semibold mb-4">Irish-based agentura</div>

        <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-[color:var(--color-primary)]">Studium v Irsku — jednoduché a bez bolesti</h1>

        <p class="mt-6 text-lg text-gray-700 max-w-2xl">Pomůžeme ti od první konzultace až po první den na kampusu – přihlášky, dokumenty, ubytování i podpora po příjezdu.</p>

        <div class="mt-8 flex flex-wrap gap-3">
          <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 btn-cta text-white px-5 py-3 rounded-md shadow">Domluv si bezplatnou konzultaci</a>
          <a href="{{ route('why') }}" class="inline-flex items-center gap-2 bg-white text-[color:var(--color-primary)] px-5 py-3 rounded-md border">Zjisti proč studovat v Irsku</a>
        </div>

        <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="bg-white rounded-lg p-5 shadow-sm border">
            <h3 class="text-sm font-semibold text-[color:var(--color-primary)]">Komplexní servis</h3>
            <p class="mt-2 text-sm text-gray-600">Pomáháme od výběru studia až po nastěhování.</p>
          </div>
          <div class="bg-white rounded-lg p-5 shadow-sm border">
            <h3 class="text-sm font-semibold text-[color:var(--color-primary)]">Ověřené postupy</h3>
            <p class="mt-2 text-sm text-gray-600">Zkušenosti s irským systémem vzdělávání a víz.</p>
          </div>
        </div>
      </div>

  <div class="lg:col-span-5 reveal reveal-from-right">
        <div class="relative group">
          <!-- decorative SVG blob -->
          <svg class="absolute -left-12 -top-10 w-72 h-72 opacity-30 transform-gpu will-change-transform transition-transform duration-700 group-hover:rotate-6" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <g transform="translate(300,300)">
              <path d="M120,-150C156,-126,189,-96,201,-59C213,-21,204,25,180,61C156,96,117,122,77,138C37,154,-4,160,-44,146C-84,132,-123,98,-146,55C-169,12,-176,-39,-156,-82C-136,-125,-89,-159,-40,-174C9,-189,55,-184,120,-150Z" fill="url(#g1)"/>
              <defs>
                <linearGradient id="g1" x1="0%" x2="100%" y1="0%" y2="100%">
                  <stop offset="0%" stop-color="var(--color-emerald)" stop-opacity="0.25" />
                  <stop offset="100%" stop-color="var(--color-primary)" stop-opacity="0.18" />
                </linearGradient>
              </defs>
            </g>
          </svg>

          <div class="aspect-[4/3] bg-white rounded-2xl shadow-xl border overflow-hidden transform-gpu transition duration-500 group-hover:-translate-y-2 group-hover:scale-105">
            <img src="{{ asset('img/hero-illustration.png') }}" alt="Studium v Irsku" class="w-full h-full object-cover">
          </div>

          <div class="absolute -bottom-6 left-6 w-64 bg-white rounded-xl p-4 shadow-md border transform transition duration-500 motion-safe:translate-y-0 group-hover:translate-y-[-6px]">
            <div class="text-xs text-gray-500">Nejpopulárnější</div>
            <div class="mt-1 font-semibold text-[color:var(--color-primary)]">University College Dublin</div>
            <div class="mt-1 text-sm text-gray-600">Pomůžeme s přihláškou a ubytováním.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

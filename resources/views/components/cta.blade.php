<section class="py-12">
  <div class="max-w-6xl mx-auto px-4">
    <div class="bg-gradient-to-r from-[color:var(--color-emerald)]/90 to-white rounded-lg p-8 flex flex-col md:flex-row items-center md:items-start gap-6 shadow-lg">
      <div class="flex-1">
        <h2 class="text-2xl font-bold text-[color:var(--color-primary)]">Chceš pomoct se studiem v Irsku?</h2>
        <p class="text-gray-600 mb-4">Každé velké rozhodnutí začíná rozhovorem. Nabízíme nezávaznou konzultaci, kde zjistíme tvé cíle, vysvětlíme fungování přihlášek a doporučíme konkrétní první kroky.</p>
        <p class="text-sm text-gray-700">Konzultace zahrnuje přehled škol, doporučení studijního programu a postup přihlášky. Máme místní zkušenost a osobní přístup.</p>
      </div>

      <div class="flex flex-col sm:flex-row items-stretch gap-3 md:items-center">
        <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-[color:var(--color-primary)] text-white px-5 py-3 rounded-lg shadow hover:shadow-xl transition">
          <!-- phone icon -->
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" aria-hidden>
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.09 4.18 2 2 0 0 1 4 2h3a2 2 0 0 1 2 1.72c.12.99.36 1.95.72 2.86a2 2 0 0 1-.45 2.11L8.91 9.91a14.07 14.07 0 0 0 6 6l1.22-1.22a2 2 0 0 1 2.11-.45c.9.36 1.87.6 2.86.72A2 2 0 0 1 22 16.92z" />
          </svg>
          Domluv si konzultaci
        </a>

        <a href="{{ route('services') }}" class="inline-flex items-center gap-2 bg-white text-[color:var(--color-primary)] px-5 py-3 rounded-lg border hover:bg-white/90 transition">
          <!-- info icon -->
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" aria-hidden>
            <circle cx="12" cy="12" r="10" />
            <path d="M12 16v-4" />
            <path d="M12 8h.01" />
          </svg>
          Zjistit více
        </a>

        @if(config('contacts.instagram'))
          <a href="{{ config('contacts.instagram') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-white px-4 py-2 rounded-lg border border-white/20 hover:bg-white/10">
            <!-- Instagram icon -->
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden>
              <path d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm8 2H9a3 3 0 00-3 3v8a3 3 0 003 3h6a3 3 0 003-3V7a3 3 0 00-3-3z" />
              <path d="M12 7a5 5 0 110 10 5 5 0 010-10zm0 2a3 3 0 100 6 3 3 0 000-6z" fill="#fff" />
              <circle cx="17.5" cy="6.5" r="1.2" fill="#fff" />
            </svg>
            <span class="text-sm">@irskostudy</span>
          </a>
        @endif

        {{-- <div class="hidden md:flex flex-col text-sm text-gray-700 ml-2">
          @if(config('contacts.email'))
            <a href="mailto:{{ config('contacts.email') }}" class="text-gray-700 hover:text-[color:var(--color-primary)]">E-mail: {{ config('contacts.email') }}</a>
          @endif
          @if(config('contacts.mobile'))
            <a href="tel:{{ config('contacts.mobile') }}" class="text-gray-700 hover:text-[color:var(--color-primary)]">Mobil: {{ config('contacts.mobile') }}</a>
          @endif
          @if(config('contacts.whatsapp'))
            <a href="{{ strpos(config('contacts.whatsapp'),'http') === 0 ? config('contacts.whatsapp') : 'https://wa.me/' . config('contacts.whatsapp') }}" class="text-gray-700 hover:text-[color:var(--color-primary)] mt-1" target="_blank" rel="noopener">WhatsApp</a>
          @endif
        </div> --}}
      </div>
    </div>
  </div>
</section>


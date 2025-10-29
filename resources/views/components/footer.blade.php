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
          <div>{{ config('contacts.email') }}</div>
          <div class="mt-2">Mobil: {{ config('contacts.mobile') }}</div>
          @if(config('contacts.whatsapp'))
            <div class="mt-1">WhatsApp: <a href="{{ strpos(config('contacts.whatsapp'),'http') === 0 ? config('contacts.whatsapp') : 'https://wa.me/' . config('contacts.whatsapp') }}" class="text-white underline" target="_blank" rel="noopener">Odeslat zprávu</a></div>
          @endif
          @if(config('contacts.whatsapp_qr'))
            <div class="mt-3">
              @php
                $qr = config('contacts.whatsapp_qr');
                $qrUrl = strpos($qr, 'http') === 0 ? $qr : asset($qr);
              @endphp
              <a href="{{ $qrUrl }}" target="_blank" rel="noopener" class="inline-block">
                <img src="{{ $qrUrl }}" alt="WhatsApp QR" class="w-24 h-24 object-contain rounded-md border border-white/20" />
              </a>
            </div>
          @endif
        </div>

        <div class="mt-6 flex items-center gap-3">
          {{-- <a href="#" class="w-9 h-9 bg-white/10 rounded flex items-center justify-center hover:bg-white/20">F</a>
          <a href="#" class="w-9 h-9 bg-white/10 rounded flex items-center justify-center hover:bg-white/20">I</a>
          <a href="#" class="w-9 h-9 bg-white/10 rounded flex items-center justify-center hover:bg-white/20">L</a> --}}
          @if(config('contacts.whatsapp'))
            <a href="{{ strpos(config('contacts.whatsapp'),'http') === 0 ? config('contacts.whatsapp') : 'https://wa.me/' . config('contacts.whatsapp') }}" class="w-9 h-9 bg-white/10 rounded flex items-center justify-center hover:bg-white/20" target="_blank" rel="noopener" aria-label="WhatsApp">
              <!-- WhatsApp svg icon -->
              <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden>
                <path d="M20.52 3.48A11.95 11.95 0 0012 0C5.373 0 0 5.373 0 12c0 2.115.552 4.094 1.6 5.86L0 24l6.4-1.6A11.95 11.95 0 0012 24c6.627 0 12-5.373 12-12 0-3.2-1.248-6.218-3.48-8.52zM12 21.6c-1.6 0-3.152-.384-4.544-1.112l-.32-.176L4 20l.68-2.96-.192-.32A9.6 9.6 0 012.4 12c0-5.28 4.32-9.6 9.6-9.6 2.56 0 4.96.96 6.8 2.72A9.36 9.36 0 0121.6 12c0 5.28-4.32 9.6-9.6 9.6z" />
                <path d="M17.04 14.4c-.32-.16-1.92-.96-2.24-1.04-.32-.08-.56-.16-.8.16-.24.32-.96 1.04-1.18 1.28-.22.24-.44.28-.8.12-.36-.16-1.52-.56-2.88-1.78-1.06-.94-1.76-2.08-1.98-2.44-.22-.36-.02-.56.16-.72.16-.16.36-.44.54-.64.18-.2.24-.36.36-.6.12-.24.04-.44-.02-.6-.06-.16-.8-1.92-1.12-2.64-.28-.68-.56-.6-.8-.6l-.68.04c-.24 0-.64.08-.96.4-.32.32-1.28 1.24-1.28 3.04 0 1.8 1.32 3.56 1.5 3.8.18.24 2.6 3.96 6.44 5.56 3.84 1.6 3.84 1.08 4.56 1.02.72-.06 2.32-.94 2.64-1.84.32-.9.32-1.66.224-1.84-.096-.18-.352-.28-.672-.44z" fill="#fff" />
              </svg>
            </a>
          @endif
        </div>
      </div>
    </div>
  </div>
  </div>
  <div class="bg-[color:var(--color-primary)] text-white/80 py-3">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 text-sm">&copy; {{ date('Y') }} {{ config('contacts.company_name') }}</div>
  </div>
</footer>


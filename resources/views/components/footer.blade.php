@php
$year = date('Y');
$footerLinksClass = 'type-text-md transition-color-figma block py-2 text-white hover:underline underline-offset-4';
@endphp

<footer class="pt-10 text-white">
  <div class="redesign-footer-pattern overflow-hidden rounded-t-[24px] px-4 py-12 md:px-16 md:py-16">
    <div class="mx-auto flex w-full max-w-[1280px] flex-col gap-10 md:gap-12 md:px-8">

      <div class="flex flex-col gap-10 md:flex-row md:items-start md:justify-between">
        <div class="w-full md:max-w-[360px]">
          <a href="{{ route('home') }}" class="inline-flex items-center gap-2" aria-label="Irsko Study domů">
            <img src="{{ asset('img/logo-irsko-white.svg') }}" alt="Irsko" class="h-10 w-auto" loading="lazy" decoding="async">
            <span class="type-decorative text-[28px] leading-7 text-brand-orange sm:text-[32px]">Study</span>
          </a>
          <p class="type-text-md mt-6 max-w-[300px] text-white">
            Pomáháme studentům z ČR a SK najít a zařídit studium v Irsku.
          </p>
        </div>

        <div class="grid w-full grid-cols-1 gap-10 md:w-auto md:grid-cols-3 md:gap-16">
          <div>
            <h3 class="type-decorative text-[24px] leading-6 text-brand-light-green md:text-[16px]">Studium</h3>
            <div class="mt-2 flex flex-col">
              <a href="{{ route('about') }}" class="{{ $footerLinksClass }}">O nás</a>
              <a href="{{ route('why') }}" class="{{ $footerLinksClass }}">Proč Irsko</a>
              <a href="{{ route('universities') }}" class="{{ $footerLinksClass }}">Vysoké školy</a>
              <a href="{{ route('finder.courses') }}" class="{{ $footerLinksClass }}">Kurzy</a>
              <a href="{{ route('services') }}" class="{{ $footerLinksClass }}">Služby</a>
            </div>
          </div>
          <div>
            <h3 class="type-decorative text-[24px] leading-6 text-brand-light-green md:text-[16px]">Informace</h3>
            <div class="mt-2 flex flex-col">
              <a href="{{ route('faq') }}" class="{{ $footerLinksClass }}">Co tě zajímá</a>
              <a href="{{ route('blog') }}" class="{{ $footerLinksClass }}">Blog</a>
              <a href="{{ route('sitemap') }}" class="{{ $footerLinksClass }}">Sitemap</a>
            </div>
          </div>
          <div>
            <h3 class="type-decorative text-[24px] leading-6 text-brand-light-green md:text-[16px]">Kontakt</h3>
            <div class="mt-2 flex flex-col">
              <a href="mailto:{{ config('contacts.email') }}" class="{{ $footerLinksClass }}">{{ config('contacts.email') }}</a>
              <a href="tel:{{ config('contacts.mobile') }}" class="{{ $footerLinksClass }}">{{ config('contacts.mobile') }}</a>
              <a href="{{ route('contact') }}" class="{{ $footerLinksClass }}">Napište nám</a>
            </div>
          </div>
        </div>
      </div>

      @if(config('contacts.instagram') || config('contacts.whatsapp'))
      <div class="flex items-center gap-4">
        @if(config('contacts.instagram'))
        <a href="{{ config('contacts.instagram') }}" target="_blank" rel="noopener" aria-label="Instagram" class="transition-color-figma inline-flex h-8 w-8 items-center justify-center text-white hover:text-brand-light-green">
          <svg viewBox="0 0 24 24" class="h-6 w-6" fill="currentColor" aria-hidden="true"><path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2Zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5a4.25 4.25 0 0 0 4.25 4.25h8.5a4.25 4.25 0 0 0 4.25-4.25v-8.5a4.25 4.25 0 0 0-4.25-4.25h-8.5Zm9.75 1.75a1 1 0 1 1 0 2 1 1 0 0 1 0-2ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 1.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z"/></svg>
        </a>
        @endif
        @if(config('contacts.whatsapp'))
        <a href="{{ strpos(config('contacts.whatsapp'),'http') === 0 ? config('contacts.whatsapp') : 'https://wa.me/message/' . config('contacts.whatsapp') }}" target="_blank" rel="noopener" aria-label="WhatsApp" class="transition-color-figma inline-flex h-8 w-8 items-center justify-center text-white hover:text-brand-light-green">
          <svg viewBox="0 0 24 24" class="h-6 w-6" fill="currentColor" aria-hidden="true"><path d="M20.52 3.48A11.95 11.95 0 0 0 12 0C5.373 0 0 5.373 0 12c0 2.115.552 4.094 1.6 5.86L0 24l6.4-1.6A11.95 11.95 0 0 0 12 24c6.627 0 12-5.373 12-12 0-3.2-1.248-6.218-3.48-8.52ZM12 21.6c-1.6 0-3.152-.384-4.544-1.112l-.32-.176L4 20l.68-2.96-.192-.32A9.6 9.6 0 0 1 2.4 12c0-5.28 4.32-9.6 9.6-9.6 2.56 0 4.96.96 6.8 2.72A9.36 9.36 0 0 1 21.6 12c0 5.28-4.32 9.6-9.6 9.6Zm5.04-7.2c-.32-.16-1.92-.96-2.24-1.04-.32-.08-.56-.16-.8.16-.24.32-.96 1.04-1.18 1.28-.22.24-.44.28-.8.12-.36-.16-1.52-.56-2.88-1.78-1.06-.94-1.76-2.08-1.98-2.44-.22-.36-.02-.56.16-.72.16-.16.36-.44.54-.64.18-.2.24-.36.36-.6.12-.24.04-.44-.02-.6-.06-.16-.8-1.92-1.12-2.64-.28-.68-.56-.6-.8-.6l-.68.04c-.24 0-.64.08-.96.4-.32.32-1.28 1.24-1.28 3.04 0 1.8 1.32 3.56 1.5 3.8.18.24 2.6 3.96 6.44 5.56 3.84 1.6 3.84 1.08 4.56 1.02.72-.06 2.32-.94 2.64-1.84.32-.9.32-1.66.224-1.84-.096-.18-.352-.28-.672-.44Z"/></svg>
        </a>
        @endif
      </div>
      @endif

      <div class="border-t border-white/20 pt-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
          <span class="type-text-sm text-white">© {{ $year }} {{ config('contacts.company_name') }}, všechna práva vyhrazena</span>
          <a href="{{ route('privacy.cs') }}" class="type-text-sm transition-color-figma text-white hover:underline underline-offset-4">Ochrana údajů</a>
        </div>
      </div>
    </div>
  </div>
</footer>

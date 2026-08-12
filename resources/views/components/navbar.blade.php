@php
$routeName = Route::currentRouteName() ?? '';

$photoHeroRoutes = ['finder.courses', 'finder.course.show', 'finder.school.show'];
$isPhotoHero = in_array($routeName, $photoHeroRoutes, true);

$solidDarkRoutes = ['home'];
$isSolidDark = in_array($routeName, $solidDarkRoutes, true);

$isRouteActive = function (string $name) use ($routeName): bool {
    return match ($name) {
        'home' => $routeName === 'home',
        'about' => $routeName === 'about',
        'why' => $routeName === 'why',
        'universities' => $routeName === 'universities' || $routeName === 'finder.school.show',
        'kurzy' => in_array($routeName, ['finder.courses', 'finder.course.show', 'finder.search']),
        'services' => $routeName === 'services',
        'blog' => str_starts_with($routeName, 'blog'),
        'faq' => $routeName === 'faq',
        'contact' => $routeName === 'contact',
        default => false,
    };
};

$desktopLinks = [
    ['name' => 'about', 'label' => 'O nás', 'href' => route('about')],
    ['name' => 'why', 'label' => 'Proč Irsko', 'href' => route('why')],
    ['name' => 'universities', 'label' => 'Vysoké školy', 'href' => route('universities')],
    ['name' => 'kurzy', 'label' => 'Kurzy', 'href' => route('finder.courses')],
    ['name' => 'services', 'label' => 'Služby', 'href' => route('services')],
    ['name' => 'blog', 'label' => 'Blog', 'href' => route('blog')],
    ['name' => 'faq', 'label' => 'Co tě zajímá', 'href' => route('faq')],
];

$mobileLinks = [
    ['name' => 'home', 'label' => 'Domů', 'href' => route('home')],
    ...$desktopLinks,
    ['name' => 'contact', 'label' => 'Kontakt', 'href' => route('contact')],
];
@endphp

<header
  id="redesign-nav"
  v-cloak
  @keydown.escape.window="mobileOpen = false"
  class="{{ $isPhotoHero ? 'absolute inset-x-0 top-0 z-50' : 'sticky top-0 z-50' }} {{ $isSolidDark ? 'bg-brand-dark-green' : '' }}"
>
  <div class="px-2 py-2 md:px-4">
    <div class="flex items-center rounded-[16px] bg-brand-dark-green p-4 md:h-[72px] md:px-4 md:py-0">
      <div class="flex min-w-0 flex-1 items-center pl-2">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2" aria-label="Irsko Study domů">
          <img src="{{ asset('img/logo-irsko-white.svg') }}" alt="Irsko" class="h-9 w-auto" loading="eager" decoding="async">
          <span class="type-decorative text-[28px] leading-7 text-brand-orange sm:text-[32px]">Study</span>
        </a>
      </div>

      <nav aria-label="Hlavní navigace" class="hidden items-center lg:flex">
        @foreach ($desktopLinks as $link)
          <a
            href="{{ $link['href'] }}"
            @class([
              'type-text-md transition-color-figma flex items-center gap-1 px-3 py-4 hover:text-brand-light-green xl:px-4',
              'text-brand-light-green' => $isRouteActive($link['name']),
              'text-white' => !$isRouteActive($link['name']),
            ])
          >
            <span>{{ $link['label'] }}</span>
          </a>
        @endforeach
      </nav>

      <div class="hidden min-w-0 flex-1 justify-end lg:flex">
        <a
          href="{{ route('contact') }}"
          class="type-input-label transition-color-figma inline-flex items-center justify-center rounded-[var(--layout-radius-md)] border border-brand-light-gray px-8 py-4 text-brand-light-gray hover:bg-white/10"
        >
          Konzultace zdarma
        </a>
      </div>

      <div class="flex min-w-0 flex-1 justify-end lg:hidden">
        <button
          type="button"
          class="transition-color-figma inline-flex h-8 w-8 items-center justify-center text-white hover:text-brand-light-green"
          :aria-label="mobileOpen ? 'Zavřít menu' : 'Otevřít menu'"
          @click="mobileOpen = !mobileOpen"
        >
          <template v-if="!mobileOpen">
            <svg class="h-8 w-8" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <path d="M7 10.5H25" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
              <path d="M7 16H25" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
              <path d="M7 21.5H25" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
            </svg>
          </template>
          <template v-else>
            <svg class="h-8 w-8" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <path d="M10 10L22 22" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
              <path d="M22 10L10 22" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
            </svg>
          </template>
        </button>
      </div>
    </div>
  </div>

  <transition name="redesign-slide-down">
    <div v-show="mobileOpen" class="px-2 pb-2 lg:hidden">
      <div class="rounded-[16px] bg-brand-dark-green px-4 pb-4 pt-2">
        <nav aria-label="Mobilní navigace" class="flex flex-col items-stretch">
          @foreach ($mobileLinks as $link)
            <a
              href="{{ $link['href'] }}"
              @class([
                'type-text-md transition-color-figma flex items-center justify-center gap-1 py-3 hover:text-brand-light-green',
                'text-brand-light-green' => $isRouteActive($link['name']),
                'text-white' => !$isRouteActive($link['name']),
              ])
              @click="mobileOpen = false"
            >{{ $link['label'] }}</a>
          @endforeach
        </nav>

        <div class="mt-4 rounded-[8px] bg-black/20 p-4 text-center text-white">
          <a href="mailto:{{ config('contacts.email') }}" class="type-text-sm transition-color-figma block hover:text-brand-light-green">{{ config('contacts.email') }}</a>
          <a href="tel:{{ config('contacts.mobile') }}" class="type-text-sm transition-color-figma mt-2 block hover:text-brand-light-green">{{ config('contacts.mobile') }}</a>
        </div>
      </div>
    </div>
  </transition>
</header>

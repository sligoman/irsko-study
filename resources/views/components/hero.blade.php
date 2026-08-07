<section data-redesign-section="hero-stats" class="relative overflow-hidden rounded-b-[24px] bg-brand-dark-green text-white">
  <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(255,120,46,0.22),transparent_30%),radial-gradient(circle_at_82%_24%,rgba(156,204,87,0.18),transparent_28%)]"></div>
  <div class="absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-black/20 to-transparent"></div>

  <div class="layout-container relative grid min-h-[720px] gap-10 pb-12 pt-32 lg:grid-cols-12 lg:items-center lg:pt-40">
    <div class="lg:col-span-7">
      <p class="type-text-md-semibold inline-flex rounded-full bg-white/10 px-4 py-2 text-white ring-1 ring-white/15">Najdeš nás přímo v Irsku</p>
      <h1 class="type-display-2xl mt-6 max-w-[820px] text-white">
        Studium v Irsku <span class="text-brand-orange">jednoduše</span> a bez starostí
      </h1>
      <p class="type-text-lg mt-6 max-w-[650px] text-white/85">Pomůžeme ti od první konzultace až po první den na kampusu: výběr školy, přihlášky, dokumenty, ubytování i praktická podpora po příjezdu.</p>

      <div class="mt-10 flex flex-col gap-4 sm:flex-row">
        <a href="{{ route('contact') }}" class="type-input-label transition-color-figma inline-flex items-center justify-center rounded-[8px] bg-brand-light-green px-8 py-4 text-brand-dark-green hover:bg-[#7db709]">Domluv si bezplatnou konzultaci</a>
        <a href="{{ route('why') }}" class="type-input-label transition-color-figma inline-flex items-center justify-center rounded-[8px] border border-white/35 px-8 py-4 text-white hover:bg-white/10">Proč studovat v Irsku</a>
      </div>
    </div>

    <div class="lg:col-span-5">
      <div class="relative mx-auto max-w-[520px]">
        <div class="absolute -left-6 -top-6 hidden h-24 w-24 rounded-full bg-brand-orange/80 md:block"></div>
        <div class="absolute -bottom-6 -right-6 hidden h-32 w-32 rounded-full border border-white/25 md:block"></div>
        <div class="relative rounded-[16px] bg-white/10 p-3 shadow-2xl ring-1 ring-white/15 backdrop-blur">
          <university-slideshow></university-slideshow>
        </div>
      </div>
    </div>

    <div class="lg:col-span-12">
      <div class="mx-auto mt-4 grid max-w-[880px] gap-4 sm:grid-cols-3 sm:gap-8 lg:mt-10">
        <div class="text-center sm:border-r sm:border-white/20 sm:pr-8">
          <p class="type-display-xs text-white">A-Z</p>
          <p class="type-text-sm mt-1 text-white/80">servis při přihlášce</p>
        </div>
        <div class="text-center sm:border-r sm:border-white/20 sm:px-8">
          <p class="type-display-xs text-white">1:1</p>
          <p class="type-text-sm mt-1 text-white/80">osobní podpora v Irsku</p>
        </div>
        <div class="text-center sm:pl-8">
          <p class="type-display-xs text-white">CZ/SK</p>
          <p class="type-text-sm mt-1 text-white/80">průvodce pro studenty</p>
        </div>
      </div>
    </div>
  </div>
</section>

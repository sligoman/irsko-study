<section data-redesign-section="student-video" class="home-section bg-white">
  <div class="layout-container">
    <div class="grid gap-8 lg:grid-cols-[0.85fr_1.15fr] lg:items-center">
      <div>
        <p class="type-text-md-semibold text-brand-orange">Reference studentů</p>
        <h2 class="type-display-lg mt-2 text-brand-dark-green">Podívej se, jak vypadá cesta do Irska očima studentů</h2>
        <p class="type-text-lg mt-6 text-brand-dark-green">Krátké video z původního Irsko Study webu jsme ponechali i v redesignu, protože nejlépe ukazuje atmosféru a osobní přístup, na kterém služba stojí.</p>
      </div>

      <div class="relative">
        <div class="absolute -left-4 -top-4 h-20 w-20 rounded-full bg-brand-orange/80 md:-left-6 md:-top-6"></div>
        <div class="absolute -bottom-5 -right-5 h-24 w-24 rounded-full bg-brand-light-green/70 md:-bottom-7 md:-right-7"></div>
        <div class="relative overflow-hidden rounded-[16px] bg-brand-dark-green p-3 shadow-xl">
          <video class="aspect-video w-full rounded-[12px] bg-black object-cover" controls preload="metadata">
            <source src="{{ asset('img/video.mp4') }}" type="video/mp4">
            Váš prohlížeč nepodporuje přehrávání videa.
          </video>
        </div>
      </div>
    </div>
  </div>
</section>

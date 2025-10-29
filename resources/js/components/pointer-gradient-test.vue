<template>
  <section class="pointer-gradient relative overflow-hidden rounded-lg p-8 my-8" data-parallax-intensity="30">
    <!-- Decorative background so overlay is visible even with light content -->
    <div class="absolute inset-0 bg-gradient-to-br from-sky-50 to-emerald-50 pointer-events-none" aria-hidden="true"></div>

    <div class="relative z-10">
      <h3 class="text-2xl font-semibold mb-2">Pointer gradient test</h3>
      <p class="text-sm text-slate-600">Move your mouse across this box to test the pointer-driven radial tint and parallax effect.</p>

      <div class="mt-6 w-56 h-28 bg-white/60 rounded parallax" data-parallax-scale="12"></div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'PointerGradientTest'
}
</script>

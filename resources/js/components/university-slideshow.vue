<template>
  <div class="w-full md:max-w-xl mx-auto">
      <div class="hidden md:block md:absolute z-50 -bottom-6 left-6 w-64 bg-white rounded-xl p-4 shadow-md border transform transition duration-500 motion-safe:translate-y-0 group-hover:translate-y-[-6px] z-20">
        <!-- <div class="text-xs text-gray-500">Nejpopulárnější</div> -->
        <div class="mt-1 font-semibold text-[color:var(--color-primary)]">{{ current ? current.name : '…' }}</div>
        <div class="mt-1 text-sm text-gray-600">{{ current ? current.description : '' }}</div>
      </div>
    <div class="relative bg-white rounded-xl overflow-hidden shadow-lg">
          <!-- badge positioned over the slideshow (bottom-left) -->

          <div class="relative h-64 sm:h-80 bg-gray-100 overflow-hidden" @mouseenter="stop" @mouseleave="start">
            <!-- layered images for crossfade -->
            <template v-for="(s, i) in slides" :key="s.file + '-' + i">
              <img
                v-if="i === index || i === prevIndex"
                :src="imageUrl(s.file)"
                :alt="s.name"
                class="absolute inset-0 w-full h-full object-cover transition-transform ease-in-out"
                :style="computeStyle(i)"
              />
            </template>

            <!-- dot controls (optional) -->
            <div class="absolute bottom-3 right-3 flex gap-2 z-30">
              <button v-for="(s, i) in slides" :key="s.file + '-dot-' + i" @click="goTo(i)" :class="['w-2 h-2 rounded-full', i === index ? 'bg-[color:var(--color-primary)]' : 'bg-gray-300']" aria-label="Go to slide"></button>
            </div>
          </div>

      <div class="md:hidden p-4">
        <h3 class="text-lg font-semibold text-[color:var(--color-primary)]">{{ current ? current.name : '…' }}</h3>
        <p class="text-sm text-gray-600 mt-1">{{ current ? current.description : '' }}</p>

        <!-- <div class="mt-3 flex items-center gap-2">
          <button v-for="(s, i) in slides" :key="s.file" @click="goTo(i)" :class="['w-2 h-2 rounded-full', i === index ? 'bg-[color:var(--color-primary)]' : 'bg-gray-300']" aria-label="Go to slide"></button>
        </div> -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UniversitySlideshow',
  data() {
    return {
      slides: [],
      index: 0,
      prevIndex: null,
      lastDirection: 'left',
      timer: null,
      interval: 4000,
      transitionDuration: 1200 // ms - keep in sync with CSS duration
    };
  },
  computed: {
    current() {
      return this.slides.length ? this.slides[this.index] : null;
    }
  },
  methods: {
    imageUrl(file) {
      return '/img/blog/medium/' + file;
    },
    async load() {
      try {
        const res = await fetch('/img/universities/universities.json', { cache: 'no-store' });
        if (res.ok) {
          this.slides = await res.json();
          // preload images for smoother transitions
          this.preloadImages();
        }
      } catch (e) {
        // ignore
        console.error('Failed to load university slideshow JSON', e);
      }
    },
    preloadImages() {
      if (!this.slides || !this.slides.length) return;
      this.slides.forEach(s => {
        try { const img = new Image(); img.src = this.imageUrl(s.file); } catch (e) { }
      });
    },
    setIndex(newIndex) {
      if (!this.slides.length) return;
      newIndex = (newIndex + this.slides.length) % this.slides.length;
      if (newIndex === this.index) return;
      // determine direction: forward (left) or backward (right)
      const delta = (newIndex - this.index + this.slides.length) % this.slides.length;
      this.lastDirection = delta <= (this.slides.length / 2) ? 'left' : 'right';
      // keep previous index visible to allow slide animation
      this.prevIndex = this.index;
      this.index = newIndex;
      this.emitSlideChange();
      // clear prevIndex after transition completes
      const ms = this.transitionDuration + 50;
      setTimeout(() => { this.prevIndex = null; }, ms);
    },
    computeStyle(i) {
      const dur = this.transitionDuration + 'ms';
      const z = i === this.index ? 20 : 10;
      // incoming (current)
      if (i === this.index) {
        return { zIndex: z, transitionDuration: dur, transform: 'translateX(0%)' };
      }
      // outgoing (previous)
      if (i === this.prevIndex) {
        if (this.lastDirection === 'left') {
          // moved forward: previous slides out to left
          return { zIndex: z, transitionDuration: dur, transform: 'translateX(-100%)' };
        }
        // moved backward: previous slides out to right
        return { zIndex: z, transitionDuration: dur, transform: 'translateX(100%)' };
      }
      return { zIndex: 5, transitionDuration: dur, transform: 'translateX(100%)' };
    },
    next() {
      if (!this.slides.length) return;
      this.setIndex(this.index + 1);
    },
    prev() {
      if (!this.slides.length) return;
      this.setIndex(this.index - 1);
    },
    goTo(i) {
      if (i >= 0 && i < this.slides.length) this.setIndex(i);
    },
    start() {
      this.stop();
      this.timer = setInterval(this.next, this.interval);
    },
    stop() {
      if (this.timer) { clearInterval(this.timer); this.timer = null; }
    }
    ,emitSlideChange() {
      // Dispatch a DOM event so outer non-Vue markup can react and update the overlay
      try {
        const detail = this.current ? { ...this.current } : null;
        window.dispatchEvent(new CustomEvent('universitySlideChange', { detail }));
      } catch (e) {
        // ignore
      }
    }
  },
  mounted() {
    this.load().then(() => {
      if (this.slides.length) this.start();
      // emit initial slide so outer UI can pick up title/description
      this.emitSlideChange();
    });
    window.addEventListener('visibilitychange', () => {
      if (document.hidden) this.stop(); else this.start();
    });
  },
  beforeUnmount() {
    this.stop();
  }
};
</script>

<style scoped>
/* little styling for the dot buttons */
.dot { width: 8px; height: 8px; border-radius: 9999px; }
</style>

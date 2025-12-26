<template>
  <section class="instagram-widget my-12">
    <div class="max-w-6xl mx-auto px-4">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold">Instagram</h2>
        <a href="https://instagram.com/" target="_blank" class="text-sm text-gray-500 hover:text-gray-700">Follow on Instagram →</a>
      </div>

      <div v-if="loading" class="text-center py-12">Loading...</div>

      <div v-else class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 auto-rows-fr">
        <button v-for="(post, idx) in posts" :key="post.id" @click.prevent="openModal(post)" class="group relative block overflow-hidden rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-400">
          <div :class="['w-full h-full bg-gray-100 overflow-hidden', idx === 0 ? 'lg:col-span-2 lg:row-span-2' : '']">
            <img :src="post.image" :alt="post.caption" class="object-cover w-full h-full transform transition-transform duration-500 group-hover:scale-105" />
            <div class="absolute inset-0 flex items-end bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
              <div class="p-3 w-full text-white">
                <div class="flex items-center justify-between">
                  <p class="text-sm line-clamp-2">{{ post.caption }}</p>
                  <svg class="w-5 h-5 text-white opacity-90" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2C4.238 2 2 4.238 2 7v10c0 2.762 2.238 5 5 5h10c2.762 0 5-2.238 5-5V7c0-2.762-2.238-5-5-5H7zm10 2a3 3 0 013 3v10a3 3 0 01-3 3H7a3 3 0 01-3-3V7a3 3 0 013-3h10z"/></svg>
                </div>
              </div>
            </div>
          </div>
        </button>
      </div>

      <noscript>
        <div class="grid grid-cols-3 gap-3 mt-4">
          <!-- <img src="/img/blog/placeholder1.jpg" alt="instagram" /> -->
        </div>
      </noscript>
    </div>
    <!-- Modal / Lightbox -->
    <div v-if="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4" @click.self="closeModal">
      <div class="max-w-4xl w-full bg-white rounded-lg overflow-hidden shadow-lg">
        <div class="relative">
          <button @click="closeModal" class="absolute top-3 right-3 z-10 bg-white/80 rounded-full p-2 hover:bg-white">
            <svg class="w-5 h-5 text-gray-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
          <img :src="selected.image" :alt="selected.caption" class="w-full max-h-[80vh] object-contain bg-black" />
        </div>
        <div class="p-4">
          <p class="text-gray-800">{{ selected.caption }}</p>
          <div class="mt-3 flex items-center justify-end">
            <a :href="selected.link" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-sm text-pink-600 hover:underline">Open on Instagram
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M14 3h7v7h-2V6.414l-9.293 9.293-1.414-1.414L17.586 5H14V3z"/></svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'InstagramWidget',
  data() {
    return {
      posts: [],
      loading: true,
      modalOpen: false,
      selected: {},
    };
  },
  async mounted() {
    try {
      const res = await fetch('/api/instagram/feed');
      if (res.ok) {
        const json = await res.json();
        const items = json.items || [];
        // map API shape to component-friendly shape
        this.posts = items.map(i => ({
          id: i.id,
          image: i.media_url || i.thumbnail_url,
          thumbnail: i.thumbnail_url || i.media_url,
          caption: i.caption,
          link: i.permalink,
          media_type: i.media_type,
          timestamp: i.timestamp,
        }));
      } else {
        console.error('Failed to fetch instagram posts', res.status);
      }
    } catch (e) {
      console.error(e);
    } finally {
      this.loading = false;
    }

    document.addEventListener('keydown', this.handleKeydown);
  },
  unmounted() {
    document.removeEventListener('keydown', this.handleKeydown);
  },
  methods: {
    openModal(post) {
      this.selected = post || {};
      this.modalOpen = true;
    },
    closeModal() {
      this.modalOpen = false;
      this.selected = {};
    },
    handleKeydown(e) {
      if (e.key === 'Escape' && this.modalOpen) {
        this.closeModal();
      }
    },
  },
};
</script>

<style scoped>
.instagram-widget img { min-height: 140px; }
.instagram-widget button { cursor: pointer; }
.instagram-widget .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>

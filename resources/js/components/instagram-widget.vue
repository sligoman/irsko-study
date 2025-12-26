<template>
  <section class="instagram-widget my-12">
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold">Instagram</h2>
        <a href="https://instagram.com/" target="_blank" class="text-sm text-gray-500 hover:text-gray-700">Follow on Instagram →</a>
      </div>

      <div v-if="loading" class="text-center py-12">Loading...</div>

      <div v-else class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <a v-for="post in posts" :key="post.id" :href="post.link" target="_blank" rel="noopener" class="group block overflow-hidden rounded-lg">
          <div class="relative w-full aspect-square bg-gray-100">
            <img :src="post.image" :alt="post.caption" class="object-cover w-full h-full transform transition-transform duration-500 group-hover:scale-105" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
              <p class="p-3 text-white text-sm line-clamp-2">{{ post.caption }}</p>
            </div>
          </div>
        </a>
      </div>

      <noscript>
        <div class="grid grid-cols-3 gap-3 mt-4">
          <!-- <img src="/img/blog/placeholder1.jpg" alt="instagram" /> -->
        </div>
      </noscript>
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
    };
  },
  async mounted() {
    try {
      const res = await fetch('/api/instagram');
      if (res.ok) {
        this.posts = await res.json();
      } else {
        console.error('Failed to fetch instagram posts');
      }
    } catch (e) {
      console.error(e);
    } finally {
      this.loading = false;
    }
  },
};
</script>

<style scoped>
.instagram-widget img { min-height: 120px; }
</style>

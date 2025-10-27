<template>
  <div>
    <div v-if="posts.length === 0" class="text-gray-600">Žádné články zatím.</div>
    <ul v-else class="space-y-2">
      <li v-for="post in posts" :key="post.id" class="p-3 border rounded bg-white">
        <h4 class="font-semibold">{{ post.title }}</h4>
        <p class="text-sm text-gray-600">{{ post.excerpt }}</p>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'BlogDynamic',
  data() {
    return { posts: [] };
  },
  mounted() {
    // placeholder: in a real app fetch from API
    this.posts = [
      { id: 1, title: 'Jak podat přihlášku', excerpt: 'Krátký návod, krok za krokem.' },
      { id: 2, title: 'Práce při studiu', excerpt: 'Možnosti a pravidla pro studenty.' },
    ];
  },
};
</script>

<style scoped>
</style>
        <p class="text-sm text-gray-600">{{ post.excerpt }}</p>

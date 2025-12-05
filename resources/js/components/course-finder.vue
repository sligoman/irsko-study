<template>
  <div class="bg-white rounded-lg shadow p-6">
    <div class="flex flex-col lg:flex-row gap-4 lg:items-end lg:justify-between mb-6">
      <div class="flex-1">
        <label class="block text-sm font-medium text-gray-700">Hledat</label>
        <input v-model="filters.q" @input="debouncedSearch" type="search" placeholder="Např. engineering, business, psychology" class="mt-1 block w-full rounded border-gray-200 shadow-sm focus:ring-2 focus:ring-emerald-400" />
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 w-full lg:w-2/3">
        <div>
          <label class="block text-sm font-medium text-gray-700">Škola</label>
          <select v-model="filters.school" @change="search" class="mt-1 block w-full rounded border-gray-200 p-2">
            <option value="">Všechny školy</option>
            <option v-for="s in initial.schools" :key="s.id" :value="s.id">{{ s.name }}</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Obor</label>
          <select v-model="filters.field" @change="search" class="mt-1 block w-full rounded border-gray-200 p-2">
            <option value="">Všechny obory</option>
            <option v-for="f in initial.fields" :key="f.id" :value="f.id">{{ f.name }}</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Úroveň</label>
          <select v-model="filters.level" @change="search" class="mt-1 block w-full rounded border-gray-200 p-2">
            <option value="">Všechny úrovně</option>
            <option v-for="l in initial.levels" :key="l.id" :value="l.id">{{ l.name }}</option>
          </select>
        </div>
      </div>

      <div class="flex items-center gap-3 mt-4 lg:mt-0">
        <button @click="clearFilters" class="px-4 py-2 bg-gray-100 rounded">Vymazat</button>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <article v-for="course in results.data" :key="course.id" class="bg-gray-50 rounded-lg p-4 border">        
        <h3 class="text-lg font-semibold mb-1">{{ course.title_cs || course.title_en }}</h3>
        <div v-if="course.title_cs && course.title_en" class="text-sm text-gray-600 mb-2">{{ course.title_en }}</div>
        <div v-else class="text-sm text-gray-600 mb-2">{{ course.school ? course.school.name : '' }}</div>
        <p class="text-gray-700 text-sm mb-3" v-html="truncate(course.description_cs || course.description_en, 200)"></p>
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-600">Požadované body: <span class="font-medium">{{ course.points_required || '—' }}</span></div>
          <a :href="courseLink(course.id)" class="text-emerald-600 hover:underline text-sm">Více</a>
        </div>
      </article>
    </div>

    <div class="mt-6 flex justify-center">
      <nav class="inline-flex -space-x-px rounded-md shadow-sm" v-if="results.meta && results.meta.last_page > 1" aria-label="Pagination">
        <button @click="goto(results.meta.current_page - 1)" :disabled="results.meta.current_page <= 1" class="px-3 py-2 bg-white border">«</button>
        <button v-for="p in pagesToShow" :key="p" @click="goto(p)" :class="['px-3 py-2 border', { 'bg-emerald-500 text-white': p === results.meta.current_page, 'bg-white': p !== results.meta.current_page }]">{{ p }}</button>
        <button @click="goto(results.meta.current_page + 1)" :disabled="results.meta.current_page >= results.meta.last_page" class="px-3 py-2 bg-white border">»</button>
      </nav>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CourseFinder',
  props: {
    initialData: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      initial: this.initialData,
      filters: {
        q: '',
        school: '',
        field: '',
        level: '',
        page: 1,
      },
      results: this.initialData.results || { data: [], meta: { current_page: 1, last_page: 1 } },
      debounceTimer: null,
    };
  },
  computed: {
    pagesToShow() {
      const meta = this.results.meta || {};
      const last = meta.last_page || 1;
      const current = meta.current_page || 1;
      const pages = [];
      const start = Math.max(1, current - 2);
      const end = Math.min(last, current + 2);
      for (let i = start; i <= end; i++) pages.push(i);
      return pages;
    },
  },
  methods: {
    courseLink(id) {
      return `/finder/courses/${id}`;
    },
    truncate(text, n) {
      if (!text) return '';
      return text.length > n ? text.substring(0, n) + '…' : text;
    },
    async search() {
      try {
        const params = {
          q: this.filters.q || undefined,
          school: this.filters.school || undefined,
          field: this.filters.field || undefined,
          level: this.filters.level || undefined,
          page: this.filters.page || 1,
        };

        const res = await window.axios.get('/finder/search', { params });
        this.results = res.data;
      } catch (e) {
        console.error('Search failed', e);
      }
    },
    debouncedSearch() {
      clearTimeout(this.debounceTimer);
      this.debounceTimer = setTimeout(() => this.search(), 450);
    },
    clearFilters() {
      this.filters = { q: '', school: '', field: '', level: '', page: 1 };
      this.search();
    },
    goto(page) {
      if (!this.results.meta) return;
      page = Math.max(1, Math.min(page, this.results.meta.last_page));
      this.filters.page = page;
      this.search();
    },
  },
};
</script>

<!-- Use Tailwind utility classes instead of resolving theme() in component CSS -->


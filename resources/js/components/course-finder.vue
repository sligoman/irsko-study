<template>
  <div>
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
      <div class="flex-1">
        <label class="type-text-md font-medium text-brand-dark-green">Hledat</label>
        <input
          v-model="filters.q"
          @input="debouncedSearch"
          type="search"
          placeholder="Např. engineering, business, psychology"
          class="mt-1 block w-full rounded-[8px] border border-brand-dark-green/20 bg-white px-4 py-3 text-brand-dark-green placeholder:text-[var(--color-utility-text-placeholder)] focus:outline-none focus:ring-2 focus:ring-brand-light-green"
        />
      </div>

      <div class="grid w-full grid-cols-1 gap-3 sm:grid-cols-3 lg:w-2/3">
        <div>
          <label class="type-text-md font-medium text-brand-dark-green">Škola</label>
          <select v-model="filters.school" @change="search" class="mt-1 block w-full rounded-[8px] border border-brand-dark-green/20 bg-white px-3 py-3 text-brand-dark-green focus:outline-none focus:ring-2 focus:ring-brand-light-green">
            <option value="">Všechny školy</option>
            <option v-for="s in initial.schools" :key="s.id" :value="s.school_id">{{ s.name }}</option>
          </select>
        </div>

        <div>
          <label class="type-text-md font-medium text-brand-dark-green">Obor</label>
          <select v-model="filters.field" @change="search" class="mt-1 block w-full rounded-[8px] border border-brand-dark-green/20 bg-white px-3 py-3 text-brand-dark-green focus:outline-none focus:ring-2 focus:ring-brand-light-green">
            <option value="">Všechny obory</option>
            <option v-for="f in initial.fields" :key="f.id" :value="f.id">{{ f.description }}</option>
          </select>
        </div>

        <div>
          <label class="type-text-md font-medium text-brand-dark-green">Úroveň</label>
          <select v-model="filters.level" @change="search" class="mt-1 block w-full rounded-[8px] border border-brand-dark-green/20 bg-white px-3 py-3 text-brand-dark-green focus:outline-none focus:ring-2 focus:ring-brand-light-green">
            <option value="">Všechny úrovně</option>
            <option v-for="l in initial.levels" :key="l.id" :value="l.id">{{ l.name }}</option>
          </select>
        </div>
      </div>

      <div class="mt-4 flex items-center gap-3 lg:mt-0">
        <button @click="clearFilters" class="type-input-label transition-color-figma inline-flex items-center justify-center rounded-[8px] border border-brand-dark-green/30 px-6 py-3 text-brand-dark-green hover:bg-white">Vymazat</button>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
      <article v-for="course in results.data" :key="course.id" class="flex h-full flex-col overflow-hidden rounded-[16px] bg-white ring-1 ring-brand-dark-green/10 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
        <div class="flex flex-1 flex-col p-6">
          <p class="type-text-sm text-brand-orange">{{ course.school ? course.school.name : 'Kurz' }}</p>
          <h3 class="type-display-xs mt-2 text-brand-dark-green">{{ course.title_cs || course.title_en }}</h3>
          <div class="type-text-md mt-3 text-brand-dark-green/80" v-html="truncate(course.description_cs || course.description_en, 200)"></div>
          <a :href="courseLink(course)" class="type-text-sm mt-4 text-brand-dark-green hover:text-brand-orange">Zobrazit detail →</a>
        </div>
      </article>
    </div>

    <div v-if="results.meta && results.meta.last_page > 1" class="mt-8 flex justify-center">
      <nav class="inline-flex gap-2" aria-label="Stránkování">
        <button @click="goto(results.meta.current_page - 1)" :disabled="results.meta.current_page <= 1" class="type-input-label inline-flex h-11 w-11 items-center justify-center rounded-[8px] border border-brand-dark-green/20 text-brand-dark-green disabled:opacity-40">«</button>
        <button
          v-for="p in pagesToShow"
          :key="p"
          @click="goto(p)"
          :class="[
            'type-input-label inline-flex h-11 w-11 items-center justify-center rounded-[8px]',
            p === results.meta.current_page ? 'bg-brand-dark-green text-white' : 'border border-brand-dark-green/20 text-brand-dark-green hover:bg-brand-light-green',
          ]"
        >{{ p }}</button>
        <button @click="goto(results.meta.current_page + 1)" :disabled="results.meta.current_page >= results.meta.last_page" class="type-input-label inline-flex h-11 w-11 items-center justify-center rounded-[8px] border border-brand-dark-green/20 text-brand-dark-green disabled:opacity-40">»</button>
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
      filters: Object.assign({ q: '', school: '', field: '', level: '', page: 1 }, this.initialData.filters || {}),
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
    courseLink(course) {
      // prefer slug: {school_school_id}-{course_code}
      return `/kurzy/${course.url}`;
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

        const res = await window.axios.get('/kurzy/hledat', { params });
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

<style scoped>
</style>

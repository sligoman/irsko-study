<template>
  <div class="space-y-4">
    <div v-for="(item, idx) in items" :key="idx" class="bg-white p-4 rounded shadow">
      <button @click="toggle(idx)" class="w-full text-left flex justify-between items-center">
        <span class="font-semibold">{{ item.question }}</span>
        <span>{{ open === idx ? '-' : '+' }}</span>
      </button>
      <div v-if="open === idx" class="mt-2 text-gray-700">{{ item.answer }}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FaqAccordion',
  data() {
    return {
      open: null,
      items: [
        { question: 'Jak se přihlásím?', answer: 'Kontaktujte nás, poradíme s dokumenty a postupem.' },
        { question: 'Potřebuji znalost angličtiny?', answer: 'Závisí na programu; některé kurzy vyžadují jazykový test.' },
        { question: 'Potřebuji vízum?', answer: 'Záleží na zemi původu a typu studia. Obecně studenti mimo EU potřebují vízum.' },
      ],
    };
  },
  methods: {
    toggle(i) {
      this.open = this.open === i ? null : i;
    },
  },
};
</script>

<style scoped>
summary { cursor: pointer; }
</style>

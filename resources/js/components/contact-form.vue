<template>
  <form @submit.prevent="submit" class="space-y-4 bg-white p-6 rounded shadow max-w-lg">
    <div>
      <label class="block text-sm font-medium">Jméno</label>
      <input v-model="form.name" :class="inputClass('name')" class="w-full border rounded px-3 py-2" />
      <p v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name[0] }}</p>
    </div>

    <div>
      <label class="block text-sm font-medium">E-mail</label>
      <input v-model="form.email" type="email" :class="inputClass('email')" class="w-full border rounded px-3 py-2" />
      <p v-if="errors.email" class="text-red-600 text-sm mt-1">{{ errors.email[0] }}</p>
    </div>

    <div>
      <label class="block text-sm font-medium">Zpráva</label>
      <textarea v-model="form.message" :class="inputClass('message')" class="w-full border rounded px-3 py-2"></textarea>
      <p v-if="errors.message" class="text-red-600 text-sm mt-1">{{ errors.message[0] }}</p>
    </div>

    <div class="flex items-center gap-4">
      <button :disabled="submitting" class="bg-[color:var(--color-primary)] text-white px-4 py-2 rounded disabled:opacity-60">{{ submitting ? 'Odesílám…' : 'Odeslat' }}</button>
      <p v-if="sent" class="text-green-600">Děkujeme, zpráva byla odeslána.</p>
    </div>
  </form>
</template>

<script>
export default {
  name: 'ContactForm',
  data() {
    return {
      form: { name: '', email: '', message: '' },
      errors: {},
      sending: false,
      sent: false,
      submitting: false,
    };
  },
  methods: {
    inputClass(field) {
      return this.errors[field] ? 'border-red-400' : '';
    },
    async submit() {
      this.errors = {};
      this.submitting = true;
      try {
        const res = await window.axios.post('/contact', this.form);
        if (res && res.data) {
          this.sent = true;
          this.form = { name: '', email: '', message: '' };
          setTimeout(() => (this.sent = false), 4000);
        }
      } catch (err) {
        if (err.response && err.response.status === 422) {
          this.errors = err.response.data.errors || {};
        } else {
          // Generic error handling: show message in console and set a simple error
          console.error('Contact submit error', err);
          this.errors = { form: ['Nastala chyba při odesílání. Zkuste to prosím později.'] };
        }
      } finally {
        this.submitting = false;
      }
    },
  },
};
</script>

<style scoped>
.border-red-400 { border-color: #f87171; }
</style>

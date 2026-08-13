<template>
  <form @submit.prevent="submit" :data-form-variant="variant" class="space-y-4">
    <template v-if="!isSubmitted">
      <input type="hidden" name="page" v-model="form.page" />
      <div>
        <label class="type-text-sm block text-brand-dark-green" for="lead-name">Jméno a příjmení *</label>
        <input id="lead-name" v-model="form.name" required type="text" :class="inputClass('name')" class="type-text-md mt-1 h-[50px] w-full rounded-[8px] border border-[#d6d6d6] bg-brand-light-gray px-4 text-brand-dark-green" />
        <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name[0] }}</p>
      </div>
      <div>
        <label class="type-text-sm block text-brand-dark-green" for="lead-email">E-mail *</label>
        <input id="lead-email" v-model="form.email" required type="email" :class="inputClass('email')" class="type-text-md mt-1 h-[50px] w-full rounded-[8px] border border-[#d6d6d6] bg-brand-light-gray px-4 text-brand-dark-green" />
        <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</p>
      </div>
      <template v-if="variant === 'inline'">
        <div>
          <label class="type-text-sm block text-brand-dark-green" for="lead-phone">Telefon</label>
          <input id="lead-phone" v-model="form.phone" type="tel" class="type-text-md mt-1 h-[50px] w-full rounded-[8px] border border-[#d6d6d6] bg-brand-light-gray px-4 text-brand-dark-green" />
        </div>
        <div>
          <label class="type-text-sm block text-brand-dark-green" for="lead-program">Vyberte program</label>
          <select id="lead-program" v-model="program" class="type-text-md mt-1 h-[50px] w-full rounded-[8px] border border-[#d6d6d6] bg-brand-light-gray px-4 text-brand-dark-green" @change="prefillMessage">
            <option value="">Vyberte typ programu...</option>
            <option>Studium v Irsku</option>
            <option>Jazykový kurz</option>
            <option>Příprava na vysokou školu</option>
          </select>
        </div>
      </template>
      <div>
        <label class="type-text-sm block text-brand-dark-green" for="lead-message">Zpráva *</label>
        <textarea id="lead-message" v-model="form.message" required rows="6" :class="inputClass('message')" class="type-text-md mt-1 h-[180px] w-full resize-none rounded-[8px] border border-[#d6d6d6] bg-brand-light-gray px-4 py-3 text-brand-dark-green"></textarea>
        <p v-if="errors.message" class="mt-1 text-sm text-red-600">{{ errors.message[0] }}</p>
      </div>
      <label class="flex items-start gap-2 text-sm text-brand-dark-green">
        <input v-model="form.consent" required type="checkbox" name="consent" class="mt-1 h-4 w-4">
        <span>Souhlasím se zpracováním osobních údajů za účelem vyřízení mého dotazu.</span>
      </label>
      <p v-if="errors.consent" class="mt-1 text-sm text-red-600">{{ errors.consent[0] }}</p>
      <p v-if="errors.form" class="text-sm text-red-600">{{ errors.form[0] }}</p>
      <button type="submit" :disabled="submitting" class="type-input-label inline-flex w-full items-center justify-center rounded-[8px] bg-brand-light-green px-8 py-4 text-brand-dark-green transition-color-figma hover:bg-[#7db709] disabled:opacity-60">{{ submitting ? 'Odesíláme...' : 'Domluvit konzultaci' }}</button>
    </template>
    <p v-else class="type-text-lg py-8 text-brand-dark-green">Děkujeme, ozveme se co nejdříve.</p>
  </form>
</template>

<script>
export default {
  name: 'ContactForm',
  props: {
    variant: { type: String, default: 'inline' },
    page: { type: String, default: '' }
  },
  emits: ['submitted'],
  data() {
    return {
      form: { name: '', email: '', message: '', phone: '', page: this.page || '', consent: false, source: 'website' },
      program: '',
      errors: {},
      submitting: false,
      isSubmitted: false,
    };
  },
  watch: { page(value) { this.form.page = value; } },
  mounted() {
    if (!this.form.page && typeof window !== 'undefined') this.form.page = this.page || window.location.href;
  },
  methods: {
    inputClass(field) { return this.errors[field] ? 'border-red-400' : ''; },
    prefillMessage() {
      if (this.program && !this.form.message) this.form.message = `Mám zájem o: ${this.program}.`;
    },
    async submit() {
      this.errors = {};
      this.submitting = true;
      try {
        const res = await window.axios.post('/contact', this.form);
        if (res?.data) {
          this.isSubmitted = true;
          this.form.name = this.form.email = this.form.message = this.form.phone = '';
          this.program = '';
          this.$emit('submitted');
        }
      } catch (err) {
        this.errors = err.response?.status === 422 ? (err.response.data.errors || {}) : { form: ['Nastala chyba při odesílání. Zkuste to prosím později.'] };
      } finally {
        this.submitting = false;
      }
    },
  },
};
</script>

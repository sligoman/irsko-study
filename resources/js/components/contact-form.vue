<template>
  <div>
    <!-- Floating toggle button for floating position -->
    <button
      v-if="position === 'floating' && !visible && !isSubmitted"
      @click="open"
      class="fixed z-50 right-6 bottom-6 bg-[color:var(--color-primary)] text-white p-3 rounded-full shadow-lg hover:scale-105 transition-transform"
      aria-label="Otevřít kontaktní formulář"
    >
      <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
      </svg>
    </button>

    <!-- Backdrop + panel for floating form -->
    <div v-if="position === 'floating'" aria-hidden="false">
      <div v-show="visible" class="fixed inset-0 bg-black/40 z-40" @click="close"></div>
      <div v-show="visible" class="fixed right-6 bottom-6 z-50 w-full max-w-md">
        <div class="bg-white p-4 rounded-lg shadow-lg relative">
          <!-- Close button for the floating panel -->
          <button @click="close" class="absolute -top-3 -right-3 bg-white text-gray-700 rounded-full p-2 shadow hover:bg-gray-100 transition" aria-label="Zavřít formulář">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
          </button>
          <template v-if="!isSubmitted && !submitting">
            <form @submit.prevent="submit" :data-form-position="position">
              <input type="hidden" name="page" v-model="form.page" />
              <input type="hidden" name="position" v-model="form.position" />

              <div class="mb-2">
                <label class="block text-sm font-medium">Jméno<span class="text-red-900">*</span></label>
                <input v-model="form.name" required type="text" id="name" name="name" :class="inputClass('name')" class="w-full border rounded px-3 py-2" />
                <p v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name[0] }}</p>
              </div>

              <div class="mb-2">
                <label class="block text-sm font-medium">E-mail<span class="text-red-900">*</span></label>
                <input v-model="form.email" required type="email" id="email" name="email" :class="inputClass('email')" class="w-full border rounded px-3 py-2" />
                <p v-if="errors.email" class="text-red-600 text-sm mt-1">{{ errors.email[0] }}</p>
              </div>

              <div class="mb-2">
                <label class="block text-sm font-medium">Telefon</label>
                <input v-model="form.phone" type="text" id="phone" name="phone" class="w-full border rounded px-3 py-2" />
              </div>

              <div class="mb-2">
                <label class="block text-sm font-medium">Zpráva</label>
                <textarea v-model="form.message" id="message" name="message" class="w-full border rounded px-3 py-2"></textarea>
                <p v-if="errors.message" class="text-red-600 text-sm mt-1">{{ errors.message[0] }}</p>
              </div>

              <div v-if="selectedCourses.length > 0" class="mb-2">
                <label class="block text-sm font-medium">Poptávané kurzy</label>
                <div class="flex flex-wrap mt-2">
                  <span v-for="c in selectedCourses" :key="c.id" class="flex items-center bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">
                    <span>{{ c.name }}</span><span v-if="c.price">&nbsp;za&nbsp;{{ c.price }}</span>
                  </span>
                </div>
              </div>

              <div class="mt-3">
                <button type="submit" :disabled="submitting" class="inline-flex items-center justify-center w-full h-12 px-6 font-medium text-white bg-[color:var(--color-primary)] rounded">
                  {{ submitting ? 'Odesílám…' : 'Odeslat' }}
                </button>
              </div>
              <!-- Optional quick contact line -->
              <div class="mt-3 text-sm text-gray-600">
                <div v-if="contacts.email">Napište nám: <a :href="`mailto:${contacts.email}`" class="text-[color:var(--color-emerald)]">{{ contacts.email }}</a></div>
                <div v-if="contacts.mobile" class="mt-1">Mobil: <a :href="`tel:${contacts.mobile}`" class="text-[color:var(--color-emerald)]">{{ contacts.mobile }}</a></div>
                <div v-if="contacts.whatsapp" class="mt-1">WhatsApp: <a :href="contacts.whatsapp.startsWith('http') ? contacts.whatsapp : ('https://wa.me/' + contacts.whatsapp)" target="_blank" rel="noopener" class="text-[color:var(--color-emerald)]">Odeslat zprávu</a></div>
                <div v-if="contacts.whatsapp_qr" class="mt-2">
                  <a :href="contacts.whatsapp_qr.startsWith('http') ? contacts.whatsapp_qr : ('/' + contacts.whatsapp_qr)" target="_blank" rel="noopener">
                    <img :src="contacts.whatsapp_qr.startsWith('http') ? contacts.whatsapp_qr : ('/' + contacts.whatsapp_qr)" alt="WhatsApp QR" class="w-20 h-20 object-contain rounded-md border" />
                  </a>
                </div>
              </div>
            </form>
          </template>

          <div v-show="submitting" class="flex items-center justify-center w-full h-12">
            <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0012 20c4.411 0 8-3.589 8-8h-4a4 4 0 11-8 0H6v3.291z"></path>
            </svg>
          </div>

          <div v-show="isSubmitted" class="font-bold text-[color:var(--color-primary)] text-lg space-y-2">
            <div>Děkujeme za odeslání formuláře.</div>
            <div>Brzy se ti ozveme.</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Inline/bottom form variant -->
    <div v-if="position !== 'floating'" id="lead-form-inline">
      <form @submit.prevent="submit" :data-form-position="position" class="space-y-4 bg-white p-6 rounded shadow max-w-lg">
        <input type="hidden" name="page" v-model="form.page" />
        <input type="hidden" name="position" v-model="form.position" />

        <div>
          <label class="block text-sm font-medium">Jméno</label>
          <input v-model="form.name" required type="text" :class="inputClass('name')" class="w-full border rounded px-3 py-2" />
          <p v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name[0] }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium">E-mail</label>
          <input v-model="form.email" required type="email" :class="inputClass('email')" class="w-full border rounded px-3 py-2" />
          <p v-if="errors.email" class="text-red-600 text-sm mt-1">{{ errors.email[0] }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium">Telefon</label>
          <input v-model="form.phone" type="text" class="w-full border rounded px-3 py-2" />
        </div>

        <div>
          <label class="block text-sm font-medium">Zpráva</label>
          <textarea v-model="form.message" class="w-full border rounded px-3 py-2"></textarea>
          <p v-if="errors.message" class="text-red-600 text-sm mt-1">{{ errors.message[0] }}</p>
        </div>

        <div class="flex items-center gap-4">
          <button :disabled="submitting" class="bg-[color:var(--color-primary)] text-white px-4 py-2 rounded disabled:opacity-60">{{ submitting ? 'Odesílám…' : 'Odeslat' }}</button>
          <p v-if="isSubmitted" class="text-green-600">Děkujeme, zpráva byla odeslána.</p>
        </div>
        <div class="mt-2 text-sm text-gray-600">
          <div v-if="contacts.email">Napište nám: <a :href="`mailto:${contacts.email}`" class="text-[color:var(--color-emerald)]">{{ contacts.email }}</a></div>
          <div v-if="contacts.mobile" class="mt-1">Mobil: <a :href="`tel:${contacts.mobile}`" class="text-[color:var(--color-emerald)]">{{ contacts.mobile }}</a></div>
          <div v-if="contacts.whatsapp" class="mt-1">WhatsApp: <a :href="contacts.whatsapp.startsWith('http') ? contacts.whatsapp : ('https://wa.me/' + contacts.whatsapp)" target="_blank" rel="noopener" class="text-[color:var(--color-emerald)]">Odeslat zprávu</a></div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ContactForm',
  props: {
    position: { type: String, default: 'bottom' },
    page: { type: String, default: '' }
  },
  data() {
    return {
      form: { name: '', email: '', message: '', phone: '', page: this.page || '', position: this.position },
      errors: {},
      submitting: false,
      isSubmitted: false,
      visible: this.position === 'floating' ? false : true,
      selectedCourses: [],
      contacts: {},
    };
  },
  watch: {
    page(v) { this.form.page = v; },
    position(v) { this.form.position = v; }
  },
  mounted() {
    // Initialize hidden fields if Blade provided a page prop via attribute
    if (!this.form.page && typeof window !== 'undefined') {
      this.form.page = this.page || window.location.href;
    }
    this.form.position = this.position;
    // pick up contacts config exposed by Blade layout (window.__CONTACTS)
    if (typeof window !== 'undefined' && window.__CONTACTS) {
      this.contacts = window.__CONTACTS;
    }
  },
  methods: {
    inputClass(field) {
      return this.errors[field] ? 'border-red-400' : '';
    },
    open() { this.visible = true; },
    close() { this.visible = false; },
    async submit() {
      this.errors = {};
      this.submitting = true;
      try {
        const res = await window.axios.post('/contact', this.form);
        if (res && res.data) {
          this.isSubmitted = true;
          // clear form fields except page/position
          this.form.name = '';
          this.form.email = '';
          this.form.message = '';
          this.form.phone = '';
          setTimeout(() => {
            this.isSubmitted = false;
            if (this.position === 'floating') this.close();
          }, 4000);
        }
      } catch (err) {
        if (err.response && err.response.status === 422) {
          this.errors = err.response.data.errors || {};
        } else {
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

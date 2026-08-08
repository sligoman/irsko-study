<template>
  <div>
    <!-- Floating toggle button (circular) -->
    <div v-if="position === 'floating'" class="z-50 fixed bottom-4 right-4">
      <button
        v-if="!visible && !isSubmitted"
        @click="open"
        class="bg-[color:var(--color-brand-dark-green)] text-white rounded-full w-16 h-16 flex items-center justify-center shadow-lg hover:brightness-110 focus:outline-none"
        aria-label="Otevřít kontaktní formulář"
      >
        <!-- use a slightly larger SVG like your snippet -->
        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path d="M16.5 14.5C16.5 14.5 17.5 15.5 17.5 16.5C17.5 17.6 16.6 18.5 15.5 18.5C14.4 18.5 13.5 17.6 13.5 16.5C13.5 15.4 14.4 14.5 15.5 14.5C15.8 14.5 16.2 14.5 16.5 14.5ZM12.5 2C7.8 2 4 5.8 4 10.5C4 15.2 7.8 19 12.5 19C17.2 19 21 15.2 21 10.5C21 5.8 17.2 2 12.5 2ZM12.5 17C9.5 17 7 14.5 7 11.5C7 8.5 9.5 6 12.5 6C15.5 6 18 8.5 18 11.5C18 14.5 15.5 17 12.5 17Z" />
        </svg>
      </button>

      <!-- selected courses badge -->
      <div v-if="selectedCourses && selectedCourses.length > 0" class="absolute -top-3 -right-3">
        <span class="bg-[color:var(--color-brand-dark-green)] text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg text-sm">{{ selectedCourses.length }}</span>
      </div>
    </div>

    <!-- Modal (centered on desktop, full-screen on mobile) -->
    <div v-show="position === 'floating' && visible" class="fixed inset-0 z-50 flex items-center justify-center">
      <!-- Backdrop -->
      <div class="fixed inset-0 bg-gray-900 opacity-75" @click="close" aria-hidden="true"></div>

      <!-- Panel -->
      <div class="bg-white rounded-none sm:rounded-lg shadow-lg p-6 w-full sm:w-auto sm:max-w-md z-10 h-full sm:h-auto max-h-[90vh] overflow-auto" @click.stop>
        <!-- Close button -->
        <button @click="close" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 focus:outline-none" aria-label="Zavřít formulář">
          <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>

        <div>
          <!-- reuse existing form markup inside -->
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

              <div v-if="selectedCourses && selectedCourses.length > 0" class="mb-2">
                <label class="block text-sm font-medium">Poptávané kurzy</label>
                <div class="flex flex-wrap mt-2">
                  <span v-for="c in selectedCourses" :key="c.id" class="flex items-center bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">
                    <span>{{ c.name }}</span><span v-if="c.price">&nbsp;za&nbsp;{{ c.price }}</span>
                  </span>
                </div>
              </div>

              <div class="mt-3">
                <button type="submit" :disabled="submitting" class="inline-flex items-center justify-center w-full h-12 px-6 font-medium text-white bg-[color:var(--color-brand-dark-green)] rounded">
                  {{ submitting ? 'Odesílám…' : 'Odeslat' }}
                </button>
              </div>
              <!-- Optional quick contact line -->
              <div class="mt-3 text-sm text-gray-600">
                <div v-if="contacts.email">Napište nám: <a :href="`mailto:${contacts.email}`" class="text-[color:var(--color-brand-light-green)]">{{ contacts.email }}</a></div>
                <div v-if="contacts.mobile" class="mt-1">Mobil: <a :href="`tel:${contacts.mobile}`" class="text-[color:var(--color-brand-light-green)]">{{ contacts.mobile }}</a></div>
                <div v-if="contacts.whatsapp" class="mt-1">WhatsApp: <a :href="contacts.whatsapp.startsWith('http') ? contacts.whatsapp : ('https://wa.me/message/' + contacts.whatsapp)" target="_blank" rel="noopener" class="text-[color:var(--color-brand-light-green)]">Odeslat zprávu</a></div>
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

          <div v-show="isSubmitted" class="font-bold text-[color:var(--color-brand-dark-green)] text-lg space-y-2">
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
          <button :disabled="submitting" class="bg-[color:var(--color-brand-dark-green)] text-white px-4 py-2 rounded disabled:opacity-60">{{ submitting ? 'Odesílám…' : 'Odeslat' }}</button>
          <p v-if="isSubmitted" class="text-green-600">Děkujeme, zpráva byla odeslána.</p>
        </div>
        <div class="mt-2 text-sm text-gray-600">
          <div v-if="contacts.email">Napište nám: <a :href="`mailto:${contacts.email}`" class="text-[color:var(--color-brand-light-green)]">{{ contacts.email }}</a></div>
          <div v-if="contacts.mobile" class="mt-1">Mobil: <a :href="`tel:${contacts.mobile}`" class="text-[color:var(--color-brand-light-green)]">{{ contacts.mobile }}</a></div>
          <div v-if="contacts.whatsapp" class="mt-1">WhatsApp: <a :href="contacts.whatsapp.startsWith('http') ? contacts.whatsapp : ('https://wa.me/message/' + contacts.whatsapp)" target="_blank" rel="noopener" class="text-[color:var(--color-brand-light-green)]">Odeslat zprávu</a></div>
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
    open() {
      this.visible = true;
      // Fire analytics event if available
      try { if (typeof gtag === 'function') gtag('event','form_open'); } catch (e) { /* ignore */ }
    },
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

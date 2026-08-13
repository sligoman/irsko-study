<div v-cloak>
  <div class="fixed bottom-[max(0.75rem,env(safe-area-inset-bottom))] right-[max(0.75rem,env(safe-area-inset-right))] z-50">
    <button class="relative flex h-16 w-16 items-center justify-center rounded-full bg-brand-dark-green text-white shadow-[0_4px_24px_rgba(0,0,0,0.15),0_4px_4px_rgba(0,0,0,0.05)] transition-color-figma hover:bg-[#0a4a46] focus:outline-hidden focus:shadow-outline sm:h-[88px] sm:w-[92px]" @click="openContactForm" aria-label="Otevřít kontaktní formulář">
      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" viewBox="0 0 40 40" aria-hidden="true"><path fill="#9BCC57" d="M35 7.813H5a.937.937 0 0 0-.938.937V30a2.187 2.187 0 0 0 2.188 2.188h27.5A2.188 2.188 0 0 0 35.938 30V8.75A.938.938 0 0 0 35 7.812ZM20 21.227 7.41 9.688h25.18L20 21.227ZM15.886 20l-9.948 9.119V10.88L15.886 20Zm1.387 1.272 2.102 1.919a.938.938 0 0 0 1.266 0l2.093-1.92 9.857 9.041H7.41l9.862-9.04ZM24.114 20l9.948-9.119V29.12L24.114 20Z"/></svg>
    </button>
  </div>
  <transition name="redesign-fade">
    <div v-show="showContactForm" class="fixed inset-0 z-50 flex items-end justify-center p-3 sm:items-center sm:p-6" style="display: none;" @keydown.esc.window="closeContactForm">
      <div class="absolute inset-0 bg-black/55" @click="closeContactForm"></div>
      <div role="dialog" aria-modal="true" aria-labelledby="contact-form-title" class="redesign-footer-pattern relative z-10 max-h-[calc(100dvh-1.5rem)] w-full max-w-[768px] overflow-y-auto rounded-[16px] p-6 shadow-2xl sm:max-h-[calc(100dvh-3rem)] sm:p-10 lg:p-16">
        <button class="absolute right-4 top-4 text-white/90 hover:text-white focus:outline-hidden" @click="closeContactForm" aria-label="Zavřít formulář"><svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
        <div class="mx-auto max-w-[472px]">
          <h2 id="contact-form-title" class="type-display-lg text-white">Kontaktujte nás</h2>
          <p class="type-text-md mt-3 text-white">Online jsme každý den od 10:00 do 14:00.</p>
          <div class="mt-8 rounded-[16px] bg-base-white p-6 sm:p-8"><contact-form variant="modal" v-on:submitted="closeContactFormAfterSubmission"></contact-form></div>
        </div>
      </div>
    </div>
  </transition>
</div>

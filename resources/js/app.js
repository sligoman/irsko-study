import './bootstrap';
import { createApp } from 'vue';

// Register Vue components used across Blade pages.
import FaqAccordion from './components/faq-accordion.vue';
import ContactForm from './components/contact-form.vue';
import BlogDynamic from './components/blog-dynamic.vue';

const app = createApp({});
app.component('faq-accordion', FaqAccordion);
app.component('contact-form', ContactForm);
app.component('blog-dynamic', BlogDynamic);
app.mount('#app');

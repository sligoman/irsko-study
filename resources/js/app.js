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

// Mobile nav toggle: attach in bundled JS to avoid inline <script> inside Blade templates
document.addEventListener('DOMContentLoaded', function(){
	const btn = document.getElementById('nav-toggle');
	const menu = document.getElementById('mobile-menu');
	if (btn && menu) btn.addEventListener('click', () => menu.classList.toggle('hidden'));
});

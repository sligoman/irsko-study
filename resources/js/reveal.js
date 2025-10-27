// Small reveal-on-scroll utility using IntersectionObserver
export default function initReveal(selector = '.reveal', options = {}) {
  const config = Object.assign({
    root: null,
    rootMargin: '0px 0px -10% 0px',
    threshold: 0.08,
  }, options);

  if (typeof window === 'undefined' || !('IntersectionObserver' in window)) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        // allow per-element delay via data-reveal-delay (ms)
        const delay = entry.target.getAttribute('data-reveal-delay');
        if (delay) {
          entry.target.style.transitionDelay = `${parseInt(delay, 10)}ms`;
        }
        entry.target.classList.add('revealed');
        // optional: unobserve so animation runs once
        observer.unobserve(entry.target);
      }
    });
  }, config);

  document.querySelectorAll(selector).forEach(el => observer.observe(el));
}

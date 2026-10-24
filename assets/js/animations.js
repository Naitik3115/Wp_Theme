/* Atelier B2B – GSAP / ScrollTrigger reveals. Page stays fully usable if GSAP is missing. */
(function () {
  'use strict';

  if (!window.gsap || !window.ScrollTrigger) { return; }
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) { return; }

  var gsap = window.gsap;
  var ScrollTrigger = window.ScrollTrigger;
  gsap.registerPlugin(ScrollTrigger);

  /* Line-by-line heading reveals */
  gsap.utils.toArray('[data-lines]').forEach(function (el) {
    var lines = el.querySelectorAll('.line__inner');
    gsap.from(lines, {
      yPercent: 112, duration: 1.3, ease: 'power4.out', stagger: 0.12,
      scrollTrigger: { trigger: el, start: 'top 88%', once: true }
    });
  });

  /* Supporting text / CTA fade up */
  gsap.utils.toArray('[data-fade]').forEach(function (el) {
    gsap.from(el, {
      y: 28, autoAlpha: 0, duration: 1.1, ease: 'power3.out',
      delay: parseFloat(el.getAttribute('data-delay')) || 0,
      scrollTrigger: { trigger: el, start: 'top 92%', once: true }
    });
  });

  /* Image clip-path reveals */
  gsap.utils.toArray('[data-clip]').forEach(function (el) {
    var img = el.querySelector('img');
    gsap.fromTo(el, { clipPath: 'inset(0% 0% 100% 0%)' }, {
      clipPath: 'inset(0% 0% 0% 0%)', duration: 1.5, ease: 'power4.inOut',
      scrollTrigger: { trigger: el, start: 'top 85%', once: true },
      onComplete: function () { gsap.set(el, { clearProps: 'clipPath' }); }
    });
    if (img && !el.querySelector('[data-parallax]')) {
      gsap.from(img, {
        scale: 1.2, duration: 1.8, ease: 'power3.out',
        scrollTrigger: { trigger: el, start: 'top 85%', once: true },
        onComplete: function () { gsap.set(img, { clearProps: 'transform' }); }
      });
    }
  });

  /* Parallax (desktop / tablet only) */
  var mm = gsap.matchMedia();
  mm.add('(min-width: 768px)', function () {
    gsap.utils.toArray('[data-parallax]').forEach(function (el) {
      gsap.fromTo(el, { yPercent: -5 }, {
        yPercent: 5, ease: 'none',
        scrollTrigger: { trigger: el.parentElement, start: 'top bottom', end: 'bottom top', scrub: true }
      });
    });
    var hero = document.querySelector('[data-hero-media]');
    if (hero) {
      gsap.to(hero, {
        yPercent: 6, ease: 'none',
        scrollTrigger: { trigger: '#hero', start: 'top top', end: 'bottom top', scrub: true }
      });
    }
  });

  window.addEventListener('load', function () { ScrollTrigger.refresh(); });
})();

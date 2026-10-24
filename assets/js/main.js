/* Atelier B2B – core behaviours: mobile menu, back-to-top, hero video. */
(function () {
  'use strict';

  var root = document.documentElement;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---- Mobile menu ---- */
  var toggle = document.querySelector('[data-menu-toggle]');
  var menu = document.querySelector('[data-mobile-menu]');
  var closeBtn = document.querySelector('[data-menu-close]');
  var lastFocus = null;

  function focusables() {
    return menu.querySelectorAll('a[href], button:not([disabled])');
  }
  function openMenu() {
    lastFocus = document.activeElement;
    menu.removeAttribute('inert');
    menu.setAttribute('aria-hidden', 'false');
    menu.classList.add('is-open');
    toggle.setAttribute('aria-expanded', 'true');
    root.classList.add('menu-open');
    window.setTimeout(function () { closeBtn.focus(); }, 60);
  }
  function closeMenu(restore) {
    menu.classList.remove('is-open');
    menu.setAttribute('aria-hidden', 'true');
    menu.setAttribute('inert', '');
    toggle.setAttribute('aria-expanded', 'false');
    root.classList.remove('menu-open');
    if (restore !== false && lastFocus) { lastFocus.focus(); }
  }

  if (toggle && menu && closeBtn) {
    toggle.addEventListener('click', function () {
      toggle.getAttribute('aria-expanded') === 'true' ? closeMenu() : openMenu();
    });
    closeBtn.addEventListener('click', function () { closeMenu(); });
    menu.addEventListener('click', function (e) {
      var a = e.target.closest('a');
      if (a) { closeMenu(false); }
    });
    document.addEventListener('keydown', function (e) {
      if (!menu.classList.contains('is-open')) { return; }
      if (e.key === 'Escape') { closeMenu(); return; }
      if (e.key === 'Tab') {
        var f = focusables();
        if (!f.length) { return; }
        var first = f[0], last = f[f.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
      }
    });
    window.matchMedia('(min-width: 1180px)').addEventListener('change', function (m) {
      if (m.matches && menu.classList.contains('is-open')) { closeMenu(false); }
    });
  }

  /* ---- Back to top ---- */
  document.querySelectorAll('[data-back-to-top]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
    });
  });

  /* ---- Hero video: respect reduced motion, fall back to poster if playback fails ---- */
  var video = document.querySelector('[data-hero-video]');
  if (video) {
    if (reduce) { video.removeAttribute('autoplay'); video.pause(); }
    else {
      var p = video.play();
      if (p && p.catch) { p.catch(function () { /* poster stays visible */ }); }
    }
  }
})();

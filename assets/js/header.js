/* Atelier B2B – header: transparent at top, hides on scroll down, reveals on scroll up.
   Works with the static header and with the Elementor-rendered header (same wrapper: [data-header]).
   Disabled inside the Elementor editor / header preview. */
(function () {
  'use strict';

  var header = document.querySelector('[data-header]');
  if (!header) { return; }

  var body = document.body;
  var root = document.documentElement;

  // Never animate / hide the header while editing in Elementor.
  if (body.classList.contains('elementor-editor-active') || body.classList.contains('atelier-header-preview')) {
    header.classList.remove('is-hidden');
    return;
  }

  var overlay = header.getAttribute('data-overlay') === 'true';
  var isElementor = header.classList.contains('atelier-elementor-header');
  var lastY = window.pageYOffset;
  var acc = 0;
  var ticking = false;

  var TOP = 12;          // px from top treated as "at top"
  var HIDE_AFTER = 160;  // don't hide before this scroll depth
  var DOWN_THRESHOLD = 14;
  var UP_THRESHOLD = 8;

  function show() { header.classList.remove('is-hidden'); }
  function hide() { header.classList.add('is-hidden'); }

  /* Elementor header: expose the real bar / nav heights so hero + inner pages offset correctly
     even after the bar is edited or hidden in Elementor. */
  function measure() {
    if (!isElementor) { return; }
    var bar = header.querySelector('.atelier-announcement');
    var nav = header.querySelector('.atelier-navigation');
    var bh = bar ? bar.offsetHeight : 0;
    var nh = nav ? nav.offsetHeight : 0;
    if (nh) {
      root.style.setProperty('--bar-h', bh + 'px');
      root.style.setProperty('--nav-h', nh + 'px');
    }
  }

  function update() {
    ticking = false;
    var y = Math.max(0, window.pageYOffset);
    var delta = y - lastY;
    lastY = y;

    if (root.classList.contains('menu-open')) { return; }

    if (overlay) {
      if (y <= TOP) {
        header.classList.add('is-top');
        header.classList.remove('is-scrolled');
      } else {
        header.classList.remove('is-top');
        header.classList.add('is-scrolled');
      }
    }

    if (y <= TOP) { acc = 0; show(); return; }

    // accumulate movement in the current direction to ignore tiny jitters
    if ((delta > 0 && acc < 0) || (delta < 0 && acc > 0)) { acc = 0; }
    acc += delta;

    if (acc > DOWN_THRESHOLD && y > HIDE_AFTER) { hide(); acc = 0; }
    else if (acc < -UP_THRESHOLD) { show(); acc = 0; }
  }

  window.addEventListener('scroll', function () {
    if (!ticking) { ticking = true; window.requestAnimationFrame(update); }
  }, { passive: true });

  if (isElementor) {
    measure();
    window.addEventListener('resize', measure);
    window.addEventListener('load', measure);
    if (window.ResizeObserver) { new ResizeObserver(measure).observe(header); }
  }

  update();
})();

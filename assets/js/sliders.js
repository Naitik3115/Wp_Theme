/* Atelier B2B – categories slider: mouse drag with momentum, native touch swipe, buttons, progress, drag cursor. */
(function () {
  'use strict';

  var fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function initializeSlider(root) {
    if (root.hasAttribute('data-slider-initialized')) { return; }
    root.setAttribute('data-slider-initialized', '');

    var vp = root.querySelector('[data-slider-viewport]');
    if (!vp) { return; }
    var bar = root.querySelector('[data-slider-progress]');
    var cursor = root.querySelector('[data-drag-cursor]');
    var section = root.closest('.categories') || root.parentElement;
    var controls = section.querySelector('[data-slider-controls]');
    var prev = controls && controls.querySelector('[data-slider-prev]');
    var next = controls && controls.querySelector('[data-slider-next]');
    var duration = Math.max(0, parseInt(root.getAttribute('data-slider-speed'), 10) || 0);
    var down = false, startX = 0, startScroll = 0, moved = 0;
    var vel = 0, lastX = 0, lastT = 0, raf = 0, scrollRaf = 0, suppressClick = false;

    function max() { return Math.max(0, vp.scrollWidth - vp.clientWidth); }

    function sync() {
      var m = max();
      var ratio = m > 0 ? vp.scrollLeft / m : 0;
      var vis = vp.scrollWidth > 0 ? Math.min(1, vp.clientWidth / vp.scrollWidth) : 1;
      if (bar) {
        bar.style.width = (vis * 100) + '%';
        bar.style.transform = 'translateX(' + (ratio * (1 / vis - 1) * 100) + '%)';
      }
      if (prev) { prev.disabled = vp.scrollLeft <= 2; }
      if (next) { next.disabled = vp.scrollLeft >= m - 2; }
    }

    function step(dir) {
      var slide = vp.querySelector('.slider__slide');
      if (!slide) { return; }
      var gap = parseFloat(getComputedStyle(vp.firstElementChild).columnGap) || 24;
      var target = Math.max(0, Math.min(max(), vp.scrollLeft + dir * (slide.getBoundingClientRect().width + gap)));
      window.cancelAnimationFrame(scrollRaf);
      if (!duration || reduce) {
        vp.scrollTo({ left: target, behavior: reduce ? 'auto' : 'smooth' });
        return;
      }
      var from = vp.scrollLeft;
      var start = performance.now();
      function animate(now) {
        var progress = Math.min(1, (now - start) / duration);
        var eased = 1 - Math.pow(1 - progress, 3);
        vp.scrollLeft = from + (target - from) * eased;
        if (progress < 1) { scrollRaf = window.requestAnimationFrame(animate); }
      }
      scrollRaf = window.requestAnimationFrame(animate);
    }

    if (prev) { prev.addEventListener('click', function () { step(-1); }); }
    if (next) { next.addEventListener('click', function () { step(1); }); }
    vp.addEventListener('scroll', function () { window.requestAnimationFrame(sync); }, { passive: true });
    window.addEventListener('resize', sync);

    function momentum() {
      vel *= 0.94;
      if (Math.abs(vel) < 0.3) { return; }
      vp.scrollLeft -= vel;
      raf = window.requestAnimationFrame(momentum);
    }

    vp.addEventListener('pointerdown', function (e) {
      if (e.pointerType !== 'mouse' || e.button !== 0) { return; }
      window.cancelAnimationFrame(scrollRaf);
      down = true; moved = 0; vel = 0;
      startX = lastX = e.clientX; startScroll = vp.scrollLeft; lastT = performance.now();
      window.cancelAnimationFrame(raf);
      vp.classList.add('is-dragging');
    });

    window.addEventListener('pointermove', function (e) {
      if (cursor && fine) { placeCursor(e); }
      if (!down) { return; }
      var dx = e.clientX - startX;
      moved = Math.max(moved, Math.abs(dx));
      vp.scrollLeft = startScroll - dx;
      var now = performance.now();
      var dt = Math.max(1, now - lastT);
      vel = 0.8 * vel + 0.2 * ((e.clientX - lastX) / dt * 16);
      lastX = e.clientX; lastT = now;
    });

    window.addEventListener('pointerup', function () {
      if (!down) { return; }
      down = false;
      vp.classList.remove('is-dragging');
      if (moved > 6) { suppressClick = true; }
      if (!reduce) { raf = window.requestAnimationFrame(momentum); }
    });

    vp.addEventListener('click', function (e) {
      if (suppressClick) { e.preventDefault(); e.stopPropagation(); suppressClick = false; }
    }, true);
    vp.addEventListener('dragstart', function (e) { e.preventDefault(); });

    var cx = 0, cy = 0, tx = 0, ty = 0, cRaf = 0, inside = false;
    function placeCursor(e) { tx = e.clientX; ty = e.clientY; if (!cRaf) { cRaf = window.requestAnimationFrame(tick); } }
    function tick() {
      cx += (tx - cx) * 0.2; cy += (ty - cy) * 0.2;
      var scale = down ? 0.82 : (inside ? 1 : 0.4);
      cursor.style.transform = 'translate3d(' + cx + 'px,' + cy + 'px,0) scale(' + scale + ')';
      cRaf = (Math.abs(tx - cx) > 0.1 || Math.abs(ty - cy) > 0.1 || down) ? window.requestAnimationFrame(tick) : 0;
    }
    if (cursor && fine) {
      vp.addEventListener('pointerenter', function (e) { inside = true; cx = tx = e.clientX; cy = ty = e.clientY; cursor.classList.add('is-visible'); placeCursor(e); });
      vp.addEventListener('pointerleave', function () { inside = false; cursor.classList.remove('is-visible'); placeCursor({ clientX: tx, clientY: ty }); });
    }
    sync();
  }

  document.querySelectorAll('[data-slider]').forEach(initializeSlider);

  function registerElementorHook() {
    if (!window.elementorFrontend || !window.elementorFrontend.hooks) { return; }
    window.elementorFrontend.hooks.addAction('frontend/element_ready/atelier-categories.default', function ($scope) {
      var scope = $scope && $scope[0];
      if (scope) { scope.querySelectorAll('[data-slider]').forEach(initializeSlider); }
    });
  }

  if (window.elementorFrontend && window.elementorFrontend.hooks) {
    registerElementorHook();
  } else if (window.jQuery) {
    window.jQuery(window).on('elementor/frontend/init', registerElementorHook);
  }
})();

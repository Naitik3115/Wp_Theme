/* Atelier B2B – Product catalog category filter. */
(function () {
  'use strict';

  function ready(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  function paramCategory() {
    try {
      return new URLSearchParams(window.location.search).get('category') || '';
    } catch (e) {
      return '';
    }
  }

  function setUrlCategory(slug) {
    try {
      var url = new URL(window.location.href);
      if (slug) url.searchParams.set('category', slug);
      else url.searchParams.delete('category');
      window.history.replaceState({}, '', url.pathname + url.search + url.hash);
    } catch (e) { /* ignore */ }
  }

  function initRoot(root) {
    if (!root || root.getAttribute('data-filter-ready') === '1') return;
    root.setAttribute('data-filter-ready', '1');

    var buttons = root.querySelectorAll('[data-filter]');
    var cards = root.querySelectorAll('[data-category]');
    var countEl = root.querySelector('[data-collections-count]');
    var emptyEl = root.querySelector('[data-collections-empty]');
    var countLabel = root.getAttribute('data-count-label') || '%d products';

    function apply(slug) {
      var visible = 0;
      cards.forEach(function (card) {
        var match = !slug || card.getAttribute('data-category') === slug;
        card.classList.toggle('is-hidden', !match);
        if (match) {
          card.removeAttribute('hidden');
          visible += 1;
        } else {
          card.setAttribute('hidden', '');
        }
      });

      buttons.forEach(function (btn) {
        var active = (btn.getAttribute('data-filter') || '') === slug;
        btn.classList.toggle('is-active', active);
        btn.setAttribute('aria-pressed', active ? 'true' : 'false');
      });

      if (countEl) {
        countEl.textContent = countLabel.replace('%d', String(visible));
      }
      if (emptyEl) {
        var showEmpty = visible === 0;
        emptyEl.classList.toggle('is-hidden', !showEmpty);
        if (showEmpty) emptyEl.removeAttribute('hidden');
        else emptyEl.setAttribute('hidden', '');
      }

      root.setAttribute('data-active', slug);
      setUrlCategory(slug);
    }

    buttons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        apply(btn.getAttribute('data-filter') || '');
      });
    });

    var initial = root.getAttribute('data-active') || paramCategory() || '';
    apply(initial);
  }

  function boot(scope) {
    var roots = (scope || document).querySelectorAll
      ? (scope || document).querySelectorAll('[data-collections-filter]')
      : [];
    roots.forEach(initRoot);
  }

  function onElementorReady($scope) {
    var el = $scope && $scope[0] ? $scope[0] : null;
    if (!el) return;
    el.querySelectorAll('[data-collections-filter]').forEach(function (root) {
      root.removeAttribute('data-filter-ready');
      initRoot(root);
    });
  }

  ready(function () {
    boot(document);
  });

  function registerElementorHook() {
    if (!window.elementorFrontend || !window.elementorFrontend.hooks) return;
    window.elementorFrontend.hooks.addAction(
      'frontend/element_ready/atelier-product-catalog.default',
      onElementorReady
    );
  }

  if (window.elementorFrontend && window.elementorFrontend.hooks) {
    registerElementorHook();
  } else if (window.jQuery) {
    window.jQuery(window).on('elementor/frontend/init', registerElementorHook);
  }
})();

/* Atelier B2B – Collections: category filtering (no reload), Load More, hover-image preloading, Quick View drawer.
   One init per grid root ([data-ready] guard) so Elementor editor re-renders never duplicate listeners. */
(function () {
  'use strict';

  var cfg = window.atelierCollections || { rest: '/wp-json/atelier/v1/', inquiry: '/contact/', i18n: {} };
  var t = cfg.i18n || {};
  var htmlEl = document.documentElement;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var canHover = window.matchMedia('(hover: hover)').matches;
  var saveData = navigator.connection && navigator.connection.saveData;

  function fmt(s, a, b) { return String(s || '').replace('%1$s', a).replace('%2$s', b); }
  function safeUrl(u) { return /^https?:\/\//i.test(u) || u.charAt(0) === '/' ? u : ''; }

  /* =========================== Quick View =========================== */
  var qv = (function () {
    var el = document.getElementById('atelier-qv');
    if (!el) { return null; }

    var panel = el.querySelector('.qv__panel');
    var stateEl = el.querySelector('[data-qv-state]');
    var content = el.querySelector('[data-qv-content]');
    var layers = [el.querySelector('[data-qv-img="0"]'), el.querySelector('[data-qv-img="1"]')];
    var thumbsEl = el.querySelector('[data-qv-thumbs]');
    var counter = el.querySelector('[data-qv-counter]');
    var titleEl = el.querySelector('[data-qv-title]');
    var catEl = el.querySelector('[data-qv-cat]');
    var facts = el.querySelector('[data-qv-facts]');
    var desc = el.querySelector('[data-qv-desc]');
    var cta = el.querySelector('[data-qv-cta]');
    var prevBtn = el.querySelector('[data-qv-prev]');
    var nextBtn = el.querySelector('[data-qv-next]');
    var closeBtn = el.querySelector('.qv__close');

    var cache = {}, data = null, idx = 0, layer = 0, lastFocus = null, base = cfg.inquiry, token = 0, timer = 0;

    function setState(msg, retry) {
      stateEl.textContent = '';
      if (msg) {
        var p = document.createElement('p');
        p.textContent = msg;
        stateEl.appendChild(p);
        if (retry) {
          var b = document.createElement('button');
          b.type = 'button'; b.className = 'btn btn--outline-dark';
          var s = document.createElement('span'); s.textContent = t.retry || 'Try again';
          b.appendChild(s); b.addEventListener('click', retry);
          stateEl.appendChild(b);
        }
      }
      stateEl.hidden = !msg;
    }

    function load(id) {
      if (cache[id]) { return Promise.resolve(cache[id]); }
      return fetch(cfg.rest + 'product/' + encodeURIComponent(id), { headers: { Accept: 'application/json' } })
        .then(function (r) { if (!r.ok) { throw new Error('http ' + r.status); } return r.json(); })
        .then(function (d) { cache[id] = d; return d; });
    }

    function addFact(label, value) {
      if (!value) { return; }
      var dt = document.createElement('dt'); dt.textContent = label;
      var dd = document.createElement('dd'); dd.textContent = value;
      facts.appendChild(dt); facts.appendChild(dd);
    }

    function show(i, instant) {
      var g = data.gallery;
      if (!g.length) { return; }
      idx = (i + g.length) % g.length;
      var item = g[idx];
      var next = instant ? layer : 1 - layer;
      var img = layers[next];
      var done = function () {
        layers[layer].classList.remove('is-on'); layers[layer].setAttribute('aria-hidden', 'true');
        img.classList.add('is-on'); img.removeAttribute('aria-hidden');
        layer = next;
      };
      img.onload = done;
      img.alt = item.alt || '';
      if (item.srcset) { img.srcset = item.srcset; img.sizes = '(min-width:900px) 34rem, 100vw'; } else { img.removeAttribute('srcset'); }
      img.src = safeUrl(item.src);
      if (img.complete && img.naturalWidth) { done(); }

      [].forEach.call(thumbsEl.children, function (li, n) {
        li.firstChild.setAttribute('aria-current', n === idx ? 'true' : 'false');
      });
      counter.textContent = fmt(t.of || '%1$s / %2$s', idx + 1, g.length);
      // Warm the neighbour image only now (galleries are never preloaded in advance).
      [1, -1].forEach(function (d) { var n = g[(idx + d + g.length) % g.length]; if (n && n.src) { (new Image()).src = safeUrl(n.src); } });
    }

    function render(d) {
      data = d; idx = 0;
      titleEl.textContent = d.title || '';
      catEl.textContent = [(d.categories || []).join(', '), d.season].filter(Boolean).join(' · ');
      facts.textContent = '';
      addFact('Ref.', d.ref);
      addFact('Fabric', d.fabric);
      addFact('Colours', (d.colors || []).join(', '));
      desc.textContent = d.description || '';

      var sep = base.indexOf('?') === -1 ? '?' : '&';
      cta.href = base + sep + 'product=' + encodeURIComponent(d.ref || d.title) + '&product_name=' + encodeURIComponent(d.title || '');

      thumbsEl.textContent = '';
      var multi = (d.gallery || []).length > 1;
      (d.gallery || []).forEach(function (g, n) {
        var li = document.createElement('li');
        var b = document.createElement('button');
        b.type = 'button'; b.className = 'qv__thumb';
        b.setAttribute('aria-label', fmt(t.of || 'Image %1$s of %2$s', n + 1, d.gallery.length));
        var im = document.createElement('img'); im.alt = ''; im.loading = 'lazy'; im.src = safeUrl(g.thumb || g.src);
        b.appendChild(im);
        b.addEventListener('click', function () { show(n); });
        li.appendChild(b); thumbsEl.appendChild(li);
      });
      thumbsEl.hidden = !multi;
      prevBtn.hidden = nextBtn.hidden = counter.hidden = !multi;
      layers[0].classList.remove('is-on'); layers[1].classList.remove('is-on');
      show(0, true);
    }

    function focusables() {
      return [].filter.call(el.querySelectorAll('a[href], button:not([disabled])'), function (n) {
        return n.offsetParent !== null && !n.hidden;
      });
    }

    function open(id, trigger, inquiry) {
      window.clearTimeout(timer);
      lastFocus = trigger || document.activeElement;
      base = inquiry || cfg.inquiry;
      el.hidden = false;
      el.setAttribute('aria-hidden', 'false');
      void el.offsetWidth;                       // let the browser register the closed state
      el.classList.add('is-open');
      htmlEl.classList.add('qv-open');
      content.hidden = true;
      setState(t.loading || 'Loading…');
      window.setTimeout(function () { closeBtn.focus(); }, reduce ? 0 : 120);

      var mine = ++token;
      load(id).then(function (d) {
        if (mine !== token) { return; }
        setState('');
        content.hidden = false;
        render(d);
      }).catch(function () {
        if (mine !== token) { return; }
        setState(t.error || 'Could not load.', function () { open(id, lastFocus, base); });
      });
    }

    function close() {
      if (el.hidden) { return; }
      token++;
      el.classList.remove('is-open');
      el.setAttribute('aria-hidden', 'true');
      htmlEl.classList.remove('qv-open');
      timer = window.setTimeout(function () { el.hidden = true; }, reduce ? 0 : 750);
      if (lastFocus && lastFocus.focus) { lastFocus.focus(); }
    }

    el.addEventListener('click', function (e) { if (e.target.closest('[data-qv-close]')) { close(); } });
    prevBtn.addEventListener('click', function () { if (data) { show(idx - 1); } });
    nextBtn.addEventListener('click', function () { if (data) { show(idx + 1); } });
    document.addEventListener('keydown', function (e) {
      if (el.hidden || !el.classList.contains('is-open')) { return; }
      if (e.key === 'Escape') { e.preventDefault(); close(); return; }
      if (e.key === 'ArrowLeft' && data) { show(idx - 1); }
      if (e.key === 'ArrowRight' && data) { show(idx + 1); }
      if (e.key === 'Tab') {
        var f = focusables();
        if (!f.length) { e.preventDefault(); panel.focus(); return; }
        var first = f[0], last = f[f.length - 1];
        if (e.shiftKey && (document.activeElement === first || document.activeElement === panel)) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
      }
    });

    return { open: open };
  })();

  /* =========================== Grid =========================== */
  function initGrid(root) {
    if (!root || root.getAttribute('data-ready')) { return; }
    root.setAttribute('data-ready', '1');

    var st = {};
    try { st = JSON.parse(root.getAttribute('data-settings') || '{}'); } catch (err) { st = {}; }

    var list = root.querySelector('[data-list]');
    var status = root.querySelector('[data-status]');
    var empty = root.querySelector('[data-empty]');
    var more = root.querySelector('[data-load-more]');
    var filters = root.querySelector('[data-filters]');
    var scroller = root.querySelector('[data-filter-scroll]');
    var ind = root.querySelector('[data-filter-ind]');
    var btns = filters ? [].slice.call(filters.querySelectorAll('[data-term]')) : [];

    var state = { term: st.term || '', page: 1, pages: st.pages || 1, found: st.found || 0, req: 0 };
    var cache = {};

    root.classList.add('is-ready');

    /* ---- entrance + hover-image preloading ---- */
    var revealIO = 'IntersectionObserver' in window ? new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add('is-in'); revealIO.unobserve(en.target); }
      });
    }, { rootMargin: '0px 0px -6% 0px' }) : null;

    function loadHover(card) {
      var img = card.querySelector('img[data-src]');
      if (!img) { return; }
      if (img.getAttribute('data-srcset')) { img.srcset = img.getAttribute('data-srcset'); img.removeAttribute('data-srcset'); }
      img.src = img.getAttribute('data-src');
      img.removeAttribute('data-src');
    }

    var hoverIO = (canHover && !saveData && 'IntersectionObserver' in window) ? new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) { return; }
        hoverIO.unobserve(en.target);
        var run = function () { loadHover(en.target); };
        if (window.requestIdleCallback) { window.requestIdleCallback(run, { timeout: 2500 }); } else { window.setTimeout(run, 400); }
      });
    }, { rootMargin: '150px 0px' }) : null;

    function watch(scope) {
      [].forEach.call(scope.querySelectorAll('.pcard'), function (card) {
        if (revealIO) { revealIO.observe(card); } else { card.classList.add('is-in'); }
        if (card.classList.contains('has-hover') && hoverIO) { hoverIO.observe(card); }
      });
    }
    watch(list);

    list.addEventListener('pointerenter', function (e) {
      var c = e.target.closest && e.target.closest('.pcard.has-hover'); if (c) { loadHover(c); }
    }, true);
    list.addEventListener('focusin', function (e) {
      var c = e.target.closest('.pcard.has-hover'); if (c) { loadHover(c); }
    });

    /* ---- Quick View trigger (delegated) ---- */
    root.addEventListener('click', function (e) {
      var b = e.target.closest('[data-quick-view]');
      if (b && qv) { e.preventDefault(); qv.open(b.getAttribute('data-quick-view'), b, st.inquiry); }
    });

    /* ---- helpers ---- */
    function updateStatus() {
      var shown = list.querySelectorAll('.pcard').length;
      if (status) { status.textContent = fmt(t.showing || 'Showing %1$s of %2$s', shown, state.found); }
      if (empty) { empty.hidden = state.found > 0; }
      if (more) { more.hidden = state.page >= state.pages; more.removeAttribute('aria-busy'); }
    }

    function moveInd() {
      if (!ind) { return; }
      var active = filters.querySelector('.is-active');
      if (!active) { return; }
      ind.style.setProperty('--x', active.offsetLeft + 'px');
      ind.style.setProperty('--w', active.offsetWidth + 'px');
      if (scroller && scroller.scrollWidth > scroller.clientWidth) {
        scroller.scrollTo({ left: active.offsetLeft - (scroller.clientWidth - active.offsetWidth) / 2, behavior: reduce ? 'auto' : 'smooth' });
      }
    }

    function fetchPage(term, page) {
      var key = term + '|' + page;
      if (cache[key]) { return Promise.resolve(cache[key]); }
      var q = new URLSearchParams({
        term: term, page: page, per_page: st.per_page, orderby: st.orderby, featured: st.featured,
        terms: (st.terms || []).join(','), quick: st.quick ? 1 : 0, ref: st.ref ? 1 : 0, season: st.season ? 1 : 0
      });
      return fetch(cfg.rest + 'collection?' + q.toString(), { headers: { Accept: 'application/json' } })
        .then(function (r) { if (!r.ok) { throw new Error('http ' + r.status); } return r.json(); })
        .then(function (d) { cache[key] = d; return d; });
    }

    function sleep(ms) { return new Promise(function (res) { window.setTimeout(res, reduce ? 0 : ms); }); }

    /* ---- filtering ---- */
    function setTerm(term) {
      if (term === state.term) { return; }
      state.term = term;
      var mine = ++state.req;

      btns.forEach(function (b) {
        var on = b.getAttribute('data-term') === term;
        b.classList.toggle('is-active', on);
        b.setAttribute('aria-pressed', on ? 'true' : 'false');
      });
      moveInd();

      try {
        var u = new URL(window.location.href);
        if (term) { u.searchParams.set('collection', term); } else { u.searchParams.delete('collection'); }
        window.history.replaceState(null, '', u.toString());
      } catch (err) { /* ignore */ }

      list.classList.add('is-swapping');
      Promise.all([fetchPage(term, 1), sleep(260)]).then(function (res) {
        if (mine !== state.req) { return; }
        var d = res[0];
        list.innerHTML = d.html;
        state.page = 1; state.pages = d.pages; state.found = d.found;
        watch(list);
        updateStatus();
        window.requestAnimationFrame(function () { list.classList.remove('is-swapping'); });
      }).catch(function () {
        if (mine !== state.req) { return; }
        list.classList.remove('is-swapping');
        if (status) { status.textContent = t.error || 'Could not load.'; }
      });
    }

    btns.forEach(function (b) {
      b.addEventListener('click', function () { setTerm(b.getAttribute('data-term') || ''); });
    });

    /* ---- load more ---- */
    if (more) {
      more.addEventListener('click', function () {
        if (state.page >= state.pages || more.getAttribute('aria-busy') === 'true') { return; }
        more.setAttribute('aria-busy', 'true');
        var mine = state.req;
        var next = state.page + 1;
        fetchPage(state.term, next).then(function (d) {
          if (mine !== state.req) { return; }
          var tmp = document.createElement('div');
          tmp.innerHTML = d.html;
          var first = null;
          while (tmp.firstElementChild) {
            var node = tmp.firstElementChild;
            if (!first) { first = node; }
            list.appendChild(node);
          }
          state.page = next; state.pages = d.pages; state.found = d.found;
          watch(list);
          updateStatus();
          var f = first && first.querySelector('button.pcard__media');
          if (f) { f.focus({ preventScroll: true }); }
        }).catch(function () {
          more.removeAttribute('aria-busy');
          if (status) { status.textContent = t.error || 'Could not load.'; }
        });
      });
    }

    moveInd();
    window.addEventListener('resize', moveInd);
    if (document.fonts && document.fonts.ready) { document.fonts.ready.then(moveInd); }
    window.addEventListener('load', moveInd);
  }

  function initAll() { [].forEach.call(document.querySelectorAll('[data-collection-grid]'), initGrid); }
  initAll();

  // Elementor editor / dynamic re-renders: initialise newly inserted grids exactly once.
  window.addEventListener('elementor/frontend/init', function () {
    if (window.elementorFrontend && window.elementorFrontend.hooks) {
      window.elementorFrontend.hooks.addAction('frontend/element_ready/atelier-collection-grid.default', function ($el) {
        var node = $el && $el[0] ? $el[0] : $el;
        initGrid(node.querySelector ? node.querySelector('[data-collection-grid]') : null);
      });
    }
  });
})();

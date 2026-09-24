/*! ColorlibUI 3.0.0 — core, owl, isotope, scrollup, barfiller, ytbg. Built for this theme from core + the modules it uses. */
/**
 * The interactive pieces these themes actually use, without jQuery.
 *
 * The core: the small helpers every module uses, plus the pieces nearly every
 * theme needs (styled selects, counters, reveal on scroll). Modules for the
 * other plugins the themes used (Owl Carousel, Slick, Magnific Popup, Isotope,
 * SlickNav, ScrollUp, AjaxChimp, ...) are appended after it by the build, only
 * when a theme uses them, and register themselves on window.ColorlibUI.
 * Those libraries are general-purpose; the themes use a narrow slice of them:
 * a looping carousel, a slider with a thumbnail strip, a lightbox for images
 * and video embeds, a styled select, numbers that count up and elements that
 * animate in as they scroll into view.
 *
 * Markup is read from the same class names and data attributes the old
 * plugins used, so templates do not change.
 *
 * Each piece is optional: if the markup is not on the page, nothing runs.
 */
(function () {
  'use strict';

  var PREFERS_REDUCED = window.matchMedia
    ? window.matchMedia('(prefers-reduced-motion: reduce)').matches
    : false;

  /** Elements from a selector, an Element, a NodeList/array, or a jQuery-like object. */
  function toElements(target, root) {
    if (!target) return [];
    if (typeof target === 'string') {
      return Array.prototype.slice.call((root || document).querySelectorAll(target));
    }
    if (target.nodeType === 1) return [target];
    if (typeof target.length === 'number') return Array.prototype.slice.call(target);
    return [];
  }

  /** Dispatch a bubbling CustomEvent carrying detail. */
  function emit(el, type, detail) {
    var event;
    try {
      event = new CustomEvent(type, { bubbles: true, cancelable: true, detail: detail || {} });
    } catch (e) {
      event = document.createEvent('CustomEvent');
      event.initCustomEvent(type, true, true, detail || {});
    }
    return el.dispatchEvent(event);
  }

  /** Shallow-merge plain objects left to right (Object.assign where available). */
  function extend(target) {
    for (var i = 1; i < arguments.length; i++) {
      var src = arguments[i];
      if (!src) continue;
      for (var k in src) {
        if (Object.prototype.hasOwnProperty.call(src, k)) target[k] = src[k];
      }
    }
    return target;
  }

  /** Parse an HTML string into its first element (for navText, prevArrow, ...). */
  function fromHTML(html) {
    var t = document.createElement('template');
    t.innerHTML = String(html).trim();
    return t.content.firstElementChild || document.createTextNode(String(html));
  }

  /** Animate window scroll to y over ms (instant with reduced motion). */
  function scrollToY(y, ms) {
    if (PREFERS_REDUCED || !ms) { window.scrollTo(0, y); return; }
    var from = window.pageYOffset, start = null;
    function step(now) {
      if (start === null) start = now;
      var p = Math.min((now - start) / ms, 1);
      var eased = p < 0.5 ? 2 * p * p : -1 + (4 - 2 * p) * p;
      window.scrollTo(0, from + (y - from) * eased);
      if (p < 1) window.requestAnimationFrame(step);
    }
    window.requestAnimationFrame(step);
  }

  /* ------------------------------------------------------------------ *
   * The jQuery effects the themes use, on the Web Animations API.
   * jQuery's semantics: slideDown/fadeIn show a hidden element (display
   * from the stylesheet, or block), slideUp/fadeOut end with display:none.
   * With reduced motion the end state is applied at once.
   * ------------------------------------------------------------------ */

  function isHidden(el) {
    return window.getComputedStyle(el).display === 'none';
  }

  function show(el) {
    el.style.display = '';
    if (isHidden(el)) el.style.display = 'block';
  }

  function animateTo(el, frames, ms, done) {
    if (PREFERS_REDUCED || !ms || !el.animate) { if (done) done(); return; }
    var anim = el.animate(frames, { duration: ms, easing: 'ease' });
    anim.onfinish = function () { if (done) done(); };
  }

  /** slide(el, 'up' | 'down' | 'toggle', ms = 400, done) */
  function slide(target, dir, ms, done) {
    if (ms === undefined) ms = 400;
    toElements(target).forEach(function (el) {
      var hidden = isHidden(el);
      var down = dir === 'down' || (dir === 'toggle' && hidden);
      if (down && !hidden) return;
      if (!down && hidden) return;
      if (down) show(el);
      var h = el.scrollHeight + 'px';
      el.style.overflow = 'hidden';
      animateTo(el, down ? [{ height: '0px' }, { height: h }] : [{ height: h }, { height: '0px' }], ms, function () {
        el.style.overflow = '';
        if (!down) el.style.display = 'none';
        if (done) done.call(el);
      });
    });
  }

  /** fade(el, 'in' | 'out' | 'toggle', ms = 400, done) */
  function fade(target, dir, ms, done) {
    if (ms === undefined) ms = 400;
    toElements(target).forEach(function (el) {
      var hidden = isHidden(el);
      var fadeIn = dir === 'in' || (dir === 'toggle' && hidden);
      if (fadeIn && !hidden) return;
      if (!fadeIn && hidden) return;
      if (fadeIn) show(el);
      animateTo(el, fadeIn ? [{ opacity: 0 }, { opacity: 1 }] : [{ opacity: 1 }, { opacity: 0 }], ms, function () {
        if (!fadeIn) el.style.display = 'none';
        if (done) done.call(el);
      });
    });
  }

  /** Document offset of an element, like jQuery's .offset(). */
  function offset(el) {
    var r = el.getBoundingClientRect();
    return { top: r.top + window.pageYOffset, left: r.left + window.pageXOffset };
  }

  /**
   * POST/GET to WordPress (admin-ajax.php and friends) the way $.ajax did:
   * data is form-encoded, the response parsed as JSON when it is JSON.
   * Returns a Promise.
   */
  function request(url, opts) {
    opts = opts || {};
    var method = (opts.method || opts.type || 'POST').toUpperCase();
    var body = null;
    if (opts.data) {
      var params = new URLSearchParams();
      Object.keys(opts.data).forEach(function (k) { params.append(k, opts.data[k]); });
      if (method === 'GET') url += (url.indexOf('?') < 0 ? '?' : '&') + params.toString();
      else body = params;
    }
    return fetch(url, { method: method, body: body, credentials: 'same-origin' }).then(function (res) {
      return res.text().then(function (text) {
        try { return JSON.parse(text); } catch (e) { return text; }
      });
    });
  }

  function debounce(fn, wait) {
    var t;
    return function () {
      clearTimeout(t);
      t = setTimeout(fn, wait);
    };
  }

  function videoSource(href) {
    var yt = href.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([\w-]+)/);
    if (yt) return 'https://www.youtube.com/embed/' + yt[1] + '?autoplay=1&rel=0';
    var vm = href.match(/vimeo\.com\/(?:video\/)?(\d+)/);
    if (vm) return 'https://player.vimeo.com/video/' + vm[1] + '?autoplay=1';
    return href;
  }


  /* ------------------------------------------------------------------ *
   * Select
   *
   * Builds the same markup jQuery Nice Select produced, because the themes
   * style that structure: .nice-select > .current, and a .list of .option.
   * The original select stays in the DOM and keeps carrying the value, so
   * forms submit exactly as before and assistive technology still sees it.
   * ------------------------------------------------------------------ */

  function enhanceSelect(select) {
    if (select.dataset.clEnhanced) return;
    select.dataset.clEnhanced = '1';

    var wrap = document.createElement('div');
    wrap.className = 'nice-select ' + (select.className || '');
    wrap.tabIndex = 0;
    wrap.setAttribute('role', 'button');
    wrap.setAttribute('aria-haspopup', 'listbox');
    wrap.setAttribute('aria-expanded', 'false');

    var current = document.createElement('span');
    current.className = 'current';

    var list = document.createElement('ul');
    list.className = 'list';
    list.setAttribute('role', 'listbox');

    Array.prototype.forEach.call(select.options, function (option) {
      var li = document.createElement('li');
      li.className = 'option' + (option.selected ? ' selected' : '') +
        (option.disabled ? ' disabled' : '');
      li.textContent = option.textContent;
      li.dataset.value = option.value;
      li.setAttribute('role', 'option');
      li.setAttribute('aria-selected', option.selected ? 'true' : 'false');

      li.addEventListener('click', function (e) {
        // The wrapper toggles on click; without this the choice would bubble
        // up and reopen the list it just closed.
        e.stopPropagation();
        if (option.disabled) return;
        select.value = option.value;
        select.dispatchEvent(new Event('change', { bubbles: true }));
        sync();
        wrap.classList.remove('open');
        wrap.setAttribute('aria-expanded', 'false');
      });
      list.appendChild(li);
    });

    function sync() {
      var chosen = select.options[select.selectedIndex];
      current.textContent = chosen ? chosen.textContent : '';
      Array.prototype.forEach.call(list.children, function (li) {
        var on = li.dataset.value === select.value;
        li.classList.toggle('selected', on);
        li.setAttribute('aria-selected', on ? 'true' : 'false');
      });
    }

    wrap.addEventListener('click', function () {
      var open = wrap.classList.toggle('open');
      wrap.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    wrap.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); wrap.click(); }
      if (e.key === 'Escape') { wrap.classList.remove('open'); }
    });
    document.addEventListener('click', function (e) {
      if (!wrap.contains(e.target)) {
        wrap.classList.remove('open');
        wrap.setAttribute('aria-expanded', 'false');
      }
    });
    select.addEventListener('change', sync);

    wrap.appendChild(current);
    wrap.appendChild(list);
    select.parentNode.insertBefore(wrap, select);

    // Kept for the form and for assistive technology, but out of the way.
    select.style.position = 'absolute';
    select.style.width = '1px';
    select.style.height = '1px';
    select.style.opacity = '0';
    select.style.pointerEvents = 'none';

    sync();
  }

  /* ------------------------------------------------------------------ *
   * Running when the page is ready
   *
   * Callers are theme scripts in the footer and inline scripts printed by
   * widgets in the middle of the page; both are safe.
   * ------------------------------------------------------------------ */

  function ready(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn);
    } else {
      fn();
    }
  }

  function each(target, fn) {
    ready(function () {
      toElements(target).forEach(fn);
    });
  }

  /** $('select').niceSelect(), without jQuery. */
  function enhanceSelects(selector) {
    each(selector || 'select', function (select) {
      // Nice Select never handled multiple selects, and neither does this.
      if (select.multiple) return;
      enhanceSelect(select);
    });
  }


  /* ------------------------------------------------------------------ *
   * Counter
   *
   * Replaces jQuery CounterUp and the Waypoints library it depended on.
   * Like CounterUp, the number is read from the element's own text and left
   * untouched until the element scrolls into view; it then counts up from
   * zero and always finishes on the original text. Text that is not a plain
   * number ("24/7") is left alone.
   * ------------------------------------------------------------------ */

  function counter(selector, options) {
    var time = (options && options.time) || 1000;

    each(selector, function (el) {
      if (el.dataset.clCounter) return;
      el.dataset.clCounter = '1';

      var text = el.textContent.trim();
      var plain = text.replace(/,/g, '');
      if (!/^\d+(\.\d+)?$/.test(plain)) return;
      if (PREFERS_REDUCED || !('IntersectionObserver' in window)) return;

      var target = parseFloat(plain);
      var decimals = (plain.split('.')[1] || '').length;
      var commas = /\d,\d/.test(text);

      function format(n) {
        var s = n.toFixed(decimals);
        return commas ? s.replace(/\B(?=(\d{3})+(?!\d))/g, ',') : s;
      }

      var observer = new IntersectionObserver(function (entries) {
        if (!entries[0].isIntersecting) return;
        observer.disconnect();

        var start = null;
        function step(now) {
          if (start === null) start = now;
          var progress = Math.min((now - start) / time, 1);
          el.textContent = progress < 1 ? format(target * progress) : text;
          if (progress < 1) window.requestAnimationFrame(step);
        }
        window.requestAnimationFrame(step);
      });
      observer.observe(el);
    });
  }


  /* ------------------------------------------------------------------ *
   * Reveal on scroll
   *
   * Replaces WOW.js, against the same markup: an element with class "wow"
   * and an animate.css animation class, plus optional data-wow-duration,
   * data-wow-delay and data-wow-iteration. It is hidden until it scrolls
   * into view, then gets the "animated" class.
   *
   * The animation name is held at "none" until then, as WOW did: otherwise
   * the animation has already run (at zero duration) by the time "animated"
   * gives it a real one, and nothing moves. With reduced motion requested,
   * or no IntersectionObserver, elements are simply left visible.
   * ------------------------------------------------------------------ */

  function reveal(selector, options) {
    var offset = (options && options.offset) || 0;
    if (PREFERS_REDUCED || !('IntersectionObserver' in window)) return;

    each(selector || '.wow', function (el) {
      if (el.dataset.clReveal) return;
      el.dataset.clReveal = '1';

      el.style.visibility = 'hidden';
      el.style.animationName = 'none';

      var observer = new IntersectionObserver(function (entries) {
        if (!entries[0].isIntersecting) return;
        observer.disconnect();

        var data = el.dataset;
        if (data.wowDuration) el.style.animationDuration = data.wowDuration;
        if (data.wowDelay) el.style.animationDelay = data.wowDelay;
        if (data.wowIteration) el.style.animationIterationCount = data.wowIteration;
        el.style.animationName = '';
        el.style.visibility = 'visible';
        el.classList.add('animated');
      }, { rootMargin: '0px 0px ' + (-offset) + 'px 0px' });
      observer.observe(el);
    });
  }

  var UI = window.ColorlibUI || {};
  extend(UI, {
    version: '3.0.0',
    reducedMotion: PREFERS_REDUCED,
    toElements: toElements,
    each: each,
    emit: emit,
    extend: extend,
    fromHTML: fromHTML,
    scrollToY: scrollToY,
    slide: slide,
    fade: fade,
    offset: offset,
    request: request,
    enhanceSelect: enhanceSelect,
    enhanceSelects: enhanceSelects,
    counter: counter,
    reveal: reveal,
    ready: ready,
    videoSource: videoSource,
    debounce: debounce
  });
  window.ColorlibUI = UI;
}());

/* ColorlibUI module: owl — replaces Owl Carousel 2 (2.3.4, and the 2.2.1 and 2.0-beta builds
 * some themes ship). Themes style the DOM Owl generates (.owl-stage-outer > .owl-stage >
 * .owl-item.active, .owl-nav > .owl-prev/.owl-next, .owl-dots > .owl-dot > span), so this
 * rebuilds that DOM with Owl's own arithmetic: the same item widths and margin-right, stage
 * width, translate3d offsets, loop clones before and after the real items, active/center
 * classes, dot pages and the same responsive merge (largest breakpoint <= window.innerWidth).
 * Option names and defaults are Owl's, so `$(x).owlCarousel(opts)` becomes
 * `ColorlibUI.owl(x, opts)` with the same object.
 *
 * Owl's markup changed between the builds the themes load. `markup` picks it, per call or
 * once per theme with `ColorlibUI.owl.defaults.markup = '2.2'`:
 *   '2.3' (default)  <button> arrows and dots; hidden .owl-nav/.owl-dots get .disabled
 *   '2.2'            the same with <div> arrows and dots (Owl 2.1-2.2)
 *   '2.0'            Owl 2.0 beta: <div> controls inside .owl-controls, shown and hidden with
 *                    display, plus the beta's clone count, unrounded offsets and owl-theme class
 */
(function () {
  'use strict';
  var UI = window.ColorlibUI;
  if (!UI) return;

  var MARKUP = {
    '2.3': { tag: 'button', navText: ['<span aria-label="Previous">&#x2039;</span>', '<span aria-label="Next">&#x203a;</span>'] },
    '2.2': { tag: 'div', navText: ['prev', 'next'] },
    '2.0': { tag: 'div', navText: ['prev', 'next'] }
  };

  var defaults = {
    markup: '2.3',
    items: 3, loop: false, center: false, rewind: false, checkVisibility: true,
    mouseDrag: true, touchDrag: true, pullDrag: true, freeDrag: false,
    margin: 0, stagePadding: 0, startPosition: 0,
    smartSpeed: 250, dragEndSpeed: false, slideTransition: '',
    responsive: {}, responsiveRefreshRate: 200, responsiveClass: false,
    itemElement: 'div', stageElement: 'div', nestedItemSelector: false,
    nav: false, navText: null, navSpeed: false, navElement: null, navContainer: false,
    navContainerClass: 'owl-nav', navClass: ['owl-prev', 'owl-next'], slideBy: 1,
    dots: true, dotClass: 'owl-dot', dotsClass: 'owl-dots', dotsEach: false,
    dotsSpeed: false, dotsContainer: false,
    autoplay: false, autoplayTimeout: 5000, autoplayHoverPause: false, autoplaySpeed: false
  };

  /** $.extend as Owl used it: undefined values (a missing data-* option) keep the default. */
  function merge(target) {
    for (var i = 1; i < arguments.length; i++) {
      var src = arguments[i];
      for (var k in src) {
        if (Object.prototype.hasOwnProperty.call(src, k) && src[k] !== undefined) target[k] = src[k];
      }
    }
    return target;
  }

  function isNumeric(v) { return !isNaN(parseFloat(v)); }
  function kids(el) { return Array.prototype.slice.call(el.children); }
  function indexOf(el) { return el.parentNode ? kids(el.parentNode).indexOf(el) : -1; }
  function visible(el) { return !!(el.offsetWidth || el.offsetHeight || el.getClientRects().length); }
  function cap(s) { return s.charAt(0).toUpperCase() + s.slice(1); }
  function div(cls) { var d = document.createElement('div'); d.className = cls; return d; }
  function show(el, on) { el.style.display = on ? '' : 'none'; }

  /** jQuery's .width(): the content width, fractional, whatever the box-sizing. */
  function contentWidth(el) {
    var cs = window.getComputedStyle(el), w = parseFloat(cs.width);
    if (isNaN(w)) return 0;
    if (cs.boxSizing === 'border-box') {
      w -= (parseFloat(cs.paddingLeft) || 0) + (parseFloat(cs.paddingRight) || 0) +
        (parseFloat(cs.borderLeftWidth) || 0) + (parseFloat(cs.borderRightWidth) || 0);
    }
    return w;
  }

  function pointer(e) {
    var p = e.touches && e.touches.length ? e.touches[0]
      : e.changedTouches && e.changedTouches.length ? e.changedTouches[0] : e;
    return p.pageX ? { x: p.pageX, y: p.pageY } : { x: p.clientX, y: p.clientY };
  }

  /** jQuery's .html(value) as Owl used it for navText: strings are HTML, anything else text. */
  function setContent(el, v) {
    if (typeof v === 'string') el.innerHTML = v;
    else if (v || v === 0) el.textContent = String(v);
  }

  /** An arrow or dot; <div> ones (2.2/2.0 markup) are made keyboard-operable too. */
  function control(tag, cls, content, label) {
    var el = document.createElement(tag);
    el.className = cls;
    setContent(el, content);
    if (tag === 'button') {
      el.type = 'button';
    } else {
      el.setAttribute('role', 'button');
      el.tabIndex = 0;
      el.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); el.click(); }
      });
    }
    if (!/\w/.test(el.textContent)) el.setAttribute('aria-label', label);
    return el;
  }

  /* ------------------------------------------------------------------ *
   * The carousel. Methods mirror Owl's own (setup, refresh, update, to,
   * normalize, relative, coordinates, ...) so the numbers come out the same.
   * Positions are "absolute" (index among the stage's children, clones
   * included) unless called relative (index among the real items).
   * ------------------------------------------------------------------ */

  function Owl(el, options) {
    var o = merge({}, owl.defaults, options);
    var m = MARKUP[o.markup] || MARKUP['2.3'];
    o.navElement = String(o.navElement || m.tag).split(/\s/)[0];
    if (o.navText == null) o.navText = m.navText;

    this.el = el;
    this.options = o;
    this.legacy = o.markup === '2.0';
    this.dotTag = m.tag;
    this.settings = null;
    this._breakpoint = null;
    this._width = 0;
    this._items = [];
    this._clones = [];
    this._coordinates = [];
    this._itemWidth = 0;
    this._current = null;
    this._speed = null;
    this._invalidated = {};
    this._valid = false;
    this._suppress = {};
    this._animating = false;
    this._dragging = false;
    this._drag = {};
    this._pages = [];
    this._controls = null;
    this._auto = { call: null, time: 0, timeout: 0, paused: true, on: false };
    this._off = [];

    this.setup();
    this.initialize();
  }

  var P = Owl.prototype;

  /** Owl's trigger(): a `<name>.owl.carousel` event plus the matching onName option callback. */
  P.trigger = function (name, data, namespace) {
    if (this._suppress[name]) return true;
    var s = this.settings;
    var detail = UI.extend({
      item: { count: this._items.length, index: this._current },
      page: {
        index: this._pages.indexOf(this.page()),
        count: this._pages.length,
        size: s ? (s.center ? 1 : s.dotsEach || s.items) : undefined
      },
      relatedTarget: this
    }, data);
    var ns = 'owl.' + (namespace || 'carousel');
    var ok = UI.emit(this.el, name + '.' + ns, detail);
    var fn = s && s['on' + cap(name) + (namespace ? cap(namespace) : '')];
    if (typeof fn === 'function') {
      fn.call(this, UI.extend({ type: name, namespace: ns, target: this.el }, detail));
    }
    return ok;
  };

  P.listen = function (target, type, fn, opts) {
    target.addEventListener(type, fn, opts || false);
    this._off.push(function () { target.removeEventListener(type, fn, opts || false); });
  };

  /** Settings = options merged with the largest responsive breakpoint <= the viewport width. */
  P.setup = function () {
    var o = this.options, bps = o.responsive, vw = window.innerWidth || document.documentElement.clientWidth;
    var match = -1, s, k;
    if (bps) {
      for (k in bps) {
        if (Object.prototype.hasOwnProperty.call(bps, k) && k <= vw && k > match) match = Number(k);
      }
    }
    // The beta kept its settings object until the breakpoint itself changed.
    if (this.legacy && this.settings && this._breakpoint === match) return;
    s = merge({}, o, bps ? bps[match] : null);
    delete s.responsive;
    if (this.legacy && s.responsiveClass) {
      this.el.className = this.el.className.replace(/\b owl-responsive-\S+/g, '');
      this.el.classList.add('owl-responsive-' + match);
    }
    this.trigger('change', { property: { name: 'settings', value: s } });
    this._breakpoint = match;
    this.settings = s;
    this.invalidate('settings');
    this.trigger('changed', { property: { name: 'settings', value: s } });
    if (!this.legacy) { if (s.autoplay) this.play(); else this.stop(); }
  };

  P.initialize = function () {
    var el = this.el, s = this.settings;
    this.trigger('initialize');
    if (this.legacy) el.classList.add('owl-carousel', 'owl-theme');
    el.classList.add('owl-loading');

    this.outer = div('owl-stage-outer');
    this.stage = document.createElement(s.stageElement || 'div');
    this.stage.className = 'owl-stage';
    this.outer.appendChild(this.stage);
    var content = kids(el);
    el.appendChild(this.outer);
    this.replace(content);

    if (this.legacy) {
      this._width = contentWidth(el);
      this.refresh();
    } else if (this.isVisible()) {
      this.refresh();
    } else {
      this.invalidate('width');
    }
    el.classList.remove('owl-loading');
    el.classList.add('owl-loaded');
    this.bind();
    if (!this.legacy) {
      this.initNav();
      this.navUpdate();
      this.draw();
    }
    this.trigger('initialized');
    if (!this.legacy && this.settings.autoplay) this.play();
    this.watch();
  };

  P.isVisible = function () {
    return (this.options.markup === '2.3' && !this.settings.checkVisibility) || visible(this.el);
  };

  /** Wrap each child element in an .owl-item on the stage (Owl's replace + prepare). */
  P.replace = function (content) {
    var s = this.settings, self = this, found = [];
    this._items = [];
    if (s.nestedItemSelector) {
      content.forEach(function (c) {
        found = found.concat(Array.prototype.slice.call(c.querySelectorAll('.' + s.nestedItemSelector)));
      });
      content = found;
    }
    content.forEach(function (node) {
      if (node.nodeType !== 1) return;
      var item = document.createElement(s.itemElement || 'div');
      item.className = 'owl-item';
      item.appendChild(node);
      self.stage.appendChild(item);
      self._items.push(item);
    });
    this.reset(isNumeric(s.startPosition) ? s.startPosition : 0);
    this.invalidate('items');
  };

  P.refresh = function () {
    if (this.legacy && !this._items.length) return;
    this.trigger('refresh');
    this.setup();
    var s = this.settings;
    if (this.legacy) {
      this.el.classList.toggle('owl-center', !!s.center);
      if (s.loop && this._items.length < s.items) s.loop = false;
    }
    this.update();
    if (this.legacy) {
      if (!visible(this.el)) { this.el.classList.add('owl-hidden'); this._visible = false; }
      if (!this._controls) this.initNav();
      if (s.autoplay) { this.stop(); this.play(); } else this.stop();
    }
    if (this._controls) {
      this.navUpdate();
      this.draw();
    }
    this.trigger('refreshed');
  };

  P.invalidate = function (part) {
    this._invalidated[part] = true;
    this._valid = false;
  };

  /** Owl's worker pipeline, in Owl's order, run for whatever was invalidated. */
  P.update = function () {
    var inv = this._invalidated, layout = inv.width || inv.items || inv.settings, cur, i;
    if (!this.legacy && (inv.width || inv.settings)) this._width = contentWidth(this.el);
    if (layout) cur = this._items[this.relative(this._current)];
    if (inv.items || inv.settings) this.clone();
    if (layout) {
      this.measure();
      this.layout();
    }
    if (inv.items && this._coordinates.length < 1) this.stage.removeAttribute('style');
    if (layout) {
      i = cur ? indexOf(cur) : 0;
      if (this.legacy) { if (cur) this.reset(i); }
      else this.reset(Math.max(this.minimum(), Math.min(this.maximum(), i)));
    }
    if (inv.position) this.animate(this.coordinates(this._current));
    if (layout || inv.position) this.markActive();
    this._invalidated = {};
    this._valid = true;
  };

  /**
   * Loop clones: copies of the first items appended and of the last items prepended,
   * so the view can slide past either end. 2.x makes max(2 x items, 4, item count rounded
   * up to even) of them; the beta only max(2 x items, 4).
   */
  P.clone = function () {
    var s = this.settings, n = this._items.length, stage = this.stage, clones = [], append = [], prepend = [];
    kids(stage).forEach(function (c) { if (c.classList.contains('cloned')) stage.removeChild(c); });
    var view = Math.max(s.items * 2, 4);
    var repeat = this.legacy ? (s.loop ? view : 0)
      : (s.loop && n ? (s.rewind ? view : Math.max(view, Math.ceil(n / 2) * 2)) : 0);
    repeat /= 2;
    while (repeat > 0) {
      clones.push(this.normalize(clones.length / 2, true));
      append.push(this._items[clones[clones.length - 1]]);
      clones.push(this.normalize(n - 1 - (clones.length - 1) / 2, true));
      prepend.unshift(this._items[clones[clones.length - 1]]);
      repeat -= 1;
    }
    this._clones = clones;
    function copy(item) { var c = item.cloneNode(true); c.classList.add('cloned'); return c; }
    append.forEach(function (item) { stage.appendChild(copy(item)); });
    var first = stage.firstChild;
    prepend.forEach(function (item) { stage.insertBefore(copy(item), first); });
  };

  /** Item width and the left edge of every stage child (negative px), as Owl computes them. */
  P.measure = function () {
    var s = this.settings, total = this._clones.length + this._items.length, coords = [], i, w, f = 0;
    if (this.legacy) {
      w = (this.width() / s.items).toFixed(3);
      for (i = 0; i < total; i++) { f += (w * 1) * -1; coords.push(f); }
      this._itemWidth = w - s.margin;
    } else {
      w = (this.width() / s.items).toFixed(3) - s.margin;
      for (i = 0; i < total; i++) coords.push((coords[i - 1] || 0) + (w + s.margin) * -1);
      this._itemWidth = w;
    }
    this._coordinates = coords;
  };

  P.layout = function () {
    var s = this.settings, c = this._coordinates, pad = s.stagePadding, st = this.stage.style, w = this._itemWidth, legacy = this.legacy;
    var width = legacy ? Math.abs(c[c.length - 1]) + 2 * pad : Math.ceil(Math.abs(c[c.length - 1])) + pad * 2;
    if (!isNaN(width)) st.width = width + 'px';
    st.paddingLeft = pad ? pad + 'px' : '';
    st.paddingRight = pad ? pad + 'px' : '';
    kids(this.stage).forEach(function (item) {
      item.style.width = w + 'px';
      if (legacy) {
        item.style.marginRight = s.margin + 'px';
      } else {
        item.style.marginLeft = '';
        item.style.marginRight = s.margin ? s.margin + 'px' : '';
      }
    });
  };

  /** .active on every child whose left edge is inside the view; .center on the current one. */
  P.markActive = function () {
    var s = this.settings, c = this._coordinates, pad = s.stagePadding * 2, current = this._current;
    var begin = this.coordinates(current) + pad, end = begin - this.width(), on = {}, i, inner, outer;
    for (i = 0; i < c.length; i++) {
      inner = c[i - 1] || 0;
      outer = Math.abs(c[i]) - pad;
      if ((inner <= begin && inner > end) || (outer < begin && outer > end)) on[i] = true;
    }
    kids(this.stage).forEach(function (item, j) {
      item.classList.toggle('active', !!on[j]);
      item.classList.toggle('center', !!s.center && j === current);
    });
  };

  P.width = function () {
    return this._width - this.settings.stagePadding * 2 + this.settings.margin;
  };

  P.coordinates = function (position) {
    var c = this._coordinates, x, self = this;
    if (position === undefined) return c.map(function (v, i) { return self.coordinates(i); });
    if (this.settings.center) {
      x = c[position];
      x += (this.width() - x + (c[position - 1] || 0)) / 2;
    } else {
      x = c[position - 1] || 0;
    }
    return this.legacy ? x : Math.ceil(x);
  };

  P.normalize = function (position, relative) {
    var n = this._items.length, m = relative ? 0 : this._clones.length, e;
    if (!isNumeric(position) || n < 1) return undefined;
    position = Number(position);
    if (this.legacy) {
      e = n + m;
      return this._clones.length ? ((position % e) + e) % e
        : Math.max(this.minimum(relative), Math.min(this.maximum(relative), position));
    }
    if (position < 0 || position >= n + m) position = ((position - m / 2) % n + n) % n + m / 2;
    return position;
  };

  /** Absolute position -> index among the real items. */
  P.relative = function (position) {
    if (this.legacy) position = this.normalize(position);
    return this.normalize(position - this._clones.length / 2, true);
  };

  P.minimum = function (relative) {
    return relative ? 0 : this._clones.length / 2;
  };

  P.maximum = function (relative) {
    var s = this.settings, n = this._items.length, max;
    if (this.legacy) {
      if (relative) return n - 1;
      return !s.loop && s.center ? n - 1 : s.loop || s.center ? n + s.items : n - s.items;
    }
    if (s.loop) max = this._clones.length / 2 + n - 1;
    else if (s.center) max = n - 1;
    else max = n - s.items;
    if (relative) max -= this._clones.length / 2;
    return Math.max(max, 0);
  };

  P.speed = function (speed) {
    if (speed !== undefined) this._speed = speed;
    return this._speed;
  };

  P.duration = function (from, to, factor) {
    if (factor === 0 && !this.legacy) return 0;
    return Math.min(Math.max(Math.abs(to - from), 1), 6) * Math.abs(factor || this.settings.smartSpeed);
  };

  /** Jump to a position without animating or emitting translate events. */
  P.reset = function (position) {
    position = this.normalize(position);
    if (position === undefined) return;
    this._speed = 0;
    this._current = position;
    this._suppress.translate = this._suppress.translated = true;
    this.animate(this.coordinates(position));
    delete this._suppress.translate;
    delete this._suppress.translated;
  };

  P.animate = function (x) {
    var self = this, moving = this.speed() > 0, s = this.settings;
    var ms = UI.reducedMotion ? 0 : this.speed();
    if (this._animating && !this.legacy) this.onTransitionEnd();
    if (moving || this.legacy) {
      this._animating = moving;
      this.trigger('translate');
    }
    if (!isNaN(x)) this.stage.style.transform = 'translate3d(' + x + 'px,0px,0px)';
    this.stage.style.transition = (ms / 1000) + 's' + (s.slideTransition ? ' ' + s.slideTransition : '');
    clearTimeout(this._endTimer);
    if (moving) {
      if (!ms) {
        this.onTransitionEnd();
      } else {
        // transitionend never fires when the offset did not change, or the carousel is hidden.
        this._endTimer = setTimeout(function () { if (self._animating) self.onTransitionEnd(); }, ms + 100);
      }
    }
  };

  P.onTransitionEnd = function (e) {
    if (e && (e.target !== this.stage || !this._animating)) return;
    clearTimeout(this._endTimer);
    this._animating = false;
    this.trigger('translated');
    if (this.legacy && this.settings.autoplay && this._auto.on) { this.stop(); this.play(); }
  };

  /** Get, or set and announce, the absolute current position. */
  P.current = function (position) {
    if (position === undefined) return this._current;
    if (!this._items.length) return undefined;
    position = this.normalize(position);
    if (this._current !== position) {
      this.trigger('change', { property: { name: 'position', value: position } });
      this._current = position;
      this.invalidate('position');
      if (this._controls) this.draw();
      if (this._auto.paused) this._auto.time = 0;
      this.trigger('changed', { property: { name: 'position', value: position } });
    }
    return this._current;
  };

  /**
   * Owl's core to(): slide to a relative position. In a loop it first jumps (unseen) to the
   * equivalent real item or clone, so the slide always has room to move the short way.
   */
  P.go = function (position, speed) {
    var s = this.settings, n = this._items.length, current = this._current, self = this;
    var distance, direction, min, max, revert, f, h, fwd;
    position = Number(position);
    if (isNaN(position) || !n) return;

    if (this.legacy) {
      if (!s.loop) {
        this.speed(this.duration(current, position, speed));
        this.current(position);
        this.update();
        return;
      }
      distance = position - this.relative(current);
      f = current;
      h = current + distance;
      fwd = current - h < 0;
      if (h < s.items && !fwd) { f = current + n; this.reset(f); }
      else if (h >= this._clones.length + n - s.items && fwd) { f = current - n; this.reset(f); }
      clearTimeout(this._loopTimer);
      this._loopTimer = setTimeout(function () {
        void self.stage.offsetWidth;
        self.speed(self.duration(self._current, f + distance, speed));
        self.current(f + distance);
        self.update();
      }, 30);
      return;
    }

    distance = position - this.relative(current);
    direction = (distance > 0) - (distance < 0);
    min = this.minimum();
    max = this.maximum();
    if (s.loop) {
      if (!s.rewind && Math.abs(distance) > n / 2) distance += direction * -1 * n;
      position = current + distance;
      revert = ((position - min) % n + n) % n + min;
      if (revert !== position && revert - distance <= max && revert - distance > 0) {
        current = revert - distance;
        position = revert;
        this.reset(current);
        // Commit the jump before the animated move starts from it (Owl got this from
        // the layout read in isVisible()).
        void this.stage.offsetWidth;
      }
    } else if (s.rewind) {
      max += 1;
      position = (position % max + max) % max;
    } else {
      position = Math.max(min, Math.min(max, position));
    }
    this.speed(this.duration(current, position, speed));
    this.current(position);
    if (this.isVisible()) this.update();
  };

  /* Navigation: public next/prev/to, as Owl's navigation plugin overrides them. */

  P.page = function () {
    var r = this.relative(this._current), hit, i;
    for (i = 0; i < this._pages.length; i++) {
      if (this._pages[i].start <= r && this._pages[i].end >= r) hit = this._pages[i];
    }
    return hit;
  };

  P.step = function (forward) {
    var s = this.settings, i, len;
    if (s.slideBy == 'page') {
      i = this._pages.indexOf(this.page());
      len = this._pages.length;
      i += forward ? 1 : -1;
      return this._pages[((i % len) + len) % len].start;
    }
    return this.relative(this._current) + (forward ? s.slideBy : -s.slideBy);
  };

  P.next = function (speed) { this.go(this.step(true), speed); };
  P.prev = function (speed) { this.go(this.step(false), speed); };

  /** Slide to a dot page (Owl's navigation to()); standard=true slides to an item index. */
  P.to = function (position, speed, standard) {
    var len = this._pages.length;
    if (!standard && len) this.go(this._pages[((position % len) + len) % len].start, speed);
    else this.go(position, speed);
  };

  P.initNav = function () {
    var s = this.settings, el = this.el, self = this, c = this._controls = {}, legacy = this.legacy;
    var find = function (t) { return UI.toElements(t)[0]; };
    if (legacy && !(s.navContainer && s.dotsContainer)) el.appendChild(c.box = div('owl-controls'));

    // A container option that matches nothing leaves the controls detached, as $() did.
    c.ownAbs = !s.dotsContainer;
    c.abs = (!c.ownAbs && find(s.dotsContainer)) || div(s.dotsClass);
    c.ownRel = !s.navContainer;
    c.rel = (!c.ownRel && find(s.navContainer)) || div(s.navContainerClass);
    if (legacy) {
      if (c.ownAbs) { show(c.abs, false); c.box.appendChild(c.abs); }
      if (c.ownRel) c.box.insertBefore(c.rel, c.box.firstChild);
    } else {
      if (c.ownRel) el.appendChild(c.rel);
      if (c.ownAbs) el.appendChild(c.abs);
      c.rel.classList.add('disabled');
      c.abs.classList.add('disabled');
    }

    c.prev = control(s.navElement, s.navClass[0], s.navText && s.navText[0], 'Previous');
    c.next = control(s.navElement, s.navClass[1], s.navText && s.navText[1], 'Next');
    if (legacy) { show(c.prev, false); show(c.next, false); }
    c.rel.insertBefore(c.prev, c.rel.firstChild);
    c.rel.appendChild(c.next);
    c.prev.addEventListener('click', function () { self.prev(self.settings.navSpeed); });
    c.next.addEventListener('click', function () { self.next(self.settings.navSpeed); });

    this.listen(c.abs, 'click', function (e) {
      var t = e.target;
      while (t && t.parentNode !== c.abs) t = t.parentNode;
      if (!t || t.tagName.toLowerCase() !== self.dotTag) return;
      e.preventDefault();
      self.to(indexOf(t), self.settings.dotsSpeed);
    });
  };

  /** Dot pages: one per `items` real items (one per item in center mode). */
  P.navUpdate = function () {
    var s = this.settings, lower = this._clones.length / 2, upper = lower + this._items.length;
    var max = this.maximum(true), size = s.center ? 1 : s.dotsEach || s.items, i, j, start;
    if (s.slideBy !== 'page') s.slideBy = Math.min(s.slideBy, s.items);
    if (!(s.dots || s.slideBy == 'page')) return;
    this._pages = [];
    for (i = lower, j = 0; i < upper; i++) {
      if (j >= size || j === 0) {
        start = this.legacy ? i - lower : Math.min(max, i - lower);
        this._pages.push({ start: start, end: i - lower + size - 1 });
        if (!this.legacy && start === max) break;
        j = 0;
      }
      j += 1;
    }
  };

  P.draw = function () {
    var s = this.settings, c = this._controls, n = this._items.length, dots, dot, i, k, active, diff;
    var index = this.relative(this._current), loop = s.loop || s.rewind, few = n <= s.items;
    if (this.legacy) {
      show(c.prev, s.nav);
      show(c.next, s.nav);
    } else {
      c.rel.classList.toggle('disabled', !s.nav || few);
      if (s.nav) {
        c.prev.classList.toggle('disabled', !loop && index <= this.minimum(true));
        c.next.classList.toggle('disabled', !loop && index >= this.maximum(true));
      }
      c.abs.classList.toggle('disabled', !s.dots || few);
    }
    if (s.dots) {
      diff = this._pages.length - c.abs.children.length;
      for (; diff > 0; diff--) {
        dot = control(this.dotTag, s.dotClass, '', '');
        dot.appendChild(document.createElement('span'));
        c.abs.appendChild(dot);
      }
      for (; diff < 0; diff++) c.abs.removeChild(c.abs.lastElementChild);
      Array.prototype.forEach.call(c.abs.querySelectorAll('.active'), function (a) { a.classList.remove('active'); });
      dots = c.abs.children;
      i = this._pages.indexOf(this.page());
      active = dots[i < 0 ? dots.length + i : i];   // jQuery .eq(-1) is the last dot
      for (k = 0; k < dots.length; k++) {
        dots[k].setAttribute('aria-label', 'Go to slide ' + (k + 1));
        if (dots[k] === active) dots[k].setAttribute('aria-current', 'true');
        else dots[k].removeAttribute('aria-current');
      }
      if (active) active.classList.add('active');
    }
    if (this.legacy) show(c.abs, s.dots);
  };

  /* Autoplay: Owl 2.3's clock, which keeps its cadence through hover pauses. */

  P.play = function (timeout, speed) {
    var a = this._auto, self = this, elapsed;
    if (UI.reducedMotion) return;
    a.on = true;
    timeout = timeout || this.settings.autoplayTimeout;
    elapsed = Math.min(a.time % (a.timeout || timeout), timeout);
    if (a.paused) { a.time = this.readClock(); a.paused = false; }
    else clearTimeout(a.call);
    a.time += this.readClock() % timeout - elapsed;
    a.timeout = timeout;
    a.call = setTimeout(function () { self.autoNext(speed); }, timeout - elapsed);
  };

  P.readClock = function () { return new Date().getTime() - this._auto.time; };

  P.autoNext = function (speed) {
    var a = this._auto, self = this;
    a.call = setTimeout(function () { self.autoNext(speed); },
      a.timeout * (Math.round(this.readClock() / a.timeout) + 1) - this.readClock());
    if (this._dragging || document.hidden || (this.legacy && this._animating)) return;
    this.next(speed || this.settings.autoplaySpeed);
  };

  P.stop = function () {
    var a = this._auto;
    if (!a.on) return;
    a.time = 0;
    a.paused = true;
    clearTimeout(a.call);
    a.on = false;
  };

  P.pause = function () {
    var a = this._auto;
    if (!a.on || a.paused) return;
    a.time = this.readClock();
    a.paused = true;
    clearTimeout(a.call);
  };

  /* Events: stage transitions, window resize, drag and swipe, hover pause, commands. */

  P.bind = function () {
    var self = this, el = this.el, stage = this.stage, s = this.settings;
    var cancel = function (e) { e.preventDefault(); };
    var hover = function (pause) {
      return function () {
        if (self.settings.autoplayHoverPause && self._auto.on) { if (pause) self.pause(); else self.play(); }
      };
    };

    this.listen(stage, 'transitionend', function (e) { self.onTransitionEnd(e); });
    if (s.responsive !== false) {
      this.listen(window, 'resize', function () {
        clearTimeout(self._resizeTimer);
        self._resizeTimer = setTimeout(function () { self.onResize(); }, self.settings.responsiveRefreshRate);
      });
    }

    this._move = function (e) { self.onDragMove(e); };
    this._up = function (e) { self.onDragEnd(e); };
    var down = function (e) { self.onDragStart(e); };
    if (s.mouseDrag) {
      if (!this.legacy) el.classList.add('owl-drag');
      this.listen(stage, 'mousedown', down);
      this.listen(stage, 'dragstart', cancel);
      this.listen(stage, 'selectstart', cancel);
    } else if (this.legacy) {
      el.classList.add('owl-text-select-on');
    }
    if (s.touchDrag) {
      this.listen(stage, 'touchstart', down, { passive: true });
      this.listen(stage, 'touchcancel', this._up);
    }

    this.listen(el, 'mouseover', hover(true));
    this.listen(el, 'mouseleave', hover(false));
    this.listen(el, 'touchstart', hover(true), { passive: true });
    this.listen(el, 'touchend', hover(false));

    // $el.trigger('next.owl.carousel') and friends, as CustomEvents. Arguments come in
    // detail as an array (jQuery's extra parameters) or as named fields.
    var commands = {
      'next.owl.carousel': ['next', 'speed'],
      'prev.owl.carousel': ['prev', 'speed'],
      'to.owl.carousel': ['to', 'position', 'speed', 'standard'],
      'refresh.owl.carousel': ['refresh'],
      'destroy.owl.carousel': ['destroy'],
      'play.owl.autoplay': ['play', 'timeout', 'speed'],
      'stop.owl.autoplay': ['stop']
    };
    Object.keys(commands).forEach(function (type) {
      var spec = commands[type];
      self.listen(el, type, function (e) {
        var d = e.detail || {};
        // Skip our own lifecycle events (refresh.owl.carousel) and nested carousels' commands.
        if (e.target !== el || d.relatedTarget === self) return;
        self[spec[0]].apply(self, Array.isArray(d) ? d : spec.slice(1).map(function (k) { return d[k]; }));
      });
    });
  };

  P.onResize = function () {
    var w;
    if (!this._items.length) return;
    w = contentWidth(this.el);
    if (this._width === w) return;
    if (!this.legacy && !this.isVisible()) return;
    if (!this.trigger('resize')) return;
    if (this.legacy) this._width = w;
    this.invalidate('width');
    this.refresh();
    this.trigger('resized');
  };

  /** Owl's AutoRefresh: lay out again when a carousel that started hidden becomes visible. */
  P.watch = function () {
    var self = this;
    if (this._visible === undefined) this._visible = this.isVisible();
    var check = function () {
      var v = self.isVisible();
      if (v === self._visible) return;
      self._visible = v;
      self.el.classList.toggle('owl-hidden', !v);
      if (!v) return;
      // The beta kept the width it measured while hidden; measure it now.
      if (self.legacy) self._width = contentWidth(self.el);
      self.invalidate('width');
      self.refresh();
    };
    if (window.ResizeObserver) {
      this._observer = new ResizeObserver(check);
      this._observer.observe(this.el);
    } else {
      this._watchTimer = setInterval(check, 500);
    }
  };

  P.onDragStart = function (e) {
    var d = this._drag, m, x, y;
    if (e.which === 3) return;
    m = window.getComputedStyle(this.stage).transform.replace(/.*\(|\)| /g, '').split(',');
    x = parseFloat(m[m.length === 16 ? 12 : 4]);
    y = parseFloat(m[m.length === 16 ? 13 : 5]);
    if (this._animating) {
      this.animate(x);
      this.invalidate('position');
    }
    (this.legacy ? this.stage : this.el).classList.toggle('owl-grab', e.type === 'mousedown');
    this.speed(0);
    d.time = new Date().getTime();
    d.target = e.target;
    d.start = { x: x, y: y };
    d.current = { x: x, y: y };
    d.pointer = pointer(e);
    d.first = true;
    this.dragListeners(true);
  };

  /** Document listeners that live only while a drag is in progress. */
  P.dragListeners = function (on) {
    var m = on ? 'addEventListener' : 'removeEventListener';
    document[m]('mouseup', this._up);
    document[m]('touchend', this._up);
    document[m]('mousemove', this._move);
    // Not passive: a horizontal swipe must be able to stop the page from scrolling.
    document[m]('touchmove', this._move, { passive: false });
  };

  P.onDragMove = function (e) {
    var d = this._drag, s = this.settings, p, dx, dy, x, min, max, pull;
    if (!d.pointer) return;
    p = pointer(e);
    dx = d.pointer.x - p.x;
    dy = d.pointer.y - p.y;
    if (d.first) {
      // The first move decides: mostly vertical is a page scroll, not a drag.
      d.first = false;
      if (Math.abs(dx) < Math.abs(dy) && this._valid) return;
      e.preventDefault();
      this._dragging = true;
      this.trigger('drag');
      return;
    }
    if (!this._dragging) return;
    e.preventDefault();
    x = d.start.x - dx;
    if (s.loop && this.legacy) {
      // The beta shifted by one set of items once the drag passed the clones.
      pull = (s.center ? this.coordinates(0) : 0) - this.coordinates(this._items.length);
      if (x > this.coordinates(this.minimum()) && dx < 0) x -= pull;
      else if (x < this.coordinates(this.maximum()) && dx > 0) x += pull;
    } else if (s.loop) {
      min = this.coordinates(this.minimum());
      max = this.coordinates(this.maximum() + 1) - min;
      x = (((x - min) % max + max) % max) + min;
    } else {
      min = this.coordinates(this.minimum());
      max = this.coordinates(this.maximum());
      pull = s.pullDrag ? -1 * dx / 5 : 0;
      x = Math.max(Math.min(x, min + pull), max + pull);
    }
    d.current = { x: x, y: d.start.y };
    this.animate(x);
  };

  P.onDragEnd = function (e) {
    var d = this._drag, s = this.settings, p, dx, direction, target = d.target;
    if (!d.pointer) return;
    p = pointer(e);
    dx = d.pointer.x - p.x;
    direction = dx > 0 ? 'left' : 'right';
    d.pointer = null;
    this.dragListeners(false);
    (this.legacy ? this.stage : this.el).classList.remove('owl-grab');

    if ((dx !== 0 && this._dragging) || !this._valid) {
      this.speed(s.dragEndSpeed || s.smartSpeed);
      this.current(this.closest(d.current.x, dx !== 0 ? direction : d.direction));
      this.invalidate('position');
      this.update();
      d.direction = direction;
      if (Math.abs(dx) > 3 || new Date().getTime() - d.time > 300) {
        // A drag must not also click the link or image it started on.
        var swallow = function (ev) { ev.preventDefault(); ev.stopPropagation(); target.removeEventListener('click', swallow, true); };
        target.addEventListener('click', swallow, true);
        setTimeout(function () { target.removeEventListener('click', swallow, true); }, 400);
      }
    }
    if (!this._dragging) return;
    this._dragging = false;
    this.trigger('dragged');
  };

  /** The absolute position a drag released at `x` settles on (Owl's closest()). */
  P.closest = function (x, direction) {
    var position = -1, pull = 30, width = this.width(), c = this.coordinates(), s = this.settings, i, v, nextV;
    if (!s.freeDrag) {
      for (i = 0; i < c.length && position === -1; i++) {
        v = c[i];
        nextV = c[i + 1] !== undefined ? c[i + 1] : v - width;
        // The beta snapped to any edge within 30px; 2.x checks the far edge on a right pull.
        if ((direction === 'left' || this.legacy) && x > v - pull && x < v + pull) position = i;
        else if (direction === 'right' && !this.legacy && x > v - width - pull && x < v - width + pull) position = i + 1;
        else if (x < v && x > (this.legacy ? c[i + 1] || v - width : nextV)) position = direction === 'left' ? i + 1 : i;
      }
    }
    if (!s.loop) {
      if (x > c[this.minimum()]) position = this.minimum();
      else if (x < c[this.maximum()]) position = this.maximum();
    }
    return position;
  };

  /** Undo everything: controls, clones and wrappers go; the original children come back. */
  P.destroy = function () {
    var el = this.el, c = this._controls, outer = this.outer;
    this.stop();
    clearTimeout(this._resizeTimer);
    clearTimeout(this._endTimer);
    clearTimeout(this._loopTimer);
    clearInterval(this._watchTimer);
    if (this._observer) this._observer.disconnect();
    this._off.forEach(function (off) { off(); });
    this._off = [];
    if (this._move) this.dragListeners(false);
    if (c) {
      [c.prev, c.next].forEach(function (b) { if (b.parentNode) b.parentNode.removeChild(b); });
      if (c.ownRel && c.rel.parentNode) c.rel.parentNode.removeChild(c.rel);
      if (c.ownAbs && c.abs.parentNode) c.abs.parentNode.removeChild(c.abs);
      else if (!c.ownAbs) c.abs.innerHTML = '';
      if (c.box && c.box.parentNode) c.box.parentNode.removeChild(c.box);
    }
    this._items.forEach(function (item) {
      while (item.firstChild) el.insertBefore(item.firstChild, outer);
    });
    el.removeChild(outer);
    el.classList.remove('owl-refresh', 'owl-loading', 'owl-loaded', 'owl-rtl', 'owl-drag', 'owl-grab', 'owl-hidden', 'owl-text-select-on');
    el.removeAttribute('data-cl-owl');
    delete el._clOwl;
  };

  /** The real item elements (.owl-item wrappers), or one of them by relative position. */
  P.items = function (position) {
    return position === undefined ? this._items.slice() : this._items[this.normalize(position, true)];
  };

  /* ------------------------------------------------------------------ *
   * Entry point
   * ------------------------------------------------------------------ */

  function owl(target, options) {
    function run() {
      return UI.toElements(target).map(function (el) {
        if (!el._clOwl) {
          el.setAttribute('data-cl-owl', '');
          el._clOwl = new Owl(el, options);
        }
        return el._clOwl;
      });
    }
    // Like jQuery, start the carousels that exist now (an inline script right after its
    // markup gets its instance back). A selector used while the page is still parsing
    // also picks up matching elements parsed later, once the DOM is ready.
    if (document.readyState === 'loading' && typeof target === 'string') UI.ready(run);
    return run();
  }

  owl.defaults = defaults;
  /** The instance on an element, like $(el).data('owl.carousel'). */
  owl.instance = function (target) {
    var el = UI.toElements(target)[0];
    return (el && el._clOwl) || null;
  };
  owl.Constructor = Owl;

  UI.owl = owl;
}());

/* ColorlibUI module: isotope — replaces Isotope 3.0.5, the jQuery bridge to Masonry and
 * jQuery imagesLoaded. WordPress core already ships Masonry 4.2.2 (window.Masonry, script
 * handle 'masonry') and imagesLoaded 5 (window.imagesLoaded, handle 'imagesloaded'), so
 * UI.masonry() and UI.imagesLoaded() are thin wrappers over those globals, and UI.isotope()
 * is filtering on top of a core Masonry instance. Isotope's default layout mode *is*
 * Masonry's code, so laying out only the matching items with Masonry gives the same
 * geometry and the same inline styles (position/left/top on items, position/height on the
 * container); items that are filtered out are hidden with Masonry's own hide() (the
 * opacity/scale transition that ends in display:none, exactly Isotope's). fitRows is a
 * small override of the same Masonry instance's layout methods.
 */
(function () {
  'use strict';
  var UI = window.ColorlibUI;
  if (!UI) return;

  var slice = Array.prototype.slice;
  var warned = {};

  /** A core global, or a console warning once: the theme's enqueue declares the dependency. */
  function lib(name) {
    var fn = window[name];
    if (!fn && !warned[name]) {
      warned[name] = true;
      if (window.console) {
        console.warn('ColorlibUI: window.' + name + ' is missing; enqueue the WordPress core "' +
          name.toLowerCase() + '" script.');
      }
    }
    return fn;
  }

  /** Emitter.once that really runs once (EvEmitter 1.x keys once-listeners by source text). */
  function once(emitter, type, fn) {
    function handler() {
      emitter.off(type, handler);
      fn.apply(this, arguments);
    }
    emitter.on(type, handler);
  }

  /**
   * Outlayer's dispatchEvent runs its own listeners, then triggers the jQuery event; the DOM
   * event goes in that same place, so events reach the page in the order jQuery saw them
   * (arrangeComplete before the layoutComplete that completed it).
   */
  function bridgeEvents(layout, el, instance) {
    var dispatch = layout.dispatchEvent;
    layout.dispatchEvent = function (type, event, args) {
      dispatch.call(this, type, event, args);
      UI.emit(el, type, { items: (args && args[0]) || [], instance: instance });
    };
  }

  /**
   * Isotope and Masonry never re-layout by themselves when images arrive; themes that init
   * on DOM ready then show overlapping items. Lay out again as late images load (only
   * images still loading at init, so a finished page gets no extra layoutComplete).
   */
  function relayoutOnImages(el, layout) {
    var imagesLoaded = window.imagesLoaded;
    if (!imagesLoaded) return;
    var pending = slice.call(el.querySelectorAll('img')).some(function (img) {
      return !img.complete;
    });
    if (pending) imagesLoaded(el).on('progress', UI.debounce(layout, 60));
  }

  /** Run fn per element at DOM ready; jQuery-bridge return value (method result or list). */
  function run(name, target, fn) {
    var list = [];
    var ret;
    UI.each(target, function (el) {
      var r = fn(el);
      if (!r) return;
      list.push(r.instance);
      if (r.value !== undefined && ret === undefined) ret = r.value;
    });
    // $grid = $('.grid').isotope({...}); $grid.isotope({ filter: v }) keeps working.
    list[name] = function () {
      return UI[name].apply(null, [target].concat(slice.call(arguments)));
    };
    return ret !== undefined ? ret : list;
  }

  /** $('.grid').plugin('method', ...) */
  function callMethod(instance, method, args) {
    if (!instance || method.charAt(0) === '_' || typeof instance[method] !== 'function') return null;
    return { instance: instance, value: instance[method].apply(instance, args) };
  }

  function reduceMotion(options) {
    return UI.reducedMotion ? UI.extend({}, options, { transitionDuration: 0 }) : options;
  }

  /* ------------------------------------------------------------------ *
   * imagesLoaded
   * ------------------------------------------------------------------ */

  /** $(target).imagesLoaded(callback): one callback once every image inside has loaded or failed. */
  UI.imagesLoaded = function (target, options, callback) {
    var imagesLoaded = lib('imagesLoaded');
    var list = [];
    if (!imagesLoaded) return list;
    UI.ready(function () {
      list.push(imagesLoaded(UI.toElements(target), options, callback));
    });
    return list;
  };

  /* ------------------------------------------------------------------ *
   * Masonry
   * ------------------------------------------------------------------ */

  /** $('.grid').masonry({...}): new Masonry(el, options), same options. */
  UI.masonry = function (target, options) {
    var Masonry = lib('Masonry');
    var args = slice.call(arguments, 2);
    if (!Masonry) return [];
    return run('masonry', target, function (el) {
      var msnry = Masonry.data(el);
      if (typeof options === 'string') return callMethod(msnry, options, args);
      if (msnry) {
        // What the jQuery bridge did on a second call: set options, lay out again.
        msnry.option(reduceMotion(options || {}));
        msnry.layout();
        return { instance: msnry };
      }
      var opts = UI.extend({}, reduceMotion(options || {}));
      var initLayout = opts.isInitLayout !== undefined ? opts.isInitLayout : opts.initLayout !== false;
      delete opts.isInitLayout;
      opts.initLayout = false;
      el.setAttribute('data-cl-masonry', '1');
      msnry = new Masonry(el, opts);
      // Listen before the first layout so its layoutComplete reaches the page too.
      bridgeEvents(msnry, el, msnry);
      if (initLayout) msnry.layout();
      relayoutOnImages(el, function () {
        if (Masonry.data(el) === msnry) msnry.layout();
      });
      return { instance: msnry };
    });
  };

  /* ------------------------------------------------------------------ *
   * Isotope
   * ------------------------------------------------------------------ */

  // Isotope reads these from options.masonry, never from the top level: sunshine-wedding's
  // top-level `gutter: 10` did nothing, so it must not reach Masonry either.
  var MODE_KEYS = { columnWidth: 1, gutter: 1, fitWidth: 1, isFitWidth: 1, horizontalOrder: 1 };
  var OWN_KEYS = {
    filter: 1, layoutMode: 1, masonry: 1, fitRows: 1, vertical: 1, sortBy: 1, getSortData: 1,
    sortAscending: 1, isJQueryFiltering: 1, initLayout: 1, isInitLayout: 1
  };
  var LAYOUT_METHODS = ['_resetLayout', '_getItemLayoutPosition', '_getContainerSize', 'needsResizeLayout'];

  /** matches(), plus jQuery's leniency for class names CSS rejects (".2019" from a slug). */
  function matches(el, selector) {
    try {
      return el.matches(selector);
    } catch (e) {
      return selector.split(',').some(function (part) {
        part = part.trim();
        if (part === '*') return true;
        if (!/^(\.[\w-]+)+$/.test(part)) return false;
        return part.slice(1).split('.').every(function (c) { return el.classList.contains(c); });
      });
    }
  }

  /** Outlayer's _getMeasurement: a selector/element's size, or the number as given. */
  function measure(root, option, prop) {
    if (!option) return 0;
    var el = typeof option === 'string' ? root.querySelector(option) :
      option instanceof HTMLElement ? option : null;
    if (!el) return option;
    return window.getSize ? window.getSize(el)[prop] : el.offsetWidth;
  }

  function setPositionInt(x, y) {
    this.position.x = parseInt(x, 10);
    this.position.y = parseInt(y, 10);
  }

  /**
   * Every Isotope the themes shipped (3.0.1 to 3.0.5, Outlayer 2.1.0) truncated item positions
   * to whole pixels; core Masonry (Outlayer 2.1.1) keeps the fractions. Truncate too, so items
   * land on the same pixels and write the same left/top values as before.
   */
  function wholePixels(m) {
    var itemize = m._itemize;
    function patch(items) {
      items.forEach(function (item) { item.setPosition = setPositionInt; });
      return items;
    }
    patch(m.items);
    m._itemize = function () {
      return patch(itemize.apply(this, arguments));
    };
  }

  function Isotope(el, options) {
    this.element = el;
    this.options = { layoutMode: 'masonry' };
    this.option(options);
    this.masonry = new window.Masonry(el, this._masonryOptions());
    this.items = this.masonry.items;
    this.filteredItems = this.items;
    this._setMode();
    wholePixels(this.masonry);
    bridgeEvents(this.masonry, el, this);
    if (this._getOption('initLayout') !== false) this.layout();

    var self = this;
    relayoutOnImages(el, function () {
      if (el.__clIsotope === self) self.layout();
    });
  }

  var proto = Isotope.prototype;

  proto._getOption = function (name) {
    var old = { initLayout: 'isInitLayout', layoutInstant: 'isLayoutInstant' }[name];
    return old && this.options[old] !== undefined ? this.options[old] : this.options[name];
  };

  /** Isotope's options as Masonry options: Outlayer ones as given, mode ones from `masonry`. */
  proto._masonryOptions = function () {
    var src = this.options;
    var out = {};
    for (var k in src) {
      if (Object.prototype.hasOwnProperty.call(src, k) && !MODE_KEYS[k] && !OWN_KEYS[k]) out[k] = src[k];
    }
    UI.extend(out, src.masonry);
    out.initLayout = false; // Isotope filters before its first layout
    return reduceMotion(out);
  };

  proto.option = function (opts) {
    UI.extend(this.options, opts);
    if (this.masonry) {
      this.masonry.option(this._masonryOptions());
      this._setMode();
    }
  };

  /** Masonry's own methods for layoutMode 'masonry'; instance overrides for 'fitRows'. */
  proto._setMode = function () {
    var m = this.masonry;
    var self = this;
    LAYOUT_METHODS.forEach(function (k) { delete m[k]; });
    if (this.options.layoutMode !== 'fitRows') return;
    // isotope-layout/js/layout-modes/fit-rows.js
    m._resetLayout = function () {
      this.getSize();
      this.x = 0;
      this.y = 0;
      this.maxY = 0;
      this.gutter = measure(this.element, (self.options.fitRows || {}).gutter, 'outerWidth');
    };
    m._getItemLayoutPosition = function (item) {
      item.getSize();
      var itemWidth = item.size.outerWidth + this.gutter;
      var containerWidth = this.size.innerWidth + this.gutter;
      if (this.x !== 0 && itemWidth + this.x > containerWidth) {
        this.x = 0;
        this.y = this.maxY;
      }
      var position = { x: this.x, y: this.y };
      this.maxY = Math.max(this.maxY, this.y + item.size.outerHeight);
      this.x += itemWidth;
      return position;
    };
    m._getContainerSize = function () {
      return { height: this.maxY };
    };
    m.needsResizeLayout = window.Outlayer ? window.Outlayer.prototype.needsResizeLayout : m.needsResizeLayout;
  };

  proto._isInstant = function () {
    var instant = this._getOption('layoutInstant');
    return instant !== undefined ? instant : !this.masonry._isLayoutInited;
  };

  proto._filter = function (items) {
    var filter = this.options.filter || '*';
    var test = typeof filter === 'function' ?
      function (el) { return filter.call(el, 0, el); } : // jQuery .is(fn) signature
      function (el) { return matches(el, filter); };
    var out = { matches: [], needReveal: [], needHide: [] };
    items.forEach(function (item) {
      if (item.isIgnored) return;
      var isMatched = test(item.element);
      if (isMatched) out.matches.push(item);
      if (isMatched && item.isHidden) out.needReveal.push(item);
      else if (!isMatched && !item.isHidden) out.needHide.push(item);
    });
    return out;
  };

  /** Filter + layout. Masonry lays out whatever is in its items: the matching ones. */
  proto.arrange = function (opts) {
    var m = this.masonry;
    if (opts) this.option(opts);
    var instant = this._isInstant();
    var filtered = this._filter(this.items);
    this.filteredItems = filtered.matches;

    // arrangeComplete once layout, hide and reveal have all finished.
    var pending = 3;
    var self = this;
    function done() {
      if (--pending === 0) m.dispatchEvent('arrangeComplete', null, [self.filteredItems]);
    }
    once(m, 'layoutComplete', done);
    once(m, 'hideComplete', done);
    once(m, 'revealComplete', done);

    m.items = this.items; // hide()/reveal() look items up here
    var duration = m.options.transitionDuration;
    if (instant) m.options.transitionDuration = 0; // first arrange: no hide animation
    m.reveal(filtered.needReveal);
    m.hide(filtered.needHide);
    m.options.transitionDuration = duration;

    m.items = this.filteredItems;
    m.layout();
  };

  proto.layout = function () {
    if (!this.masonry._isLayoutInited && this._getOption('initLayout') !== false) {
      this.arrange();
      return;
    }
    this.masonry.items = this.filteredItems;
    this.masonry.layout();
  };

  proto.reloadItems = function () {
    var m = this.masonry;
    m.reloadItems();
    this.items = m.items;
    m.items = this.filteredItems; // as Isotope: filteredItems only change on arrange()
  };

  proto.appended = function (elems) {
    var m = this.masonry;
    m.items = this.items;
    var added = m.addItems(elems);
    this.items = m.items;
    var filtered = this._filter(added);
    m.hide(filtered.needHide);
    m.reveal(filtered.matches);
    m.layoutItems(filtered.matches, true);
    this.filteredItems = this.filteredItems.concat(filtered.matches);
    m.items = this.filteredItems;
  };

  proto.remove = function (elems) {
    var m = this.masonry;
    m.items = this.items;
    var gone = m.getItems(UI.toElements(elems));
    m.remove(elems);
    this.items = m.items;
    this.filteredItems = this.filteredItems.filter(function (item) { return gone.indexOf(item) === -1; });
    m.items = this.filteredItems;
  };

  proto.getItemElements = function () {
    return this.items.map(function (item) { return item.element; });
  };

  proto.getFilteredItemElements = function () {
    return this.filteredItems.map(function (item) { return item.element; });
  };

  proto.on = function (type, fn) { this.masonry.on(type, fn); return this; };
  proto.off = function (type, fn) { this.masonry.off(type, fn); return this; };
  proto.once = function (type, fn) { once(this.masonry, type, fn); return this; };

  proto.destroy = function () {
    var m = this.masonry;
    m.items = this.items;
    m.destroy();
    this.items.forEach(function (item) { item.element.style.display = ''; });
    delete this.element.__clIsotope;
    this.element.removeAttribute('data-cl-isotope');
  };

  /** $('.grid').isotope({...}), $grid.isotope({ filter: '.x' }), $grid.isotope('reloadItems'). */
  UI.isotope = function (target, options) {
    var args = slice.call(arguments, 2);
    if (!lib('Masonry')) return [];
    return run('isotope', target, function (el) {
      var iso = el.__clIsotope;
      if (typeof options === 'string') return callMethod(iso, options, args);
      if (iso) {
        iso.arrange(options); // a second call re-filters, as with jQuery
      } else {
        el.setAttribute('data-cl-isotope', '1');
        iso = el.__clIsotope = new Isotope(el, options);
      }
      return { instance: iso };
    });
  };
}());

/* ColorlibUI module: scrollup — replaces jQuery scrollUp 2.4.1. Appends the same
 * fixed <a id="scrollUp" href="#top"> (or adopts scrollTrigger), fades/slides it
 * in once the page is scrolled past scrollDistance and animates the window back
 * to the top on click, with the same inline styles jQuery left behind.
 * Option names are 2.4.1's: the themes also pass topDistance, topSpeed,
 * animationInSpeed and animationOutSpeed, which are scrollUp 1.x names that
 * 2.4.1 ignores — they are ignored here too, so the button still appears after
 * 300px (not 4500px), exactly as it does today. */
(function () {
  'use strict';
  var UI = window.ColorlibUI;
  if (!UI) return;

  var DEFAULTS = {
    scrollName: 'scrollUp', scrollDistance: 300, scrollFrom: 'top', scrollSpeed: 300,
    easingType: 'linear', animation: 'fade', animationSpeed: 200, scrollTrigger: false,
    scrollTarget: false, scrollText: 'Scroll to top', scrollTitle: false, scrollImg: false,
    activeOverlay: false, zIndex: 2147483647
  };
  var EASING = {
    swing: function (p) { return 0.5 - Math.cos(p * Math.PI) / 2; },
    linear: function (p) { return p; }
  };
  var SLIDE_PROPS = ['height', 'paddingTop', 'paddingBottom', 'marginTop', 'marginBottom'];
  var instance = null;

  function tween(ms, easing, step, done) {
    var ease = EASING[easing] || EASING.swing, start = null;
    step(0); // first frame state right away, so a fade-in never flashes at full opacity
    function frame(now) {
      if (start === null) start = now;
      var p = Math.min((now - start) / ms, 1);
      step(ease(p));
      if (p < 1) requestAnimationFrame(frame); else done();
    }
    requestAnimationFrame(frame);
  }

  /** The display jQuery's show() restores for an element with no display rule of its own. */
  function defaultDisplay(el) {
    var probe = document.createElement(el.nodeName);
    document.body.appendChild(probe);
    var d = getComputedStyle(probe).display;
    probe.parentNode.removeChild(probe);
    return d === 'none' ? 'block' : d;
  }

  /*
   * jQuery fadeIn/fadeOut, slideDown/slideUp and show/hide on one element,
   * queued like jQuery's fx queue: a fade-out requested mid fade-in waits for it.
   */
  function Fx(el) { this.el = el; this.queue = []; this.busy = false; }
  Fx.prototype.run = function (show, kind, ms) {
    this.queue.push([show, kind, ms]);
    if (!this.busy) this._next();
  };
  Fx.prototype._next = function () {
    var self = this, job = self.queue.shift();
    if (!job) { self.busy = false; return; }
    self.busy = true;
    var el = self.el, show = job[0], kind = job[1], ms = UI.reducedMotion ? 0 : job[2];
    var hidden = getComputedStyle(el).display === 'none';
    function next() { self._next(); }
    if (show === !hidden) { next(); return; }
    if (show) {
      el.style.display = '';
      if (getComputedStyle(el).display === 'none') el.style.display = defaultDisplay(el);
    }
    function end() {
      if (!show) el.style.display = 'none';
      next();
    }
    if (!ms || kind === 'toggle') { end(); return; }
    var cs = getComputedStyle(el);
    if (kind === 'fade') {
      var full = parseFloat(cs.opacity);
      tween(ms, 'swing', function (e) { el.style.opacity = String(full * (show ? e : 1 - e)); }, function () {
        el.style.opacity = '';
        end();
      });
      return;
    }
    var sizes = {};
    SLIDE_PROPS.forEach(function (p) { sizes[p] = parseFloat(cs[p]) || 0; });
    el.style.overflow = 'hidden';
    tween(ms, 'swing', function (e) {
      SLIDE_PROPS.forEach(function (p) { el.style[p] = sizes[p] * (show ? e : 1 - e) + 'px'; });
    }, function () {
      el.style.overflow = '';
      SLIDE_PROPS.forEach(function (p) { el.style[p] = ''; });
      end();
    });
  };

  function docHeight() {
    var b = document.body, d = document.documentElement;
    return Math.max(b.scrollHeight, d.scrollHeight, b.offsetHeight, d.offsetHeight, d.clientHeight);
  }

  /** $.scrollUp(options) → ColorlibUI.scrollUp(options). Only the first call on a page takes effect. */
  UI.scrollUp = function (options) {
    if (instance) return [instance];
    if (!document.body) {
      // Called from <head>: jQuery would fail too, but deferring is harmless.
      UI.ready(function () { UI.scrollUp(options); });
      return [];
    }
    var s = UI.extend({}, DEFAULTS, options);
    var el;
    if (s.scrollTrigger) {
      el = typeof s.scrollTrigger === 'string' && s.scrollTrigger.trim().charAt(0) === '<'
        ? UI.fromHTML(s.scrollTrigger) : UI.toElements(s.scrollTrigger)[0];
    }
    if (!el) {
      el = document.createElement('a');
      el.id = s.scrollName;
      el.href = '#top';
    }
    if (s.scrollTitle) el.setAttribute('title', s.scrollTitle);
    document.body.appendChild(el);
    if (!s.scrollImg && !s.scrollTrigger) el.innerHTML = s.scrollText;
    el.style.display = 'none';
    el.style.position = 'fixed';
    el.style.zIndex = String(s.zIndex);

    var overlay = null;
    if (s.activeOverlay) {
      overlay = document.createElement('div');
      overlay.id = s.scrollName + '-active';
      overlay.style.position = 'absolute';
      overlay.style.top = s.scrollDistance + 'px';
      overlay.style.width = '100%';
      overlay.style.borderTop = '1px dotted' + s.activeOverlay;
      overlay.style.zIndex = String(s.zIndex);
      document.body.appendChild(overlay);
    }

    var kind = s.animation === 'fade' ? 'fade' : s.animation === 'slide' ? 'slide' : 'toggle';
    var speed = kind === 'toggle' ? 0 : s.animationSpeed;
    // Both are measured once, at init, as in the original.
    var threshold = s.scrollFrom === 'top' ? s.scrollDistance
      : docHeight() - document.documentElement.clientHeight - s.scrollDistance;
    var dest = 0;
    if (typeof s.scrollTarget === 'number') dest = s.scrollTarget;
    else if (typeof s.scrollTarget === 'string') {
      var t = document.querySelector(s.scrollTarget);
      dest = t ? Math.floor(t.getBoundingClientRect().top + window.pageYOffset) : 0;
    }

    var fx = new Fx(el), shown = false;
    function onScroll() {
      if (window.pageYOffset > threshold) {
        if (!shown) { fx.run(true, kind, speed); shown = true; }
      } else if (shown) { fx.run(false, kind, speed); shown = false; }
    }
    window.addEventListener('scroll', onScroll);

    var scrolling = 0;
    function onClick(e) {
      e.preventDefault();
      var from = window.pageYOffset, id = ++scrolling;
      if (UI.reducedMotion || !s.scrollSpeed) { window.scrollTo(0, dest); return; }
      tween(s.scrollSpeed, s.easingType, function (p) {
        if (id === scrolling) window.scrollTo(0, from + (dest - from) * p);
      }, function () {});
    }
    el.addEventListener('click', onClick);

    instance = {
      element: el,
      settings: s,
      destroy: function () {
        window.removeEventListener('scroll', onScroll);
        el.removeEventListener('click', onClick);
        if (el.parentNode) el.parentNode.removeChild(el);
        if (overlay && overlay.parentNode) overlay.parentNode.removeChild(overlay);
        instance = null;
      }
    };
    return [instance];
  };
}());

/* ColorlibUI module: barfiller — replaces jquery.barfiller 1.0.1. The skill bars
 * (.barfiller > .tipWrap > .tip + .fill[data-percentage]) get the same inline
 * styles the plugin set: the fill's background colour, its width in px with a
 * `width Ns ease-in-out` transition, and the tooltip's `left`, so the theme CSS
 * renders identically. One change: the plugin filled every bar at page load,
 * so bars further down were already full when reached; here each bar fills when
 * it scrolls into view (IntersectionObserver), and instantly with reduced motion. */
(function () {
  'use strict';
  var UI = window.ColorlibUI;
  if (!UI) return;

  var DEFAULTS = { barColor: '#16b597', tooltip: true, duration: 1000, animateOnResize: true, symbol: '%' };

  /** jQuery's .width(): the content width, measured as a hidden block when display is none. */
  function contentWidth(el) {
    var cs = getComputedStyle(el);
    if (cs.display === 'none') {
      var saved = [el.style.display, el.style.visibility, el.style.position];
      el.style.display = 'block'; el.style.visibility = 'hidden'; el.style.position = 'absolute';
      var w0 = contentWidth(el);
      el.style.display = saved[0]; el.style.visibility = saved[1]; el.style.position = saved[2];
      return w0;
    }
    var w = parseFloat(cs.width) || 0;
    if (cs.boxSizing === 'border-box') {
      w -= (parseFloat(cs.paddingLeft) || 0) + (parseFloat(cs.paddingRight) || 0) +
        (parseFloat(cs.borderLeftWidth) || 0) + (parseFloat(cs.borderRightWidth) || 0);
    }
    return w;
  }

  function Bar(el, options) {
    var self = this;
    var s = self.settings = UI.extend({}, DEFAULTS);
    // Like $.extend, an undefined option (a bar without data-color) keeps the default.
    for (var k in options) if (options[k] !== undefined) s[k] = options[k];
    var fills = self.fills = Array.prototype.slice.call(el.querySelectorAll('.fill'));
    var tips = self.tips = Array.prototype.slice.call(el.querySelectorAll('.tip'));
    self.el = el;
    self.percentage = fills.length ? fills[0].getAttribute('data-percentage') : undefined;
    self.started = false;

    if (s.barColor != null) fills.forEach(function (f) { f.style.background = s.barColor; });
    if (!s.tooltip) tips.forEach(function (t) { t.style.display = 'none'; });
    tips.forEach(function (t) { t.textContent = self.percentage + s.symbol; });

    if (s.animateOnResize) {
      var timer;
      window.addEventListener('resize', function () {
        clearTimeout(timer);
        timer = setTimeout(function () { if (self.started) self.refill(); }, 300);
      });
    }

    function start() { self.started = true; self._fill(); }
    if (UI.reducedMotion || !('IntersectionObserver' in window)) { start(); return; }
    var io = new IntersectionObserver(function (entries) {
      if (!entries[0].isIntersecting) return;
      io.disconnect();
      start();
    });
    io.observe(el);
  }

  Bar.prototype._fill = function () {
    var s = this.settings;
    var width = contentWidth(this.el) * this.percentage * 0.01;
    Array.prototype.forEach.call(this.el.querySelectorAll('.tipWrap'), function (w) { w.style.display = 'inline'; });
    var tipWidth = this.tips.length ? contentWidth(this.tips[0]) : 0;
    var transition = UI.reducedMotion ? 'none' : (s.duration / 1000) + 's ease-in-out';
    // Width and transition are written together, as the plugin did, so the bar animates from 0.
    this.fills.forEach(function (f) {
      f.style.width = width + 'px';
      f.style.transition = transition === 'none' ? 'none' : 'width ' + transition;
    });
    this.tips.forEach(function (t) {
      t.style.left = (width - tipWidth) + 'px';
      t.style.transition = transition === 'none' ? 'none' : 'left ' + transition;
    });
  };

  Bar.prototype.refill = function () {
    this.fills.forEach(function (f) { f.style.width = '0px'; });
    this.tips.forEach(function (t) { t.style.left = '0px'; });
    this._fill();
  };

  /** $(bar).barfiller(options) → ColorlibUI.barfiller(bar, options). */
  UI.barfiller = function (target, options) {
    var out = [];
    UI.toElements(target).forEach(function (el) {
      if (!el._clBarfiller) {
        el._clBarfiller = new Bar(el, options);
        el.setAttribute('data-cl-barfiller', '1');
      }
      out.push(el._clBarfiller);
    });
    return out;
  };
}());

/* ColorlibUI module: ytbg — replaces jquery.youtubebackground 1.0.5 ($(el).YTPlayer()).
 * Appends the same markup to the element (div.ytplayer-container.background >
 * div.ytplayer-player, plus div.ytplayer-shield), sizes the player to cover the
 * window (or the element, with fitToBackground: false) and keeps it sized on
 * resize, loads the YouTube IFrame API once for all players, then mutes, plays
 * and loops the video and adds `loaded` to the element (fading out its <img>)
 * once it plays — as the plugin did. With prefers-reduced-motion the API is not
 * loaded at all: the element keeps its poster / background image. */
(function () {
  'use strict';
  var UI = window.ColorlibUI;
  if (!UI) return;

  var API = 'https://www.youtube.com/iframe_api';
  // The plugin read the window width once, when its script ran.
  var LOAD_WIDTH = document.documentElement.clientWidth;
  var DEFAULTS = {
    ratio: 16 / 9, videoId: 'LSmgKRx5pBo', mute: true, repeat: true, width: LOAD_WIDTH,
    playButtonClass: 'YTPlayer-play', pauseButtonClass: 'YTPlayer-pause', muteButtonClass: 'YTPlayer-mute',
    volumeUpClass: 'YTPlayer-volume-up', volumeDownClass: 'YTPlayer-volume-down', start: 0,
    pauseOnScroll: false, fitToBackground: true,
    playerVars: { iv_load_policy: 3, modestbranding: 1, autoplay: 1, controls: 0, showinfo: 0, wmode: 'opaque', branding: 0, autohide: 0 },
    events: null
  };
  var lastId = 0;
  var waiting = null; // callbacks queued until the API is ready

  /** Load the IFrame API once; run cb when YT.Player can be used. */
  function whenAPI(cb) {
    if (window.YT && window.YT.Player) { cb(); return; }
    if (waiting) { waiting.push(cb); return; }
    waiting = [cb];
    var previous = window.onYouTubeIframeAPIReady;
    // Chained, so another script waiting for the same API still gets its call.
    window.onYouTubeIframeAPIReady = function () {
      if (typeof previous === 'function') previous();
      var list = waiting;
      waiting = [];
      list.forEach(function (fn) { fn(); });
    };
    if (!document.querySelector('script[src*="youtube.com/iframe_api"]')) {
      var tag = document.createElement('script');
      tag.src = API;
      (document.head || document.documentElement).appendChild(tag);
    }
  }

  function merge(target, src) {
    for (var k in src) {
      if (!Object.prototype.hasOwnProperty.call(src, k) || src[k] === undefined) continue;
      var v = src[k];
      if (v && typeof v === 'object' && Object.getPrototypeOf(v) === Object.prototype) {
        target[k] = merge(target[k] && typeof target[k] === 'object' ? target[k] : {}, v);
      } else target[k] = v;
    }
    return target;
  }

  function contentBox(el) {
    var cs = getComputedStyle(el), w = parseFloat(cs.width) || 0, h = parseFloat(cs.height) || 0;
    if (cs.boxSizing === 'border-box') {
      w -= parseFloat(cs.paddingLeft) + parseFloat(cs.paddingRight) + parseFloat(cs.borderLeftWidth) + parseFloat(cs.borderRightWidth);
      h -= parseFloat(cs.paddingTop) + parseFloat(cs.paddingBottom) + parseFloat(cs.borderTopWidth) + parseFloat(cs.borderBottomWidth);
    }
    return [w, h];
  }

  function fadeOut(el, ms) {
    if (getComputedStyle(el).display === 'none') return;
    if (UI.reducedMotion) { el.style.display = 'none'; return; }
    var start = null, from = parseFloat(getComputedStyle(el).opacity);
    requestAnimationFrame(function frame(now) {
      if (start === null) start = now;
      var p = Math.min((now - start) / ms, 1);
      el.style.opacity = String(from * (0.5 + Math.cos(p * Math.PI) / 2));
      if (p < 1) { requestAnimationFrame(frame); return; }
      el.style.display = 'none';
      el.style.opacity = '';
    });
  }

  function Player(node, userOptions) {
    var self = this;
    self.node = node;
    self.player = null;
    var o = self.options = merge(merge({}, DEFAULTS), userOptions || {});
    o.height = Math.ceil(o.width / o.ratio);
    self.ID = lastId = Math.max(Date.now(), lastId + 1);
    self.holderID = 'YTPlayer-ID-' + self.ID;

    var container = document.createElement('div');
    container.id = 'ytplayer-container' + self.ID;
    var holder = document.createElement('div');
    holder.id = self.holderID;
    if (o.fitToBackground) {
      container.className = 'ytplayer-container background';
      holder.className = 'ytplayer-player';
    } else {
      holder.className = 'ytplayer-player-inline';
    }
    container.appendChild(holder);
    var shield = document.createElement('div');
    shield.id = 'ytplayer-shield';
    shield.className = 'ytplayer-shield';
    node.appendChild(container);
    node.appendChild(shield);
    self.parts = [container, shield];

    self._onResize = function () { self.resize(); };
    window.addEventListener('resize', self._onResize);
    // No autoplaying video for people who asked for less motion: the poster stays.
    if (!UI.reducedMotion) whenAPI(function () { self._create(); });
    self.resize();
  }

  Player.prototype._create = function () {
    var self = this, o = self.options;
    if (!document.getElementById(self.holderID)) return; // destroyed before the API arrived
    self.player = new window.YT.Player(self.holderID, {
      width: o.width, height: o.height, videoId: o.videoId, playerVars: o.playerVars,
      events: {
        onReady: function (e) {
          if (o.mute) e.target.mute();
          e.target.playVideo();
          if (o.pauseOnScroll) self._pauseOnScroll();
          if (typeof o.callback === 'function') o.callback.call(self);
        },
        onStateChange: function (e) {
          if (e.data === 1) {
            Array.prototype.forEach.call(self.node.querySelectorAll('img'), function (img) { fadeOut(img, 400); });
            self.node.classList.add('loaded');
          } else if (e.data === 0 && o.repeat) {
            self.player.seekTo(o.start);
          }
        }
      }
    });
  };

  Player.prototype._pauseOnScroll = function () {
    var self = this, timer;
    self._onScroll = function () {
      if (self.player && self.player.getPlayerState() === 1) self.player.pauseVideo();
      clearTimeout(timer);
      timer = setTimeout(function () {
        if (self.player && self.player.getPlayerState() === 2) self.player.playVideo();
      }, 250);
    };
    window.addEventListener('scroll', self._onScroll);
  };

  /** Cover the window (or the element): the player overflows one side and is centred. */
  Player.prototype.resize = function () {
    var o = this.options, w, h;
    if (o.fitToBackground) { w = document.documentElement.clientWidth; h = document.documentElement.clientHeight; }
    else { var box = contentBox(this.node); w = box[0]; h = box[1]; }
    var el = document.getElementById(this.holderID); // the iframe, once the API has swapped it in
    if (!el) return;
    if (w / o.ratio < h) {
      var pw = Math.ceil(h * o.ratio);
      el.style.width = pw + 'px'; el.style.height = h + 'px';
      el.style.left = (w - pw) / 2 + 'px'; el.style.top = '0px';
    } else {
      var ph = Math.ceil(w / o.ratio);
      el.style.width = w + 'px'; el.style.height = ph + 'px';
      el.style.left = '0px'; el.style.top = (h - ph) / 2 + 'px';
    }
  };

  Player.prototype.getPlayer = function () { return this.player; };

  Player.prototype.destroy = function () {
    var self = this;
    self.node.classList.remove('loaded');
    self.node.removeAttribute('data-cl-ytbg');
    self.node._clYTPlayer = null;
    self.parts.forEach(function (p) { if (p.parentNode) p.parentNode.removeChild(p); });
    window.removeEventListener('resize', self._onResize);
    if (self._onScroll) window.removeEventListener('scroll', self._onScroll);
    if (self.player && self.player.destroy) self.player.destroy();
    self.player = null;
  };

  /** $(el).YTPlayer(options) → ColorlibUI.youtubeBackground(el, options). */
  UI.youtubeBackground = function (target, options) {
    var out = [];
    UI.toElements(target).forEach(function (el) {
      if (!el._clYTPlayer) {
        el._clYTPlayer = new Player(el, options);
        el.setAttribute('data-cl-ytbg', '1');
      }
      out.push(el._clYTPlayer);
    });
    return out;
  };
}());

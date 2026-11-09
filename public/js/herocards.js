(function () {
  'use strict';

  var cards = [].slice.call(document.querySelectorAll('.hc'));
  if (!cards.length) return;

  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var fmt = function (n, d) {
    return n.toLocaleString('id-ID', { minimumFractionDigits: d, maximumFractionDigits: d });
  };
  var attrs = function (el) {
    return {
      to: parseFloat(el.getAttribute('data-count')),
      d: parseInt(el.getAttribute('data-dec') || '0', 10),
      s: el.getAttribute('data-suffix') || ''
    };
  };

  // angka berhitung naik dengan halus
  var count = function (card) {
    var el = card.querySelector('[data-count]');
    if (!el) return;
    var a = attrs(el);
    cancelAnimationFrame(el._raf);
    if (reduce || isNaN(a.to)) { el.textContent = fmt(a.to, a.d) + a.s; return; }
    var t0 = null;
    var step = function (ts) {
      if (t0 === null) t0 = ts;
      var p = Math.min((ts - t0) / 1500, 1);
      el.textContent = fmt(a.to * (1 - Math.pow(1 - p, 3)), a.d) + a.s;
      if (p < 1) el._raf = requestAnimationFrame(step);
    };
    el._raf = requestAnimationFrame(step);
  };

  // hitung naik dari 0 saat hero pertama kali terlihat
  var root = document.getElementById('h3d') || cards[0];
  if ('IntersectionObserver' in window && !reduce) {
    cards.forEach(function (c) {
      var el = c.querySelector('[data-count]');
      if (el) { var a = attrs(el); el.textContent = fmt(0, a.d) + a.s; }
    });
    var io = new IntersectionObserver(function (en) {
      if (!en[0].isIntersecting) return;
      io.disconnect();
      cards.forEach(count);
    }, { threshold: 0.25 });
    io.observe(root);
  }

  // klik/ketuk membuka detail dan mengulang hitungan; klik di luar atau Esc menutup
  var closeAll = function (except) {
    cards.forEach(function (c) {
      if (c === except) return;
      c.classList.remove('is-open');
      c.setAttribute('aria-expanded', 'false');
    });
  };
  cards.forEach(function (c) {
    c.addEventListener('click', function (e) {
      e.stopPropagation();
      var open = !c.classList.contains('is-open');
      closeAll(c);
      c.classList.toggle('is-open', open);
      c.setAttribute('aria-expanded', open ? 'true' : 'false');
      count(c);
    });
    // kilau cahaya mengikuti kursor
    c.addEventListener('pointermove', function (e) {
      var r = c.getBoundingClientRect();
      c.style.setProperty('--mx', ((e.clientX - r.left) / r.width * 100) + '%');
      c.style.setProperty('--my', ((e.clientY - r.top) / r.height * 100) + '%');
    });
  });
  document.addEventListener('click', function () { closeAll(null); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeAll(null); });
})();
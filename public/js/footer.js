(function () {
  'use strict';

  var footer = document.getElementById('siteFooter');
  if (!footer) return;

  var url = footer.getAttribute('data-url');
  var nodes = {};
  footer.querySelectorAll('[data-stat]').forEach(function (el) {
    nodes[el.getAttribute('data-stat')] = el;
  });
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var fmt = function (n) { return Number(n).toLocaleString('id-ID'); };

  // angka berhitung naik/turun dengan halus
  var setNum = function (el, to, animate) {
    var from = Number(el.getAttribute('data-value')) || 0;
    el.setAttribute('data-value', to);
    if (!animate || reduce || from === to) { el.textContent = fmt(to); return; }
    var t0 = null;
    var step = function (ts) {
      if (t0 === null) t0 = ts;
      var p = Math.min((ts - t0) / 800, 1);
      el.textContent = fmt(Math.round(from + (to - from) * (1 - Math.pow(1 - p, 3))));
      if (p < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  };

  // hitung naik dari 0 saat statistik pertama kali terlihat
  var list = footer.querySelector('.footer-stats ul');
  if (list && 'IntersectionObserver' in window) {
    var seen = new IntersectionObserver(function (entries) {
      if (!entries[0].isIntersecting) return;
      seen.disconnect();
      Object.keys(nodes).forEach(function (k) {
        var real = Number(nodes[k].getAttribute('data-value')) || 0;
        nodes[k].setAttribute('data-value', 0);
        setNum(nodes[k], real, true);
      });
    }, { threshold: 0.3 });
    seen.observe(list);
  }

  // perbarui angka tiap 30 detik (sekaligus menandai pengunjung masih online)
  var refresh = function () {
    if (document.hidden || !url) return;
    fetch(url, { headers: { 'Accept': 'application/json' }, cache: 'no-store' })
      .then(function (r) { return r.ok ? r.json() : null; })
      .then(function (d) {
        if (!d) return;
        Object.keys(nodes).forEach(function (k) { if (k in d) setNum(nodes[k], d[k], true); });
      })
      .catch(function () { /* abaikan, coba lagi di putaran berikutnya */ });
  };
  setInterval(refresh, 30000);
  document.addEventListener('visibilitychange', function () { if (!document.hidden) refresh(); });
})();

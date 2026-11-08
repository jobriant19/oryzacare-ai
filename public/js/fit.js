(function () {
  'use strict';

  // tinggi header dipakai CSS agar halaman pas satu layar (footer di bawah layar)
  var root = document.documentElement;
  var header = document.getElementById('siteHeader');
  if (!header) return;

  var set = function () { root.style.setProperty('--hh', header.offsetHeight + 'px'); };
  set();
  window.addEventListener('resize', set);
  window.addEventListener('load', set);
  if ('ResizeObserver' in window) new ResizeObserver(set).observe(header);
})();

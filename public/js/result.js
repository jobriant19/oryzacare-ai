(function () {
  'use strict';

  var root = document.documentElement;
  var header = document.getElementById('siteHeader');

  // tinggi header dipakai CSS agar halaman hasil pas satu layar
  var setHeader = function () {
    if (header) root.style.setProperty('--hh', header.offsetHeight + 'px');
  };
  setHeader();
  window.addEventListener('resize', setHeader);
  window.addEventListener('load', setHeader);
  if (header && 'ResizeObserver' in window) new ResizeObserver(setHeader).observe(header);

  // tab (dipakai di layar kecil)
  var tabs = document.querySelectorAll('.rs-tab');
  var panes = document.querySelectorAll('.rs-pane');
  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      var key = tab.getAttribute('data-tab');
      tabs.forEach(function (t) {
        var on = t === tab;
        t.classList.toggle('is-active', on);
        t.setAttribute('aria-selected', on ? 'true' : 'false');
      });
      panes.forEach(function (p) {
        p.classList.toggle('is-active', p.getAttribute('data-pane') === key);
      });
    });
  });
})();

(function () {
  'use strict';

  // Tinggi header -> dipakai CSS agar bagian ini pas satu layar
  var fit = function () {
    var h = document.querySelector('header');
    if (h) document.documentElement.style.setProperty('--hh', h.offsetHeight + 'px');
  };
  fit();
  window.addEventListener('resize', fit);
  window.addEventListener('load', fit);

  // Kartu muncul satu per satu saat bagian ini terlihat
  var sec = document.getElementById('jenis-penyakit');
  if (!sec || !('IntersectionObserver' in window)) return;

  sec.classList.add('jd-ready');
  var io = new IntersectionObserver(function (entries) {
    if (entries[0].isIntersecting) { sec.classList.add('in'); io.disconnect(); }
  }, { threshold: 0.25 });
  io.observe(sec);
})();
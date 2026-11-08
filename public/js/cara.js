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

  var card = document.getElementById('cara');
  if (!card) return;

  var tabs = [].slice.call(card.querySelectorAll('.cp-tab'));
  var panels = [].slice.call(card.querySelectorAll('.cp-panel'));
  var bar = card.querySelector('.cp-bar i');

  // lama tiap langkah = lama animasinya (atribut data-dur di blade, dalam detik)
  var DUR = panels.map(function (p) { return parseFloat(p.getAttribute('data-dur')) || 8; });

  var cur = 0;
  var offscreen = true;

  // semua animasi (garis waktu + panel) berjalan di CSS dan berjeda bersamaan, jadi selalu sinkron
  var sync = function () { card.classList.toggle('is-paused', offscreen || document.hidden); };

  var show = function (i) {
    var same = i === cur && panels[i].classList.contains('is-active');
    if (same) card.classList.add('cp-snap'); // klik tab yang sama: mulai ulang tanpa kedip
    cur = i;
    card.setAttribute('data-cur', String(i + 1));
    tabs.forEach(function (t, k) {
      var on = k === i;
      t.classList.toggle('is-active', on);
      t.setAttribute('aria-selected', on ? 'true' : 'false');
      t.tabIndex = on ? 0 : -1;
    });
    panels.forEach(function (p) { p.classList.remove('is-active'); });
    bar.classList.remove('run');
    void card.offsetWidth; // paksa animasi mulai dari awal
    bar.style.setProperty('--bd', DUR[i] + 's');
    panels[i].classList.add('is-active');
    bar.classList.add('run');
    if (same) {
      requestAnimationFrame(function () {
        requestAnimationFrame(function () { card.classList.remove('cp-snap'); });
      });
    }
  };

  // garis waktu selesai -> langkah berikutnya, dan setelah langkah 3 kembali ke langkah 1 (berputar terus)
  bar.addEventListener('animationend', function () { show((cur + 1) % tabs.length); });

  tabs.forEach(function (t, k) { t.addEventListener('click', function () { show(k); }); });
  card.addEventListener('keydown', function (e) {
    if (e.key !== 'ArrowRight' && e.key !== 'ArrowLeft') return;
    var n = (cur + (e.key === 'ArrowRight' ? 1 : tabs.length - 1)) % tabs.length;
    show(n);
    tabs[n].focus();
  });

  // dekode semua gambar lebih dulu agar pergantian langkah tidak tersendat
  [].forEach.call(card.querySelectorAll('img'), function (im) {
    if (im.decode) im.decode().catch(function () {});
  });

  document.addEventListener('visibilitychange', sync);

  // berjalan selama bagian ini terlihat di layar, berjeda hanya saat benar-benar di luar layar
  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (entries) {
      offscreen = !entries[0].isIntersecting;
      sync();
    }, { threshold: 0.1 }).observe(card);
  } else {
    offscreen = false;
  }

  // mulai langkah 1 (tetap berjeda sampai bagian ini terlihat)
  bar.style.setProperty('--bd', DUR[0] + 's');
  bar.classList.add('run');
  sync();
})();
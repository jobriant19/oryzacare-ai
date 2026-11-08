(function () {
  'use strict';

  var PER = 4; // kartu per halaman
  var root = document.documentElement;

  // Tinggi header -> dipakai CSS agar halaman pas satu layar
  var fit = function () {
    var h = document.querySelector('header');
    if (h) root.style.setProperty('--hh', h.offsetHeight + 'px');
  };
  fit();
  window.addEventListener('resize', fit);
  window.addEventListener('load', fit);

  // Foto hilang/rusak: tampilkan penanda
  document.addEventListener('error', function (e) {
    var t = e.target;
    if (t.tagName === 'IMG' && t.closest('.hp-thumb')) {
      t.hidden = true;
      t.parentNode.classList.add('is-broken');
    }
  }, true);

  // ----- Pencarian + halaman -----
  var grid = document.getElementById('hpGrid');
  if (!grid) return;

  var cards = [].slice.call(grid.children);
  var input = document.getElementById('hpSearch');
  var clear = document.getElementById('hpClear');
  var none = document.getElementById('hpNone');
  var noneText = document.getElementById('hpNoneText');
  var noneReset = document.getElementById('hpNoneReset');
  var pager = document.getElementById('hpPager');
  var prev = document.getElementById('hpPrev');
  var next = document.getElementById('hpNext');
  var dots = document.getElementById('hpDots');
  var count = document.getElementById('hpCount');
  var list = cards;
  var page = 1;
  // shortcut: halaman aktif tidak disimpan, setelah hapus kembali ke halaman 1; simpan di location.hash bila perlu

  var words = function (s) { return s.toLowerCase().split(/\s+/).filter(Boolean); };

  var render = function () {
    var pages = Math.max(1, Math.ceil(list.length / PER));
    if (page > pages) page = pages;

    cards.forEach(function (c) { c.hidden = true; });
    list.slice((page - 1) * PER, page * PER).forEach(function (c) { c.hidden = false; });

    var empty = list.length === 0;
    grid.hidden = empty;
    pager.hidden = empty;
    none.hidden = !empty;
    if (empty) noneText.textContent = 'Tidak ada riwayat yang cocok dengan “' + input.value.trim() + '”.';

    prev.disabled = page <= 1;
    next.disabled = page >= pages;
    count.textContent = 'Halaman ' + page + ' dari ' + pages + ' · ' + list.length + ' data';

    dots.textContent = '';
    for (var i = 1; i <= pages; i++) {
      var b = document.createElement('button');
      b.type = 'button';
      b.className = 'hp-dotbtn';
      b.setAttribute('aria-label', 'Halaman ' + i);
      if (i === page) b.setAttribute('aria-current', 'true');
      b.addEventListener('click', go.bind(null, i));
      dots.appendChild(b);
    }
  };

  var go = function (n) { page = n; render(); };

  var filter = function () {
    var q = words(input.value);
    clear.hidden = !input.value;
    list = cards.filter(function (c) {
      var hay = c.getAttribute('data-search') || '';
      return q.every(function (w) { return hay.indexOf(w) !== -1; });
    });
    page = 1;
    render();
  };

  var reset = function () { input.value = ''; filter(); input.focus(); };

  input.addEventListener('input', filter);
  input.addEventListener('keydown', function (e) { if (e.key === 'Escape' && input.value) reset(); });
  clear.addEventListener('click', reset);
  noneReset.addEventListener('click', reset);
  prev.addEventListener('click', function () { go(page - 1); });
  next.addEventListener('click', function () { go(page + 1); });

  filter();
})();
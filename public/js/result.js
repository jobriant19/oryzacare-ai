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

  // pop up foto: klik foto untuk memperbesar, tutup lewat tombol X, klik area luar, atau Esc
  var photo = document.querySelector('[data-zoom]');
  var lb = document.getElementById('rsLb');
  if (!photo || !lb) return;

  var img = lb.querySelector('.rs-lb-img');
  var stage = lb.querySelector('.rs-lb-stage');
  var label = lb.querySelector('.rs-lb-zoom');
  var MIN = 1, MAX = 6;
  var s = 1, x = 0, y = 0, moved = false, pinch = 0, pts = {};

  var apply = function () {
    img.style.transform = 'translate(' + x + 'px,' + y + 'px) scale(' + s + ')';
    label.textContent = Math.round(s * 100) + '%';
    stage.classList.toggle('is-zoomed', s > 1);
  };
  var setScale = function (n) {
    s = Math.min(MAX, Math.max(MIN, n));
    if (s === 1) { x = 0; y = 0; }
    apply();
  };
  var open = function () {
    img.src = photo.getAttribute('data-src');
    s = 1; x = 0; y = 0; apply();
    lb.hidden = false;
    document.body.style.overflow = 'hidden';
    lb.querySelector('.rs-lb-close').focus();
  };
  var close = function () {
    lb.hidden = true;
    document.body.style.overflow = '';
    photo.focus();
  };
  var dist = function () {
    var k = Object.keys(pts);
    return Math.hypot(pts[k[0]].x - pts[k[1]].x, pts[k[0]].y - pts[k[1]].y);
  };

  photo.addEventListener('click', open);
  photo.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(); }
  });
  lb.querySelector('.rs-lb-close').addEventListener('click', close);
  stage.addEventListener('click', function (e) {
    if (e.target !== img && !moved) close(); // klik area luar foto
  });
  document.addEventListener('keydown', function (e) {
    if (lb.hidden) return;
    if (e.key === 'Escape') close();
    else if (e.key === '+' || e.key === '=') setScale(s * 1.25);
    else if (e.key === '-') setScale(s / 1.25);
  });

  lb.querySelector('.rs-lb-tools').addEventListener('click', function (e) {
    var b = e.target.closest('button');
    if (!b) return;
    var act = b.getAttribute('data-act');
    if (act === 'in') setScale(s * 1.25);
    else if (act === 'out') setScale(s / 1.25);
    else setScale(1);
  });
  stage.addEventListener('wheel', function (e) {
    e.preventDefault();
    setScale(s * (e.deltaY < 0 ? 1.15 : 1 / 1.15));
  }, { passive: false });
  img.addEventListener('dblclick', function () { setScale(s > 1 ? 1 : 2.5); });

  // geser saat diperbesar, cubit dua jari di layar sentuh
  img.addEventListener('pointerdown', function (e) {
    img.setPointerCapture(e.pointerId);
    pts[e.pointerId] = { x: e.clientX, y: e.clientY };
    moved = false;
    pinch = Object.keys(pts).length === 2 ? dist() : 0;
  });
  img.addEventListener('pointermove', function (e) {
    var p = pts[e.pointerId];
    if (!p) return;
    var dx = e.clientX - p.x, dy = e.clientY - p.y;
    p.x = e.clientX; p.y = e.clientY;
    if (Object.keys(pts).length === 2) {
      var d = dist();
      if (pinch) setScale(s * d / pinch);
      pinch = d; moved = true;
    } else if (s > 1) {
      x += dx; y += dy; apply();
      if (Math.abs(dx) + Math.abs(dy) > 2) moved = true;
    }
  });
  var up = function (e) { delete pts[e.pointerId]; pinch = 0; };
  img.addEventListener('pointerup', up);
  img.addEventListener('pointercancel', up);
})();
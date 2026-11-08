(function () {
  'use strict';

  var header = document.getElementById('siteHeader');
  var nav = document.getElementById('mainNav');
  var toggle = document.getElementById('navToggle');
  if (!header || !nav) return;

  // ----- Menu mobile -----
  if (toggle) {
    toggle.addEventListener('click', function () {
      var open = header.classList.toggle('nav-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  // ----- Penanda aktif yang meluncur antar tab -----
  var pill = nav.querySelector('.nav-pill');
  var links = Array.prototype.slice.call(nav.querySelectorAll('a'));
  var current = nav.querySelector('a.active');
  var desktop = window.matchMedia('(min-width: 721px)');
  var KEY = 'oryzaNavPill';

  var box = function (a) {
    return { x: a.offsetLeft, y: a.offsetTop, w: a.offsetWidth, h: a.offsetHeight };
  };
  var remember = function (b) {
    try { sessionStorage.setItem(KEY, JSON.stringify(b)); } catch (e) { /* abaikan */ }
  };
  var place = function (b, animate) {
    if (!animate) pill.classList.add('no-anim');
    pill.style.width = b.w + 'px';
    pill.style.height = b.h + 'px';
    pill.style.transform = 'translate(' + b.x + 'px,' + b.y + 'px)';
    if (!animate) { void pill.offsetWidth; pill.classList.remove('no-anim'); }
  };
  var usable = function () { return !!(pill && current && desktop.matches); };

  // posisi awal: meluncur dari tab halaman sebelumnya ke tab halaman ini
  var start = function () {
    var on = usable();
    nav.classList.toggle('has-pill', on);
    if (!on) return;

    var from = null;
    try { from = JSON.parse(sessionStorage.getItem(KEY)); } catch (e) { from = null; }
    var to = box(current);

    if (from && typeof from.x === 'number') {
      place(from, false);
      requestAnimationFrame(function () {
        requestAnimationFrame(function () { place(to, true); });
      });
    } else {
      place(to, false);
    }
    remember(to);
  };

  // dipanggil saat ukuran layar berubah atau halaman dipulihkan dari cache
  var sync = function () {
    links.forEach(function (l) { l.classList.toggle('active', l === current); });
    var on = usable();
    nav.classList.toggle('has-pill', on);
    if (on) { place(box(current), false); remember(box(current)); }
  };

  // ----- Efek klik: riak, ikon membesar, lalu pindah halaman -----
  var ripple = function (a, e) {
    var r = a.getBoundingClientRect();
    var d = Math.max(r.width, r.height) * 2;
    var x = e.detail ? e.clientX - r.left : r.width / 2;
    var y = e.detail ? e.clientY - r.top : r.height / 2;
    var s = document.createElement('span');
    s.className = 'ripple';
    s.style.width = s.style.height = d + 'px';
    s.style.left = (x - d / 2) + 'px';
    s.style.top = (y - d / 2) + 'px';
    a.appendChild(s);
    setTimeout(function () { if (s.parentNode) s.parentNode.removeChild(s); }, 650);
  };

  nav.addEventListener('click', function (e) {
    var a = e.target.closest ? e.target.closest('a') : null;
    if (!a || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || a.target === '_blank') return;

    e.preventDefault();
    ripple(a, e);
    a.classList.remove('pop');
    void a.offsetWidth;
    a.classList.add('pop');

    if (nav.classList.contains('has-pill')) {
      links.forEach(function (l) { l.classList.toggle('active', l === a); });
      place(box(a), true);
      remember(box(a));
    }

    var href = a.href;
    setTimeout(function () { window.location.href = href; }, 90);
  });

  window.addEventListener('resize', sync);
  window.addEventListener('load', sync);
  window.addEventListener('pageshow', function (e) { if (e.persisted) sync(); });

  start();
})();

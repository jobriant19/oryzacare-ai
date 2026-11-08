(function () {
  'use strict';

  // ----- Muncul saat di-scroll -----
  var reveals = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); }
      });
    }, { threshold: 0.15 });
    reveals.forEach(function (el) { io.observe(el); });
  } else {
    reveals.forEach(function (el) { el.classList.add('in'); });
  }

  // ----- Cara kerja: tab interaktif + autoplay -----
  var flow = document.getElementById('flow');
  if (flow) {
    var tabs = flow.querySelectorAll('.flow-step');
    var panels = flow.querySelectorAll('.flow-panel');
    var bar = flow.querySelector('.flow-bar span');
    var cur = 0;
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    var restartBar = function () {
      bar.classList.remove('run');
      void bar.offsetWidth; // paksa animasi mulai dari awal
      if (!reduce) bar.classList.add('run');
    };
    var show = function (i) {
      cur = i;
      tabs.forEach(function (t, k) {
        var on = k === i;
        t.classList.toggle('active', on);
        t.setAttribute('aria-selected', on ? 'true' : 'false');
        t.tabIndex = on ? 0 : -1;
        panels[k].classList.toggle('active', on);
      });
      restartBar();
    };

    tabs.forEach(function (t, k) { t.addEventListener('click', function () { show(k); }); });
    bar.addEventListener('animationend', function () { show((cur + 1) % tabs.length); });
    flow.addEventListener('keydown', function (e) {
      if (e.key !== 'ArrowRight' && e.key !== 'ArrowLeft') return;
      var n = (cur + (e.key === 'ArrowRight' ? 1 : tabs.length - 1)) % tabs.length;
      show(n);
      tabs[n].focus();
    });

    // mulai berjalan setelah bagian ini terlihat di layar
    if ('IntersectionObserver' in window) {
      var starter = new IntersectionObserver(function (entries) {
        if (entries[0].isIntersecting) { restartBar(); starter.disconnect(); }
      }, { threshold: 0.3 });
      starter.observe(flow);
    } else {
      restartBar();
    }
  }
})();

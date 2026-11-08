(function () {
  'use strict';

  var root = document.documentElement;
  var confirmEl = document.getElementById('confirmModal');
  var successEl = document.getElementById('successModal');
  var yesBtn = document.getElementById('confirmYes');
  var pendingForm = null;
  var lastFocus = null;

  var open = function (el, focusSel) {
    lastFocus = document.activeElement;
    el.hidden = false;
    root.classList.add('modal-open');
    var f = el.querySelector(focusSel);
    if (f) f.focus();
  };
  var close = function (el) {
    if (!el || el.hidden || el.classList.contains('is-leaving')) return;
    el.classList.add('is-leaving');
    setTimeout(function () {
      el.hidden = true;
      el.classList.remove('is-leaving');
      root.classList.remove('modal-open');
      if (el === confirmEl) pendingForm = null;
      if (lastFocus && lastFocus.focus) lastFocus.focus();
    }, 180);
  };

  // ----- Konfirmasi hapus: form dengan atribut data-confirm-delete -----
  if (confirmEl) {
    document.addEventListener('submit', function (e) {
      var form = e.target;
      if (!form.matches || !form.matches('form[data-confirm-delete]')) return;
      e.preventDefault();
      pendingForm = form;
      yesBtn.disabled = false;
      yesBtn.textContent = 'Ya, Hapus';
      open(confirmEl, '.btn-light');
    });

    confirmEl.addEventListener('click', function (e) {
      if (e.target.closest && e.target.closest('[data-close]')) close(confirmEl);
    });

    yesBtn.addEventListener('click', function () {
      if (!pendingForm) return;
      yesBtn.disabled = true;
      yesBtn.textContent = 'Menghapus…';
      pendingForm.submit();
    });
  }

  // ----- Pop up berhasil (hijau): tutup sendiri setelah beberapa detik -----
  if (successEl) {
    root.classList.add('modal-open');
    successEl.addEventListener('click', function (e) {
      if (e.target.closest && e.target.closest('[data-close]')) close(successEl);
    });
    setTimeout(function () { close(successEl); }, Number(successEl.getAttribute('data-auto')) || 3500);
  }

  // ----- Keyboard: Esc menutup, Tab berputar di dalam pop up -----
  document.addEventListener('keydown', function (e) {
    var el = (confirmEl && !confirmEl.hidden && confirmEl) || (successEl && !successEl.hidden && successEl);
    if (!el) return;
    if (e.key === 'Escape') { close(el); return; }
    if (e.key !== 'Tab') return;
    var items = el.querySelectorAll('button:not([disabled])');
    if (!items.length) return;
    var first = items[0], last = items[items.length - 1];
    if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
    else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
  });

  // halaman dipulihkan dari cache browser: pastikan tidak ada pop up tertinggal
  window.addEventListener('pageshow', function (e) {
    if (e.persisted && confirmEl) { confirmEl.hidden = true; root.classList.remove('modal-open'); }
  });
})();

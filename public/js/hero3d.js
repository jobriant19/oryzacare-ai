/* Hero OryzaCare: model 3D (GLB) bergantian dengan ASCII padi.
   ASCII = render model 3D yang sama (kamera, putaran, animasi) diubah menjadi huruf ORYZA.
   Dimuat sebagai <script type="module"> (lihat importmap di home.blade.php). */
import * as THREE from 'three';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
import { RoomEnvironment } from 'three/addons/environments/RoomEnvironment.js';

const root = document.getElementById('h3d');
if (root) start(root);

function start(root) {
  const stage = root.querySelector('.h3d-stage');
  const layers = { '3d': root.querySelector('[data-layer="3d"]'), ascii: root.querySelector('[data-layer="ascii"]') };
  const canvas = root.querySelector('.h3d-canvas');
  const pre = root.querySelector('.h3d-pre');
  const status = root.querySelector('.h3d-status');
  const hint = root.querySelector('.h3d-hint');
  const tabs = root.querySelectorAll('[data-view]');
  const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // shortcut: ganti otomatis tiap 9 dtk, berhenti 15 dtk setelah pengguna berinteraksi; ubah angka di sini bila perlu
  const SWITCH_MS = reduce ? Infinity : 9000;
  const PAUSE_MS = 15000;
  const HINTS = { '3d': 'Seret untuk memutar model', ascii: 'Seret untuk memutar model' };

  let view = '3d', visible = true, ready3d = false, failed = false;
  let pauseUntil = 0, lastSwitch = performance.now(), lastAscii = 0;
  let base = null; // ukuran asli model GLB (setelah dimuat)

  /* ================= Pergantian tampilan ================= */
  function setView(v, manual) {
    view = v;
    lastSwitch = performance.now();
    if (manual) pauseUntil = lastSwitch + PAUSE_MS;
    Object.keys(layers).forEach(function (k) { layers[k].classList.toggle('is-active', k === v); });
    tabs.forEach(function (t) {
      var on = t.dataset.view === v;
      t.classList.toggle('is-active', on);
      t.setAttribute('aria-pressed', on ? 'true' : 'false');
    });
    hint.textContent = HINTS[v];
  }
  tabs.forEach(function (t) { t.addEventListener('click', function () { setView(t.dataset.view, true); }); });

  /* ================= 3D (GLB) ================= */
  const renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true, alpha: true });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
  renderer.outputColorSpace = THREE.SRGBColorSpace;
  renderer.toneMapping = THREE.ACESFilmicToneMapping;

  const scene = new THREE.Scene();
  scene.environment = new THREE.PMREMGenerator(renderer).fromScene(new RoomEnvironment(), 0.04).texture;
  scene.add(new THREE.HemisphereLight(0xffffff, 0x2a5a3a, 0.7));
  const key = new THREE.DirectionalLight(0xffffff, 2); key.position.set(3, 5, 4); scene.add(key);
  const rim = new THREE.DirectionalLight(0x86efac, 1.1); rim.position.set(-4, 2, -3); scene.add(rim);

  const camera = new THREE.PerspectiveCamera(35, 1, 0.1, 100);
  camera.position.set(0, 0.8, 5);

  // Kontrol dipasang di panggung (bukan kanvas) agar seretan juga memutar model saat tampilan ASCII aktif
  const controls = new OrbitControls(camera, stage);
  controls.enableDamping = true;
  controls.enableZoom = false;   // agar scroll halaman tidak terganggu
  controls.enablePan = false;
  controls.autoRotate = !reduce;
  controls.autoRotateSpeed = 2.4;
  controls.minPolarAngle = Math.PI * 0.28;
  controls.maxPolarAngle = Math.PI * 0.62;
  controls.addEventListener('start', function () { pauseUntil = performance.now() + PAUSE_MS; });
  stage.style.touchAction = 'pan-y'; // OrbitControls memaksa "none"; kembalikan agar HP tetap bisa scroll vertikal

  const holder = new THREE.Group();
  scene.add(holder);
  let mixer = null;

  // Skala model sebesar mungkin tapi tetap utuh saat berputar di panggung (aspek panggung ikut dihitung)
  function fitModel() {
    if (!base) return;
    const D = camera.position.length();
    const k = 0.96 * Math.tan(THREE.MathUtils.degToRad(camera.fov / 2)); // vertikal, margin 4% (model hanya berputar)
    const ka = k * camera.aspect;                                        // horizontal
    holder.scale.setScalar(Math.min(k * D / (base.hy + k * base.rh), ka * D / (base.rh * (1 + ka))));
  }

  new GLTFLoader().load(root.dataset.model, function (gltf) {
    const m = gltf.scene;
    const box = new THREE.Box3().setFromObject(m);
    const size = box.getSize(new THREE.Vector3());
    m.position.sub(box.getCenter(new THREE.Vector3()));
    // shortcut: jari-jari putar = sisi terlebar x 1,08 (bukan diagonal), model pipih jadi lebih besar; bila ujung daun terpotong saat berputar, naikkan 1.08 ke 1.25
    base = { hy: size.y / 2, rh: Math.max(size.x, size.z) / 2 * 1.08 };
    holder.add(m);
    if (gltf.animations && gltf.animations.length) {
      mixer = new THREE.AnimationMixer(m);
      gltf.animations.forEach(function (a) { mixer.clipAction(a).play(); });
    }
    fitModel();
    status.hidden = true;
    ready3d = true;
    lastSwitch = performance.now();
  }, undefined, function () {
    failed = true;
    status.textContent = 'Model 3D tidak dapat dimuat';
    root.querySelector('.h3d-tabs').hidden = true; // tanpa model, ASCII juga tidak ada
    hint.hidden = true;
  });

  /* ================= ASCII padi: render 3D yang sama -> huruf ORYZA =================
     Kanvas 3D diperkecil ke kanvas tersembunyi, tiap sel 4x8 px menjadi 1 huruf.
     Huruf HANYA dari "ORYZA" dan selalu berurutan menurut posisi sel (tidak berkedip saat model berputar);
     warna huruf mengikuti warna model (daun / bulir / pangkal / pipi). Mata (area gelap) dibiarkan kosong. */
  const COLS = 72, ROWS = 43, CW = 4, CH = 8; // 1 sel = 4x8 px (huruf monospace 1:2); 72x43 sel ~ rasio panggung 5:6
  const W = COLS * CW, H = ROWS * CH;
  const WORD = 'ORYZA';
  const off = document.createElement('canvas');
  off.width = W; off.height = H;
  const g = off.getContext('2d', { willReadFrequently: true });
  g.imageSmoothingQuality = 'high';
  const FONT = 'ui-monospace,Menlo,Consolas,"Courier New",monospace';
  pre.style.fontFamily = FONT;

  function drawAscii() { // harus dipanggil tepat setelah renderer.render (buffer WebGL belum dibersihkan)
    g.clearRect(0, 0, W, H);
    g.drawImage(canvas, 0, 0, W, H);
    pre.innerHTML = toHTML();
  }

  function toHTML() {
    const d = g.getImageData(0, 0, W, H).data;
    let out = '', run = '', cur = '';
    const flush = function () { if (run) { out += cur ? '<span class="' + cur + '">' + run + '</span>' : run; run = ''; } };
    for (let r = 0; r < ROWS; r++) {
      for (let c = 0; c < COLS; c++) {
        let a = 0, R = 0, G = 0, B = 0, dark = 0;
        for (let y = 0; y < CH; y++) {
          let i = ((r * CH + y) * W + c * CW) * 4;
          for (let x = 0; x < CW; x++, i += 4) {
            const al = d[i + 3];
            if (al) {
              a += al; R += d[i] * al; G += d[i + 1] * al; B += d[i + 2] * al;
              if (al > 128 && 0.3 * d[i] + 0.59 * d[i + 1] + 0.11 * d[i + 2] < 51) dark++;
            }
          }
        }
        const cov = a / (CW * CH * 255);
        if (cov < 0.22 || dark > CW * CH * 0.4) { run += ' '; continue; } // kosong / mata
        R /= a; G /= a; B /= a;
        const lum = (0.3 * R + 0.59 * G + 0.11 * B) / 255;
        let cls;
        if (B > G * 1.05) cls = 'o-p';
        else cls = 'o-' + (G > R * 1.02 ? 'l' : (G > 115 ? 'g' : 'b')) + (lum < 0.45 ? 1 : lum < 0.7 ? 2 : 3);
        if (cls !== cur) { flush(); cur = cls; }
        run += WORD[(c + r) % WORD.length];
      }
      flush();
      out += '\n';
    }
    return out;
  }

  // ukur lebar 1 huruf monospace, lalu pas-kan font agar grid ASCII persis menutup panggung
  const probe = document.createElement('span');
  probe.style.cssText = 'position:absolute;visibility:hidden;white-space:pre;font:100px ' + FONT;
  probe.textContent = 'M'.repeat(20);
  layers.ascii.appendChild(probe);
  const ratio = probe.getBoundingClientRect().width / 20 / 100;
  probe.remove();

  function fitAscii() {
    const w = stage.clientWidth;
    if (!w) return;
    const fs = w / (COLS * ratio);
    pre.style.fontSize = fs + 'px';
    pre.style.lineHeight = (2 * ratio * fs) + 'px';
  }

  /* ================= Latar siluet: mengikuti bentuk model (ikut berputar), lebih besar, + gelombang cahaya di sekelilingnya =================
     Render 3D diperkecil ke kanvas kecil, siluetnya digemukkan (digeser ke 16 arah), diwarnai, lalu di-blur lewat CSS. */
  const PAD = 0.12, IW = 150, IH = 180;                       // ukuran isi (px); kanvas efek lebih lebar PAD di tiap sisi
  const FW = Math.round(IW * (1 + 2 * PAD)), FH = Math.round(IH * (1 + 2 * PAD));
  const SPREAD = 6;                                           // makin besar, latar makin lebih besar dari model
  const FX = { '3d': ['rgba(255,255,255,.6)', 'rgba(255,255,255,.9)'],        // [latar, gelombang]
               ascii: ['rgba(8,16,30,.74)', 'rgba(251,191,36,.9)'] };         // ASCII: latar lebih gelap
  const sil = document.createElement('canvas'); sil.width = FW; sil.height = FH;
  const sg = sil.getContext('2d');
  function mkFx(cls) {
    const c = document.createElement('canvas');
    c.width = FW; c.height = FH; c.className = 'h3d-fx ' + cls; c.setAttribute('aria-hidden', 'true');
    stage.prepend(c);
    return { c: c, g: c.getContext('2d') };
  }
  const fxHalo = mkFx('h3d-fx--halo'), fxW1 = mkFx('h3d-fx--wave'), fxW2 = mkFx('h3d-fx--wave h3d-fx--wave2');
  function paint(g2, color) {
    g2.globalCompositeOperation = 'source-in'; g2.fillStyle = color; g2.fillRect(0, 0, FW, FH);
    g2.globalCompositeOperation = 'source-over';
  }
  function drawFx() { // dipanggil tepat setelah renderer.render
    sg.clearRect(0, 0, FW, FH);
    sg.drawImage(canvas, (FW - IW) / 2, (FH - IH) / 2, IW, IH);
    const h = fxHalo.g;
    h.globalCompositeOperation = 'source-over';
    h.clearRect(0, 0, FW, FH);
    for (let i = 0; i < 16; i++) {
      const a = i * Math.PI / 8;
      h.drawImage(sil, Math.cos(a) * SPREAD, Math.sin(a) * SPREAD);
    }
    h.drawImage(sil, 0, 0);
    paint(h, FX[view][0]);
    [fxW1, fxW2].forEach(function (w) {
      w.g.globalCompositeOperation = 'source-over';
      w.g.clearRect(0, 0, FW, FH);
      w.g.drawImage(fxHalo.c, 0, 0);
      paint(w.g, FX[view][1]);
    });
  }

  /* ================= Ukuran, visibilitas, loop ================= */
  new ResizeObserver(function () {
    const w = stage.clientWidth, h = stage.clientHeight;
    if (!w || !h) return;
    renderer.setSize(w, h, false);
    camera.aspect = w / h;
    camera.updateProjectionMatrix();
    fitModel();
    fitAscii();
  }).observe(stage);

  new IntersectionObserver(function (en) { visible = en[0].isIntersecting; }, { threshold: 0.1 }).observe(root);

  const clock = new THREE.Clock();
  let lastFx = 0;
  function frame(now) {
    requestAnimationFrame(frame);
    const dt = Math.min(clock.getDelta(), 0.1);
    if (!visible || document.hidden) return;

    if (!failed && ready3d && now > pauseUntil && now - lastSwitch > SWITCH_MS) {
      setView(view === '3d' ? 'ascii' : '3d');
    }

    if (mixer) mixer.update(dt);
    controls.update();
    renderer.render(scene, camera); // tetap dirender walau tampilan ASCII: itulah sumber hurufnya & siluet latar
    if (now - lastFx > 33) { lastFx = now; drawFx(); } // shortcut: ±30 fps; naikkan ke 66 bila HP lama terasa berat
    if (view === 'ascii' && now - lastAscii > 40) {    // shortcut: ±25 fps; naikkan jarak ke 66 bila HP lama terasa berat
      lastAscii = now;
      drawAscii();
    }
  }

  fitAscii();
  setView('3d');
  requestAnimationFrame(frame);
}
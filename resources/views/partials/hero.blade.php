<link rel="stylesheet" href="{{ asset('css/hero3d.css') }}">
<link rel="stylesheet" href="{{ asset('css/hero.css') }}?v=1">
<script type="importmap">
{
  "imports": {
    "three": "https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js",
    "three/addons/": "https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/"
  }
}
</script>
<script type="module" src="{{ asset('js/hero3d.js') }}"></script>
<script src="{{ asset('js/herocards.js') }}?v=1" defer></script>

@php
    $datasetTotal = (int) config('padi.dataset_total', 5932);
    $accuracy     = (float) config('padi.model_accuracy');
@endphp
<section class="hero">
    <div class="container hero-grid">
        <div class="hero-text">
            <h1><span>Kenali penyakit <em>daun <b class="hy">padi</b></em></span><span>hanya dari satu foto</span></h1>
            <p class="lead">Unggah foto daun padi, dapatkan diagnosis dan saran penanganan dalam hitungan detik.</p>
            <a href="{{ route('deteksi') }}" class="hero-cta">
                <span class="cta-bg" aria-hidden="true"><i class="cta-shine"></i></span>
                <span class="cta-ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg></span>
                <span>Mulai Deteksi</span>
                <span class="cta-arrow"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></span>
            </a>

        </div>

        <div class="h3d" id="h3d" data-model="{{ asset('base_basic_shaded.glb') }}" aria-label="Visual interaktif tanaman padi">
            <div class="h3d-frame">
                <div class="h3d-stage">
                    <div class="h3d-layer is-active" data-layer="3d">
                        <canvas class="h3d-canvas" aria-label="Model 3D interaktif, seret untuk memutar"></canvas>
                        <p class="h3d-status">Memuat model 3D…</p>
                    </div>
                    <div class="h3d-layer" data-layer="ascii" role="img" aria-label="Ilustrasi ASCII tanaman padi">
                        <pre class="h3d-pre" aria-hidden="true"></pre>
                    </div>
                </div>

                {{-- Tiga kartu mengelilingi model: atas-tengah, bawah-kiri, bawah-kanan --}}
                <div class="h3d-cards">
                    <button type="button" class="hc hc--acc" aria-expanded="false">
                        <span class="hc-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg></span>
                        <span class="hc-main">
                            <span class="hc-val" data-count="{{ $accuracy }}" data-dec="2" data-suffix="%">{{ number_format($accuracy, 2, ',', '.') }}%</span>
                            <span class="hc-lbl">Akurasi</span>
                        </span>
                        <span class="hc-more">Diuji pada 1.187 foto data uji</span>
                    </button>

                    <button type="button" class="hc hc--model" aria-expanded="false">
                        <span class="hc-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="18" r="3"/><circle cx="6" cy="6" r="3"/><circle cx="18" cy="6" r="3"/><path d="M18 9v2c0 .6-.4 1-1 1H7c-.6 0-1-.4-1-1V9"/><path d="M12 12v3"/></svg></span>
                        <span class="hc-main">
                            <span class="hc-val">Random Forest</span>
                            <span class="hc-lbl">Algoritma</span>
                        </span>
                        <span class="hc-more">100 pohon keputusan, fitur warna B-G-R</span>
                    </button>

                    <button type="button" class="hc hc--data" aria-expanded="false">
                        <span class="hc-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14a9 3 0 0 0 18 0V5"/><path d="M3 12a9 3 0 0 0 18 0"/></svg></span>
                        <span class="hc-main">
                            <span class="hc-val" data-count="{{ $datasetTotal }}" data-dec="0">{{ number_format($datasetTotal, 0, ',', '.') }}</span>
                            <span class="hc-lbl">Dataset</span>
                        </span>
                        <span class="hc-more">4.745 data latih + 1.187 data uji</span>
                    </button>
                </div>
            </div>

            <div class="h3d-tabs" role="group" aria-label="Pilih tampilan">
                <button type="button" class="is-active" data-view="3d" aria-pressed="true">Model 3D</button>
                <button type="button" data-view="ascii" aria-pressed="false">ASCII Padi</button>
            </div>
            <p class="h3d-hint" aria-live="polite">Seret untuk memutar model</p>
        </div>
    </div>
</section>
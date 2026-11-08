@extends('layouts.app')

@section('title', 'Beranda')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/history.css') }}?v=6">
    <link rel="stylesheet" href="{{ asset('css/recent.css') }}?v=1">
@endpush

@push('scripts')
    <script src="{{ asset('js/recent.js') }}?v=1"></script>
@endpush

@section('content')
<link rel="stylesheet" href="{{ asset('css/hero3d.css') }}">
<script type="importmap">
{
  "imports": {
    "three": "https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js",
    "three/addons/": "https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/"
  }
}
</script>
<script type="module" src="{{ asset('js/hero3d.js') }}"></script>

<section class="hero">
    <div class="container hero-grid">
        <div class="hero-text">
            <h1><span>Kenali penyakit <em>daun padi</em></span><span>hanya dari satu foto</span></h1>
            <p class="lead">Unggah foto daun padi, dapatkan diagnosis dan saran penanganan dalam hitungan detik.</p>
            <a href="{{ route('deteksi') }}" class="hero-cta">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                <span>Mulai Deteksi</span>
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

                <div class="h3d-cards">
                    <div class="h3d-card h3d-card--acc">
                        <span class="h3d-ico"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg></span>
                        <span><span class="h3d-val">{{ number_format(config('padi.model_accuracy'), 2, ',', '.') }}%</span><span class="h3d-lbl">Akurasi</span></span>
                    </div>
                    <div class="h3d-card h3d-card--model">
                        <span class="h3d-ico"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="18" r="3"/><circle cx="6" cy="6" r="3"/><circle cx="18" cy="6" r="3"/><path d="M18 9v2c0 .6-.4 1-1 1H7c-.6 0-1-.4-1-1V9"/><path d="M12 12v3"/></svg></span>
                        <span class="h3d-val">Random Forest</span>
                    </div>
                    <div class="h3d-card h3d-card--total">
                        <span class="h3d-ico"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg></span>
                        <span><span class="h3d-val">{{ $total }}</span><span class="h3d-lbl">Analisis</span></span>
                    </div>
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

<section class="section">
    <div class="container">
        <div class="section-intro reveal">
            <span class="eyebrow">Cara kerja</span>
            <h2 class="section-title">Tiga langkah menuju diagnosis</h2>
            <p>Dari foto sampai rekomendasi, semuanya otomatis. Pilih langkah untuk melihat detailnya.</p>
        </div>

        <div class="flow reveal" id="flow">
            <div class="flow-steps" role="tablist" aria-label="Langkah kerja">
                <button type="button" class="flow-step active" role="tab" id="flow-tab-0" aria-selected="true" aria-controls="flow-panel-0">
                    <span class="flow-ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg></span>
                    <span class="flow-txt"><span class="flow-no">Langkah 1</span><span class="flow-name">Unggah foto</span></span>
                </button>
                <button type="button" class="flow-step" role="tab" id="flow-tab-1" aria-selected="false" aria-controls="flow-panel-1" tabindex="-1">
                    <span class="flow-ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><line x1="7" y1="12" x2="17" y2="12"/></svg></span>
                    <span class="flow-txt"><span class="flow-no">Langkah 2</span><span class="flow-name">Analisis otomatis</span></span>
                </button>
                <button type="button" class="flow-step" role="tab" id="flow-tab-2" aria-selected="false" aria-controls="flow-panel-2" tabindex="-1">
                    <span class="flow-ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="8" height="4" x="8" y="2" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg></span>
                    <span class="flow-txt"><span class="flow-no">Langkah 3</span><span class="flow-name">Terima rekomendasi</span></span>
                </button>
            </div>

            <div class="flow-bar" aria-hidden="true"><span></span></div>

            <article class="flow-panel active" id="flow-panel-0" role="tabpanel" aria-labelledby="flow-tab-0">
                <div class="flow-visual">
                    <svg viewBox="0 0 200 160" fill="none" aria-hidden="true">
                        <rect x="20" y="30" width="160" height="110" rx="16" fill="#fff" stroke="#16a34a" stroke-width="3" stroke-dasharray="8 8"/>
                        <g class="anim-bounce"><path d="M100 112V62M78 82l22-22 22 22" stroke="#16a34a" stroke-width="7" stroke-linecap="round" stroke-linejoin="round"/></g>
                    </svg>
                </div>
                <div class="flow-info">
                    <h3>Unggah foto daun padi</h3>
                    <p>Pilih foto dari galeri atau ambil langsung dari kamera HP. Format JPG, PNG, dan WEBP hingga 8 MB diterima.</p>
                    <ul>
                        <li>Seret dan lepas foto ke area unggah</li>
                        <li>Pratinjau tampil sebelum dianalisis</li>
                        <li>Foto dekat, terang, dan berlatar polos memberi hasil terbaik</li>
                    </ul>
                </div>
            </article>

            <article class="flow-panel" id="flow-panel-1" role="tabpanel" aria-labelledby="flow-tab-1">
                <div class="flow-visual">
                    <svg viewBox="0 0 200 160" fill="none" aria-hidden="true">
                        <path d="M30 130C30 70 80 30 168 26C172 100 130 140 30 130Z" fill="#bbf7d0" stroke="#16a34a" stroke-width="4" stroke-linejoin="round"/>
                        <path d="M40 126C80 92 110 66 152 38" stroke="#16a34a" stroke-width="4" stroke-linecap="round"/>
                        <circle cx="104" cy="82" r="6" fill="#92400e" opacity=".75"/>
                        <circle cx="128" cy="62" r="4" fill="#92400e" opacity=".75"/>
                        <circle cx="84" cy="104" r="4" fill="#92400e" opacity=".75"/>
                        <rect class="anim-scan" x="24" y="20" width="152" height="4" rx="2" fill="#22c55e"/>
                    </svg>
                </div>
                <div class="flow-info">
                    <h3>Analisis otomatis</h3>
                    <p>Foto diperkecil menjadi 64×64 piksel, lalu rata-rata warna biru, hijau, dan merah dihitung. Model Random Forest yang terdiri dari 100 pohon keputusan menentukan penyakitnya.</p>
                    <ul>
                        <li>Pengolahan foto sama persis dengan saat model dilatih</li>
                        <li>Setiap pohon memberi suara, hasilnya berupa peluang tiap penyakit</li>
                        <li>Prosesnya hanya beberapa detik</li>
                    </ul>
                </div>
            </article>

            <article class="flow-panel" id="flow-panel-2" role="tabpanel" aria-labelledby="flow-tab-2">
                <div class="flow-visual">
                    <svg viewBox="0 0 200 160" fill="none" aria-hidden="true">
                        <circle cx="100" cy="80" r="44" stroke="#dcfce7" stroke-width="12"/>
                        <circle class="anim-ring" cx="100" cy="80" r="44" stroke="#16a34a" stroke-width="12" stroke-linecap="round"/>
                        <path d="M82 80l13 13 25-27" stroke="#16a34a" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="flow-info">
                    <h3>Terima rekomendasi</h3>
                    <p>Lihat penyakit yang terdeteksi, tingkat keyakinan, kondisi tanaman, dan langkah penanganan yang disarankan. Hasil otomatis tersimpan di riwayat.</p>
                    <ul>
                        <li>Tingkat keyakinan dalam persen</li>
                        <li>Tindakan rekomendasi dan ciri-ciri penyakit</li>
                        <li>Riwayat dapat dibuka kembali kapan saja</li>
                    </ul>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-intro reveal">
            <span class="eyebrow">Jenis penyakit</span>
            <h2 class="section-title">Penyakit yang dapat dideteksi</h2>
            <p>Empat penyakit daun padi yang dikenali oleh model.</p>
        </div>

        <div class="disease-grid">
            @foreach ($diseases as $key => $d)
                <div class="reveal" style="--i: {{ $loop->index }}">
                    <article class="dcard acc-{{ $d['tone'] }}">
                        <div class="dcard-media">
                            @if (file_exists(public_path('images/penyakit/'.$key.'.jpg')))
                                <img src="{{ asset('images/penyakit/'.$key.'.jpg') }}" alt="Contoh {{ $d['name'] }}" loading="lazy">
                            @else
                                <svg viewBox="0 0 24 24" width="56" height="56" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                            @endif
                            <span class="dcard-risk">Risiko {{ $d['risk'] }}</span>
                        </div>
                        <div class="dcard-body">
                            <h3>{{ $d['name'] }}</h3>
                            <p class="muted small italic">{{ $d['latin'] }}</p>
                            <p>{{ $d['summary'] }}</p>
                            <ul class="dcard-list">
                                @foreach (array_slice($d['symptoms'], 0, 2) as $symptom)
                                    <li>{{ $symptom }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>

@include('partials.recent', ['recent' => $recent, 'total' => $total])
@endsection

@extends('layouts.app')

@section('title', 'Beranda')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/history.css') }}?v=7">
    <link rel="stylesheet" href="{{ asset('css/recent.css') }}?v=1">
    <link rel="stylesheet" href="{{ asset('css/diseases.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('css/cara.css') }}?v=4">
@endpush

@push('scripts')
    <script src="{{ asset('js/recent.js') }}?v=1"></script>
    <script src="{{ asset('js/diseases.js') }}?v=1"></script>
    <script src="{{ asset('js/cara.js') }}?v=3"></script>
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

@include('partials.howto')
@include('partials.diseases', ['diseases' => $diseases])
@include('partials.recent', ['recent' => $recent, 'total' => $total])
@endsection

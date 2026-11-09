@extends('layouts.app')

@section('title', 'Padi Sehat')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/result.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('css/healthy.css') }}?v=1">
@endpush

@push('scripts')
    <script src="{{ asset('js/result.js') }}?v=2"></script>
@endpush

@section('content')
@php
    // ukuran warna foto (0..1) yang disimpan saat analisis
    $green  = number_format(min(max(($analysis->probabilities['green'] ?? 0) * 100, 0), 100), 1, ',', '.');
    $greenW = min(max(($analysis->probabilities['green'] ?? 0) * 100, 0), 100);
    $spot   = number_format(min(max(($analysis->probabilities['lesion'] ?? 0) * 100, 0), 100), 1, ',', '.');
    $spotW  = min(max(($analysis->probabilities['lesion'] ?? 0) * 100, 0), 100);
@endphp
<section class="container rs" data-tone="green">

    <div class="rs-top">
        <div class="rs-titlebox">
            <h1>Hasil Analisis</h1>
            <span class="rs-date">{{ $analysis->waktu }}</span>
        </div>
        <div class="rs-actions">
            <a href="{{ route('deteksi') }}" class="btn btn-primary" aria-label="Analisis foto lain">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><line x1="7" y1="12" x2="17" y2="12"/></svg>
                <span class="lbl">Analisis Lagi</span>
            </a>
            <a href="{{ route('riwayat') }}" class="btn btn-light" aria-label="Lihat riwayat">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg>
                <span class="lbl">Riwayat</span>
            </a>
            <form method="POST" action="{{ route('riwayat.hapus', $analysis) }}" data-confirm-delete>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" aria-label="Hapus riwayat ini">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                    <span class="lbl">Hapus</span>
                </button>
            </form>
        </div>
    </div>

    <div class="rs-grid">
        <figure class="rs-photo" style="--img: url('{{ $analysis->image_url }}')" data-zoom data-src="{{ $analysis->image_url }}" role="button" tabindex="0" aria-label="Perbesar foto yang dianalisis">
            <img src="{{ $analysis->image_url }}" alt="Foto daun padi yang dianalisis">
            <figcaption>Foto yang dianalisis</figcaption>
            <span class="rs-photo-zoom" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
            </span>
        </figure>

        <article class="rs-card rs-head">
            <div class="rs-headtext">
                <p class="rs-eyebrow">Hasil pemeriksaan</p>
                <h2 class="rs-name">Padi Sehat</h2>
                <p class="rs-latin">Tidak ditemukan gejala penyakit yang jelas pada foto ini.</p>
                <div class="rs-chips">
                    <span class="rs-chip">Kemungkinan sehat</span>
                    <span class="rs-chip alt">Tetap pantau berkala</span>
                </div>
            </div>
            <div class="rs-conf">
                <div class="hs-badge" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                </div>
                <div class="rs-level">
                    <small>Kondisi</small>
                    <strong class="lv-high-text">Sehat</strong>
                </div>
            </div>
        </article>

        <div class="rs-tabs" role="tablist" aria-label="Rincian hasil">
            <button type="button" class="rs-tab is-active" role="tab" aria-selected="true" data-tab="kondisi">Kondisi</button>
            <button type="button" class="rs-tab" role="tab" aria-selected="false" data-tab="tindakan">Tips</button>
            <button type="button" class="rs-tab" role="tab" aria-selected="false" data-tab="ciri">Waspada</button>
            <button type="button" class="rs-tab" role="tab" aria-selected="false" data-tab="peluang">Dasar</button>
        </div>

        <article class="rs-card rs-pane is-active" data-pane="kondisi">
            <h3>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Apa artinya
            </h3>
            <p>Daun pada foto ini didominasi warna hijau dan hampir tidak ada bercak atau perubahan warna. Tidak tampak tanda penyakit. Namun penilaian ini hanya berdasarkan warna foto, sehingga gejala yang sangat kecil atau baru muncul bisa terlewat.</p>
        </article>

        <article class="rs-card rs-pane" data-pane="peluang">
            <h3>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>
                Dasar penilaian
            </h3>
            <div class="rs-probs">
                <div class="rs-prob top">
                    <div class="rs-probhead"><span>Warna hijau daun</span><b>{{ $green }}%</b></div>
                    <div class="rs-track"><span style="width: {{ $greenW }}%"></span></div>
                </div>
                <div class="rs-prob warn">
                    <div class="rs-probhead"><span>Warna bercak</span><b>{{ $spot }}%</b></div>
                    <div class="rs-track"><span style="width: {{ max($spotW, 0.6) }}%"></span></div>
                </div>
            </div>
            <p class="hs-cap">Dihitung dari warna piksel foto (bercak = coklat, oranye, atau kuning tua), bukan dari model penyakit.</p>
        </article>

        <article class="rs-card rs-pane" data-pane="tindakan">
            <h3>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                Tips menjaga padi tetap sehat
            </h3>
            <ol class="rs-list rs-steps">
                <li>Pupuk secara berimbang (N, P, K) dan hindari urea berlebihan yang membuat tanaman rentan penyakit.</li>
                <li>Atur pengairan berselang dan jaga lahan tidak terlalu lembap.</li>
                <li>Pantau lahan secara rutin, misalnya seminggu sekali, terutama saat fase anakan dan keluar malai.</li>
                <li>Bersihkan gulma dan sisa jerami yang bisa menjadi tempat penyakit dan hama berkembang.</li>
                <li>Tanam serempak dengan benih atau varietas yang sehat agar penularan hama mudah diputus.</li>
            </ol>
        </article>

        <article class="rs-card rs-pane" data-pane="ciri">
            <h3>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                Tanda yang perlu diwaspadai
            </h3>
            <ul class="rs-list rs-ticks hs-warn">
                <li>Bercak belah ketupat berpusat abu-abu (blas).</li>
                <li>Bercak oval coklat dengan halo kuning (bercak coklat).</li>
                <li>Daun menguning hingga oranye dan tanaman kerdil (tungro).</li>
                <li>Ujung atau tepi daun mengering seperti terbakar (hawar daun bakteri).</li>
            </ul>
        </article>
    </div>

    <p class="rs-note">
        <span class="rs-note-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg></span>
        <b class="rs-note-tag">Catatan</b>
        <span>Hasil ini indikasi awal berdasarkan warna foto dan <strong>bukan jaminan padi bebas penyakit</strong>. Jika ada gejala di lapangan, foto ulang lebih dekat atau tanyakan ke penyuluh pertanian (PPL).</span>
    </p>
</section>

<div class="rs-lb" id="rsLb" role="dialog" aria-modal="true" aria-label="Foto diperbesar" hidden>
    <button type="button" class="rs-lb-close" aria-label="Tutup">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <div class="rs-lb-stage">
        <img class="rs-lb-img" src="" alt="Foto daun padi yang dianalisis" draggable="false">
    </div>
    <div class="rs-lb-tools">
        <button type="button" data-act="out" aria-label="Perkecil">&minus;</button>
        <span class="rs-lb-zoom">100%</span>
        <button type="button" data-act="in" aria-label="Perbesar">+</button>
        <button type="button" data-act="reset">Reset</button>
    </div>
</div>
@endsection
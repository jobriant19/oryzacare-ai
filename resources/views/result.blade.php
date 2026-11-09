@extends('layouts.app')

@section('title', 'Hasil Analisis')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/result.css') }}?v=2">
@endpush

@push('scripts')
    <script src="{{ asset('js/result.js') }}?v=2"></script>
@endpush

@section('content')
@php
    $info  = $analysis->info;
    $level = $analysis->level;
    $pct   = number_format($analysis->confidence, 1, ',', '.');
    $names = ['Bacterialblight' => 'Bacterialblight', 'Blast' => 'Blast', 'Brownspot' => 'Brown Spot', 'Tungro' => 'Tungro'];
    $top   = collect($probabilities)->keys()->first();
@endphp
<section class="container rs" data-tone="{{ $info['tone'] }}">

    <div class="rs-top">
        <div class="rs-titlebox">
            <h1>Hasil Analisis</h1>
            <span class="rs-date">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                {{ $analysis->waktu }}
            </span>
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
                <p class="rs-eyebrow">Penyakit terdeteksi</p>
                <h2 class="rs-name">{{ $names[$top] ?? $info['name'] }}</h2>
                <p class="rs-latin">{{ $info['latin'] }}</p>
                <div class="rs-chips">
                    <span class="rs-chip">Tingkat risiko: {{ $info['risk'] }}</span>
                    @if ($level['key'] === 'low')
                        <span class="rs-chip warn">Keyakinan rendah, hasil kurang pasti</span>
                    @endif
                </div>
            </div>
            <div class="rs-conf">
                <div class="rs-ring lv-{{ $level['key'] }}" style="--p: {{ $analysis->confidence }}"><span>{{ $pct }}%</span></div>
                <div class="rs-level">
                    <small>Tingkat keyakinan</small>
                    <strong class="lv-{{ $level['key'] }}-text">{{ $level['label'] }}</strong>
                </div>
            </div>
        </article>

        <div class="rs-tabs" role="tablist" aria-label="Rincian hasil">
            <button type="button" class="rs-tab is-active" role="tab" aria-selected="true" data-tab="kondisi">Kondisi</button>
            <button type="button" class="rs-tab" role="tab" aria-selected="false" data-tab="tindakan">Tindakan</button>
            <button type="button" class="rs-tab" role="tab" aria-selected="false" data-tab="ciri">Ciri</button>
            <button type="button" class="rs-tab" role="tab" aria-selected="false" data-tab="peluang">Peluang</button>
        </div>

        <article class="rs-card rs-pane is-active" data-pane="kondisi">
            <h3>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Kondisi tanaman
            </h3>
            <p>{{ $info['condition'] }}</p>
        </article>

        <article class="rs-card rs-pane" data-pane="peluang">
            <h3>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>
                Peluang tiap kelas
            </h3>
            <div class="rs-probs">
                @foreach ($probabilities as $label => $value)
                    @php $d = config('padi.diseases.'.$label); @endphp
                    <div class="rs-prob {{ $loop->first ? 'top' : '' }}">
                        <div class="rs-probhead">
                            <span>{{ $names[$label] ?? $d['name'] ?? $label }}</span>
                            <b>{{ number_format($value, 1, ',', '.') }}%</b>
                        </div>
                        <div class="rs-track"><span style="width: {{ max(min($value, 100), 0) }}%"></span></div>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="rs-card rs-pane" data-pane="tindakan">
            <h3>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="8" height="4" x="8" y="2" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
                Tindakan rekomendasi
            </h3>
            <ol class="rs-list rs-steps">
                @foreach ($info['actions'] as $action)
                    <li>{{ $action }}</li>
                @endforeach
            </ol>
            @if (! empty($info['prevention']))
                <div class="rs-prevent">
                    <span class="rs-prevent-ico">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                    </span>
                    <div>
                        <strong>Pencegahan</strong>
                        <p>{{ $info['prevention'] }}</p>
                    </div>
                </div>
            @endif
        </article>

        <article class="rs-card rs-pane" data-pane="ciri">
            <h3>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                Ciri-ciri penyakit
            </h3>
            <ul class="rs-list rs-ticks">
                @foreach ($info['symptoms'] as $symptom)
                    <li>{{ $symptom }}</li>
                @endforeach
            </ul>
        </article>
    </div>

    <p class="rs-note">
        <span class="rs-note-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg></span>
        <b class="rs-note-tag">Catatan</b>
        <span>Hasil ini indikasi awal dari model berbasis warna foto, <strong>bukan pengganti diagnosis ahli</strong>. Jenis dan takaran pestisida wajib mengikuti label produk dan anjuran PPL setempat.</span>
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
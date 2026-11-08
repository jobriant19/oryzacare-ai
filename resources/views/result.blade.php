@extends('layouts.app')

@section('title', 'Hasil Analisis')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/result.css') }}?v=1">
@endpush

@push('scripts')
    <script src="{{ asset('js/result.js') }}?v=1"></script>
@endpush

@section('content')
@php
    $info  = $analysis->info;
    $level = $analysis->level;
    $pct   = number_format($analysis->confidence, 1, ',', '.');
@endphp
<section class="container rs" data-tone="{{ $info['tone'] }}">

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
        <figure class="rs-photo" style="--img: url('{{ $analysis->image_url }}')">
            <img src="{{ $analysis->image_url }}" alt="Foto daun padi yang dianalisis">
            <figcaption>Foto yang dianalisis</figcaption>
        </figure>

        <article class="rs-card rs-head">
            <div class="rs-headtext">
                <p class="rs-eyebrow">Penyakit terdeteksi</p>
                <h2 class="rs-name">{{ $info['name'] }}</h2>
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
                            <span>{{ $d['name'] ?? $label }}</span>
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
                <p class="rs-prevent"><strong>Pencegahan:</strong> {{ $info['prevention'] }}</p>
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

    <p class="rs-note">Hasil ini indikasi awal dari model berbasis warna foto, bukan pengganti diagnosis ahli. Jenis dan takaran pestisida wajib mengikuti label produk dan anjuran PPL setempat.</p>
</section>
@endsection

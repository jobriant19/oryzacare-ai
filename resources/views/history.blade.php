@extends('layouts.app')

@section('title', 'Riwayat')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/history.css') }}?v=5">
@endpush

@push('scripts')
    <script src="{{ asset('js/history.js') }}?v=2"></script>
@endpush

@section('content')
@php
    // Nama singkat penyakit untuk filter
    $short = ['Bacterialblight' => 'Bacterialblight', 'Blast' => 'Blast', 'Brownspot' => 'Brown Spot', 'Tungro' => 'Tungro'];
@endphp
<section class="container hp">
    <div class="hp-head">
        <h1>Riwayat Analisis</h1>
    </div>

    <div class="hp-bar">
        <nav class="hp-chips" aria-label="Filter penyakit">
            <a href="{{ route('riwayat') }}" class="hp-chip acc-green {{ $filter ? '' : 'active' }}" @if (!$filter) aria-current="page" @endif>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                Semua <b>{{ $counts->sum() }}</b>
            </a>
            @foreach ($diseases as $key => $d)
                <a href="{{ route('riwayat', ['penyakit' => $key]) }}" class="hp-chip acc-{{ $d['tone'] }} {{ $filter === $key ? 'active' : '' }}" @if ($filter === $key) aria-current="page" @endif>
                    {{ $short[$key] ?? $d['name'] }} <b>{{ $counts[$key] ?? 0 }}</b>
                </a>
            @endforeach
            <a href="{{ route('riwayat', ['penyakit' => 'Healthy']) }}" class="hp-chip acc-green {{ $filter === 'Healthy' ? 'active' : '' }}" @if ($filter === 'Healthy') aria-current="page" @endif>
                Padi Sehat <b>{{ $counts['Healthy'] ?? 0 }}</b>
            </a>
        </nav>

        <div class="hp-tools">
            <div class="hp-search" role="search">
                <svg class="mag" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" id="hpSearch" placeholder="Cari penyakit, tanggal, atau persentase…" autocomplete="off" aria-label="Cari riwayat" @disabled($analyses->isEmpty())>
                <button type="button" class="hp-clear" id="hpClear" hidden aria-label="Hapus pencarian">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <a href="{{ route('deteksi') }}" class="btn btn-primary">+ Deteksi Baru</a>
        </div>
    </div>

    @if ($analyses->isEmpty())
        <div class="hp-empty">
            <img class="hp-empty-img" src="{{ asset('images/notfound.png') }}" alt="" onerror="this.remove()">
            <p><strong>Belum ada riwayat{{ $filter ? ' untuk filter ini' : '' }}.</strong></p>
            <p class="muted">Lakukan deteksi pertama Anda untuk melihat riwayat di sini.</p>
            <a href="{{ route('deteksi') }}" class="btn btn-primary">Mulai Deteksi</a>
        </div>
    @else
        <div class="hp-main">
            <div class="hp-grid" id="hpGrid">
                @foreach ($analyses as $a)
                    @include('partials.history-card', ['a' => $a, 'deletable' => true, 'hidden' => $loop->index >= 4])
                @endforeach
            </div>

            <div class="hp-empty" id="hpNone" hidden>
                <img class="hp-empty-img" src="{{ asset('images/notfound.png') }}" alt="" onerror="this.remove()">
                <p><strong id="hpNoneText"></strong></p>
                <p class="muted">Coba kata kunci lain, misalnya nama penyakit atau tanggal.</p>
                <button type="button" class="btn btn-light" id="hpNoneReset">Reset pencarian</button>
            </div>

            <nav class="hp-pager" id="hpPager" aria-label="Halaman riwayat">
                <button type="button" class="hp-pgbtn" id="hpPrev" aria-label="Halaman sebelumnya">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <div class="hp-dots" id="hpDots"></div>
                <button type="button" class="hp-pgbtn" id="hpNext" aria-label="Halaman berikutnya">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </button>
                <span class="hp-count" id="hpCount"></span>
            </nav>
        </div>

        {{-- Cadangan: tampil hanya bila controller masih memakai paginate() --}}
        @if (method_exists($analyses, 'hasPages') && $analyses->hasPages())
            {{ $analyses->links('pagination.padi') }}
        @endif
    @endif
</section>
@endsection
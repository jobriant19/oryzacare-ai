@php
    $healthy = $a->label === 'Healthy';
    $short   = ['Bacterialblight' => 'Bacterialblight', 'Blast' => 'Blast', 'Brownspot' => 'Brown Spot', 'Tungro' => 'Tungro'];
    $nama    = $short[$a->label] ?? $a->info['name'];
    $pct     = $healthy ? 100 : max(0, min(100, (float) $a->confidence));
    $lv      = $healthy ? 'high' : ($a->level['key'] ?? 'high');
    $lvLabel = $healthy ? 'Aman' : ($a->level['label'] ?? ucfirst($lv));
    [$tgl, $jam] = array_pad(explode(', ', (string) $a->waktu, 2), 2, null);
    $desc    = $a->info['summary'] ?? null;
    if (blank($desc)) {
        $desc = $healthy
            ? 'Daun didominasi warna hijau dan tidak tampak gejala penyakit yang jelas. Tetap pantau lahan secara berkala.'
            : config('padi.diseases.'.$a->label.'.summary');
    }
    $search  = mb_strtolower(trim($nama.' '.$a->info['name'].' '.$a->label.' '.$a->waktu.' '.$a->original_name.' '.number_format($a->confidence, 1, ',', '.').'%'.($healthy ? ' sehat' : '')));
@endphp
<article class="hp-card acc-{{ $a->info['tone'] }}" data-search="{{ $search }}" @if ($hidden ?? false) hidden @endif>
    <a href="{{ route('hasil', $a) }}" class="hp-thumb" aria-label="Buka hasil analisis" @if ($a->original_name) title="{{ $a->original_name }}" @endif>
        <img src="{{ $a->image_url }}" alt="Foto daun hasil analisis {{ $nama }}" loading="lazy">
    </a>

    <div class="hp-top">
        <h3 class="hp-name">{{ $nama }}</h3>
        <div class="hp-time">
            <span class="hp-pill hp-pill-date">
                <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                {{ $tgl }}
            </span>
            @if ($jam)
                <span class="hp-pill hp-pill-time">
                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    {{ $jam }}
                </span>
            @endif
        </div>
    </div>

    <div class="hp-conf conf-{{ $lv }}" style="--p: {{ $pct }}%">
        <div class="hp-conf-top">
            <span class="hp-conf-lbl">{{ $healthy ? 'Kondisi tanaman' : 'Tingkat keyakinan' }}</span>
            <span class="hp-conf-lv">{{ $lvLabel }}</span>
        </div>
        <div class="hp-conf-num">{{ $healthy ? 'Sehat' : number_format($a->confidence, 1, ',', '.').'%' }}</div>
        <div class="hp-conf-bar"><i></i></div>
    </div>

    <div class="hp-actions">
        <a href="{{ route('hasil', $a) }}" class="btn btn-open">
            <svg width="1.1em" height="1.1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.06 12.35a1 1 0 0 1 0-.7 10.75 10.75 0 0 1 19.88 0 1 1 0 0 1 0 .7 10.75 10.75 0 0 1-19.88 0"/><circle cx="12" cy="12" r="3"/></svg>
            Buka
        </a>
        @if ($deletable)
            <form method="POST" action="{{ route('riwayat.hapus', $a) }}" data-confirm-delete>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-delete">
                    <svg width="1.1em" height="1.1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                    Hapus
                </button>
            </form>
        @endif
    </div>

    <p class="hp-desc">{{ $desc }}</p>
</article>
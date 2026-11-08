<section class="rc {{ $recent->isEmpty() ? 'rc-solo' : '' }}" id="terbaru" aria-label="Analisis terbaru">
    <div class="container rc-in">
        @if ($recent->isNotEmpty())
            <div class="rc-head">
                <h2 class="rc-title">
                    <span class="rc-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg></span>
                    Analisis terbaru
                </h2>
                <a href="{{ route('riwayat') }}" class="rc-all">
                    Semua riwayat <b>{{ $total }}</b>
                    <span class="rc-arrow"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></span>
                </a>
            </div>

            <div class="hp-grid">
                @foreach ($recent->take(4) as $a)
                    @include('partials.history-card', ['a' => $a, 'deletable' => true])
                @endforeach
            </div>
        @endif

        <aside class="rc-note" role="note">
            <span class="rc-note-ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
            </span>
            <div class="rc-note-body">
                <span class="rc-note-tag">Catatan penting</span>
                <p>Sistem <b>hanya mengenali 4 penyakit</b> di atas. Daun sehat atau objek lain tetap akan digolongkan ke salah satu penyakit. Gunakan foto daun yang <b>dekat, terang, dan berlatar polos</b> untuk hasil terbaik.</p>
            </div>
        </aside>
    </div>
</section>
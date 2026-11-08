@inject('siteStats', 'App\Services\SiteStats')
@php
    $s   = $siteStats->summary();
    $fmt = fn ($n) => number_format($n, 0, ',', '.');
@endphp
<footer class="site-footer" id="siteFooter" data-url="{{ route('statistik') }}">
    <div class="container footer-grid">

        <section class="footer-about">
            <a href="{{ route('beranda') }}" class="footer-brand" aria-label="OryzaCare AI">
                <img src="{{ asset('images/logo.png') }}" alt="" class="footer-logo">
                <span>OryzaCare AI</span>
            </a>
            <p class="footer-desc">Deteksi penyakit daun padi dengan Random Forest. Unggah satu foto daun, lalu dapatkan jenis penyakit, tingkat keyakinan, kondisi tanaman, dan tindakan rekomendasi.</p>
            <p class="footer-note">
                <span class="fn-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg></span>
                <span class="fn-txt">
                    <b class="fn-tag">Catatan</b><br>
                    Hasil bersifat <strong>indikasi awal</strong>. Konfirmasi ke <strong>penyuluh pertanian (PPL)</strong> sebelum mengambil tindakan besar.
                </span>
            </p>
        </section>

        <nav class="footer-menu" aria-label="Tautan cepat">
            <h3>Tautan Cepat</h3>
            <ul>
                <li>
                    <a href="{{ route('deteksi') }}">
                        <span class="fm-ic" style="--c1:#22c55e;--c2:#15803d"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><line x1="7" y1="12" x2="17" y2="12"/></svg></span>
                        <span class="fm-t">Deteksi</span>
                        <svg class="fm-go" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ route('riwayat') }}">
                        <span class="fm-ic" style="--c1:#38bdf8;--c2:#2563eb"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg></span>
                        <span class="fm-t">Riwayat</span>
                        <svg class="fm-go" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ route('beranda') }}#cara-pakai">
                        <span class="fm-ic" style="--c1:#fbbf24;--c2:#d97706"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></span>
                        <span class="fm-t">Cara Pakai</span>
                        <svg class="fm-go" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ route('beranda') }}#jenis-penyakit">
                        <span class="fm-ic" style="--c1:#fb7185;--c2:#be123c"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m8 2 1.88 1.88"/><path d="M14.12 3.88 16 2"/><path d="M9 7.13v-1a3.003 3.003 0 1 1 6 0v1"/><path d="M12 20c-3.3 0-6-2.7-6-6v-3a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v3c0 3.3-2.7 6-6 6"/><path d="M12 20v-9"/><path d="M6.53 9C4.6 8.8 3 7.1 3 5"/><path d="M6 13H2"/><path d="M3 21c0-2.1 1.7-3.9 3.8-4"/><path d="M20.97 5c0 2.1-1.6 3.8-3.5 4"/><path d="M22 13h-4"/><path d="M17.2 17c2.1.1 3.8 1.9 3.8 4"/></svg></span>
                        <span class="fm-t">Jenis Penyakit</span>
                        <svg class="fm-go" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ route('beranda') }}#terbaru">
                        <span class="fm-ic" style="--c1:#a78bfa;--c2:#7c3aed"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="8" height="4" x="8" y="2" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg></span>
                        <span class="fm-t">Analisis Terbaru</span>
                        <svg class="fm-go" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="footer-farmer">
            <span class="farmer-shadow" aria-hidden="true"></span>
            <img src="{{ asset('images/farmer.png') }}" alt="Petani padi" loading="lazy" onerror="this.style.display='none'">
        </div>

        <aside class="footer-stats" aria-label="Statistik pengunjung">
            <h3>Statistik Pengunjung</h3>
            <ul>
                <li>
                    <span class="stat-ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></span>
                    <span class="stat-label">User online</span>
                    <strong data-stat="online" data-value="{{ $s['online'] }}">{{ $fmt($s['online']) }}</strong>
                </li>
                <li>
                    <span class="stat-ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                    <span class="stat-label">Visitor today</span>
                    <strong data-stat="visitor_today" data-value="{{ $s['visitor_today'] }}">{{ $fmt($s['visitor_today']) }}</strong>
                </li>
                <li>
                    <span class="stat-ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg></span>
                    <span class="stat-label">Total visitor</span>
                    <strong data-stat="visitor_total" data-value="{{ $s['visitor_total'] }}">{{ $fmt($s['visitor_total']) }}</strong>
                </li>
                <li>
                    <span class="stat-ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><line x1="7" y1="12" x2="17" y2="12"/></svg></span>
                    <span class="stat-label">Deteksi today</span>
                    <strong data-stat="detect_today" data-value="{{ $s['detect_today'] }}">{{ $fmt($s['detect_today']) }}</strong>
                </li>
                <li>
                    <span class="stat-ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg></span>
                    <span class="stat-label">Total deteksi</span>
                    <strong data-stat="detect_total" data-value="{{ $s['detect_total'] }}">{{ $fmt($s['detect_total']) }}</strong>
                </li>
            </ul>
        </aside>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <p class="footer-copy">
                <img src="{{ asset('images/logo.png') }}" alt="" class="fc-logo">
                <span>&copy; {{ date('Y') }} <b>OryzaCare AI</b>. Seluruh hak dilindungi.</span>
            </p>
        </div>
    </div>
</footer>
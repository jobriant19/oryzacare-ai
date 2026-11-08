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
            <p>Deteksi penyakit daun padi dengan Random Forest. Unggah satu foto daun, lalu dapatkan jenis penyakit, tingkat keyakinan, kondisi tanaman, dan tindakan rekomendasi.</p>
            <p class="footer-note">Hasil bersifat indikasi awal. Konfirmasi ke penyuluh pertanian (PPL) sebelum mengambil tindakan besar.</p>
            <nav class="footer-links" aria-label="Tautan cepat">
                <a href="{{ route('beranda') }}">Beranda</a>
                <a href="{{ route('deteksi') }}">Deteksi</a>
                <a href="{{ route('riwayat') }}">Riwayat</a>
            </nav>
        </section>

        <div class="footer-farmer">
            <img src="{{ asset('images/farmer.png') }}" alt="Petani padi" loading="lazy" onerror="this.style.display='none'">
        </div>

        <aside class="footer-stats" aria-label="Statistik pengunjung">
            <h3>Statistik Pengunjung</h3>
            <ul>
                <li>
                    <span class="stat-ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></span>
                    <span class="stat-label">User online <i class="live-dot" aria-hidden="true"></i></span>
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
            <p>&copy; {{ date('Y') }} OryzaCare AI. Seluruh hak dilindungi.</p>
        </div>
    </div>
</footer>

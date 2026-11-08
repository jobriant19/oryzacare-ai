<header class="site-header" id="siteHeader">
    <div class="container header-inner">
        <a href="{{ route('beranda') }}" class="brand" aria-label="OryzaCare AI">
            <img src="{{ asset('images/logo.png') }}" alt="" class="brand-logo">
            <span class="brand-name">OryzaCare AI</span>
        </a>

        <button type="button" class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="mainNav" aria-label="Buka menu">
            <span></span><span></span><span></span>
        </button>

        <nav class="main-nav" id="mainNav" aria-label="Menu utama">
            <span class="nav-pill" aria-hidden="true"></span>
            <a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                <span>Beranda</span>
            </a>
            <a href="{{ route('deteksi') }}" class="{{ request()->routeIs('deteksi') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><line x1="7" y1="12" x2="17" y2="12"/></svg>
                <span>Deteksi</span>
            </a>
            <a href="{{ route('riwayat') }}" class="{{ request()->routeIs('riwayat') || request()->routeIs('hasil') ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg>
                <span>Riwayat</span>
            </a>
        </nav>
    </div>
</header>

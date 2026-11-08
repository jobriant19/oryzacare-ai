{{-- Pop up konfirmasi hapus (merah) --}}
<div class="mdl" id="confirmModal" hidden>
    <div class="mdl-backdrop" data-close></div>
    <div class="mdl-box" role="alertdialog" aria-modal="true" aria-labelledby="confirmTitle" aria-describedby="confirmText">
        <div class="mdl-icon is-danger">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
        </div>
        <h3 id="confirmTitle">Hapus riwayat analisis?</h3>
        <p id="confirmText">Riwayat beserta fotonya akan dihapus permanen dan tidak dapat dikembalikan.</p>
        <div class="mdl-actions">
            <button type="button" class="btn btn-light" data-close>Batal</button>
            <button type="button" class="btn btn-danger-solid" id="confirmYes">Ya, Hapus</button>
        </div>
    </div>
</div>

{{-- Pop up berhasil (hijau), muncul setelah redirect dengan pesan status --}}
@if (session('status'))
    <div class="mdl" id="successModal" data-auto="3500">
        <div class="mdl-backdrop" data-close></div>
        <div class="mdl-box" role="alertdialog" aria-modal="true" aria-labelledby="successTitle" aria-describedby="successText">
            <div class="mdl-icon is-success">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h3 id="successTitle">Berhasil dihapus</h3>
            <p id="successText">{{ session('status') }}</p>
            <div class="mdl-actions">
                <button type="button" class="btn btn-primary" data-close>OK</button>
            </div>
            <div class="mdl-timer" aria-hidden="true"><span></span></div>
        </div>
    </div>
@endif

<?php

namespace App\Services;

use App\Models\Analysis;
use Illuminate\Support\Facades\DB;

class SiteStats
{
    /** Dianggap online jika terlihat dalam 5 menit terakhir. */
    private const ONLINE_MINUTES = 5;

    public function summary(): array
    {
        $today      = now('Asia/Jakarta')->toDateString();
        $startToday = now('Asia/Jakarta')->startOfDay()->utc();

        // shortcut: deteksi dihitung dari tabel analyses, angkanya turun bila riwayat dihapus; pakai kolom penghitung bila harus permanen
        return [
            'online'        => (int) DB::table('visits')->where('last_seen_at', '>=', now()->subMinutes(self::ONLINE_MINUTES)->toDateTimeString())->distinct()->count('visitor_key'),
            'visitor_today' => (int) DB::table('visits')->where('visit_date', $today)->count(),
            'visitor_total' => (int) DB::table('visits')->distinct()->count('visitor_key'),
            'detect_today'  => Analysis::where('created_at', '>=', $startToday->toDateTimeString())->count(),
            'detect_total'  => Analysis::count(),
        ];
    }
}

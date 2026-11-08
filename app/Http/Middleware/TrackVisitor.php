<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET')) {
            $key = $request->cookie('oc_vid');

            // Pengunjung anonim: hanya UUID acak di cookie, tanpa IP dan tanpa data pribadi.
            if (! is_string($key) || ! Str::isUuid($key)) {
                $key = (string) Str::uuid();
                Cookie::queue('oc_vid', $key, 60 * 24 * 365);
            }

            // shortcut: semua GET dihitung termasuk bot, saring user-agent bot bila angka harus akurat
            DB::table('visits')->upsert(
                [[
                    'visitor_key' => $key,
                    'visit_date'  => now('Asia/Jakarta')->toDateString(),
                    'last_seen_at' => now()->toDateTimeString(),
                ]],
                ['visitor_key', 'visit_date'],
                ['last_seen_at']
            );
        }

        return $next($request);
    }
}

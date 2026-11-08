<?php

namespace App\Http\Controllers;

use App\Services\SiteStats;

class StatsController extends Controller
{
    public function __invoke(SiteStats $stats)
    {
        return response()->json($stats->summary());
    }
}

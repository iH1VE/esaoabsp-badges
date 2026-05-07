<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Issuance;
use App\Models\Trail;

class DashboardController extends Controller
{
    public function index()
{
    $stats = [
        'badges_total' => Badge::count(),
        'issuances_total' => Issuance::count(),
        'issuances_issued' => Issuance::where('status', 'issued')->count(),
        'issuances_revoked' => Issuance::where('status', 'revoked')->count(),
        'trails_total' => Trail::count(),
        'issuances_this_month' => Issuance::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count(),
    ];

    $recentIssuances = Issuance::with('badge')
        ->latest()
        ->limit(8)
        ->get();

    $chartData = Issuance::selectRaw('MONTH(created_at) as month, count(*) as total')
        ->whereYear('created_at', now()->year)
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->toArray();

    $months = [];
    $totals = [];

    for ($i = 1; $i <= 12; $i++) {
        $months[] = date('M', mktime(0, 0, 0, $i, 1));
        $totals[] = $chartData[$i] ?? 0;
    }

    return view('admin.dashboard', [
        'stats' => $stats,
        'recentIssuances' => $recentIssuances,
        'chartMonths' => $months,
        'chartTotals' => $totals
    ]);
}
}

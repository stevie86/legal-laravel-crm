<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CounselingSession;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Basis-Statistiken basierend auf Berechtigungen
        $stats = [
            'total_clients' => $user->can('manage clients') ? Client::count() : 0,
            'total_sessions' => $user->can('manage sessions') ? CounselingSession::count() : 0,
            'sessions_this_month' => $user->can('manage sessions') ?
                CounselingSession::whereMonth('scheduled_at', now()->month)->count() : 0,
            'upcoming_sessions' => $user->can('manage sessions') ?
                CounselingSession::where('scheduled_at', '>', now())->count() : 0,
        ];

        // Detaillierte Daten nur für berechtigte Benutzer
        if ($user->can('manage clients')) {
            $stats['recent_clients'] = Client::latest()->take(5)->get();
        } else {
            $stats['recent_clients'] = collect();
        }

        if ($user->can('manage sessions')) {
            $stats['upcoming_sessions_list'] = CounselingSession::with('client')
                ->where('scheduled_at', '>', now())
                ->orderBy('scheduled_at')
                ->take(5)
                ->get();
        } else {
            $stats['upcoming_sessions_list'] = collect();
        }

        return view('dashboard', compact('stats'));
    }
}

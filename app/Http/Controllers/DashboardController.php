<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\CounselingSession;
use App\Models\CalendarEvent;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        // Statistiken
        $stats = [
            'total_clients' => Client::where('status', 'active')->count(),
            'sessions_today' => CounselingSession::whereDate('scheduled_at', $today)->count(),
            'sessions_this_week' => CounselingSession::whereBetween('scheduled_at', [$thisWeek, $thisWeek->copy()->endOfWeek()])->count(),
            'sessions_this_month' => CounselingSession::whereBetween('scheduled_at', [$thisMonth, $thisMonth->copy()->endOfMonth()])->count(),
        ];

        // Heutige Termine
        $todaysSessions = CounselingSession::with(['client', 'user'])
            ->whereDate('scheduled_at', $today)
            ->orderBy('scheduled_at')
            ->get();

        // Kommende Termine (nächste 7 Tage)
        $upcomingSessions = CounselingSession::with(['client', 'user'])
            ->whereBetween('scheduled_at', [Carbon::now(), Carbon::now()->addDays(7)])
            ->where('status', 'scheduled')
            ->orderBy('scheduled_at')
            ->limit(10)
            ->get();

        // Neueste Klienten
        $recentClients = Client::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Kalender-Events für heute
        $todaysEvents = CalendarEvent::with(['client', 'counselingSession'])
            ->whereDate('start_time', $today)
            ->orderBy('start_time')
            ->get();

        return view('dashboard', compact(
            'stats',
            'todaysSessions',
            'upcomingSessions',
            'recentClients',
            'todaysEvents'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = $request->get('month', Carbon::now()->format('Y-m'));
        $date = Carbon::createFromFormat('Y-m', $currentMonth)->startOfMonth();

        // Hole alle Events für den aktuellen Monat
        $events = CalendarEvent::with(['client', 'counselingSession'])
            ->whereBetween('start_time', [
                $date->copy()->startOfMonth(),
                $date->copy()->endOfMonth(),
            ])
            ->orderBy('start_time')
            ->get();

        // Gruppiere Events nach Datum
        $eventsByDate = $events->groupBy(function ($event) {
            return $event->start_time->format('Y-m-d');
        });

        // Erstelle Kalender-Grid
        $calendar = $this->generateCalendarGrid($date, $eventsByDate);

        return view('calendar.index', compact('calendar', 'date', 'events'));
    }

    private function generateCalendarGrid($date, $eventsByDate)
    {
        $calendar = [];
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // Starte mit dem ersten Montag vor oder am ersten Tag des Monats
        $current = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);

        // Erstelle 6 Wochen (42 Tage)
        for ($week = 0; $week < 6; $week++) {
            $calendar[$week] = [];
            for ($day = 0; $day < 7; $day++) {
                $dayEvents = $eventsByDate->get($current->format('Y-m-d'), collect());

                $calendar[$week][$day] = [
                    'date' => $current->copy(),
                    'isCurrentMonth' => $current->month === $date->month,
                    'isToday' => $current->isToday(),
                    'events' => $dayEvents,
                ];

                $current->addDay();
            }
        }

        return $calendar;
    }

    public function events(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $events = CalendarEvent::with(['client', 'counselingSession'])
            ->whereBetween('start_time', [$start, $end])
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_time->toISOString(),
                    'end' => $event->end_time->toISOString(),
                    'backgroundColor' => $event->color,
                    'borderColor' => $event->color,
                    'url' => $event->counseling_session_id
                        ? route('sessions.show', $event->counseling_session_id)
                        : null,
                ];
            });

        return response()->json($events);
    }
}

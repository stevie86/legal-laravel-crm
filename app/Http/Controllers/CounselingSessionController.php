<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Client;
use App\Models\CounselingSession;
use Illuminate\Http\Request;

class CounselingSessionController extends Controller
{
    public function index(Request $request)
    {
        $query = CounselingSession::with(['client', 'user']);

        // Filter nach Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter nach Klient
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // Filter nach Datum
        if ($request->filled('date_from')) {
            $query->whereDate('scheduled_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('scheduled_at', '<=', $request->date_to);
        }

        $sessions = $query->orderBy('scheduled_at', 'desc')->paginate(20);
        $clients = Client::where('status', 'active')->orderBy('last_name')->get();

        return view('sessions.index', compact('sessions', 'clients'));
    }

    public function create(Request $request)
    {
        $clients = Client::where('status', 'active')->orderBy('last_name')->get();
        $selectedClient = null;

        if ($request->filled('client_id')) {
            $selectedClient = Client::find($request->client_id);
        }

        return view('sessions.create', compact('clients', 'selectedClient'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'session_type' => 'required|in:individual,group,family,couple,assessment',
            'location' => 'nullable|string|max:255',
            'fee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:scheduled,completed,cancelled,no_show',
        ]);

        $validated['user_id'] = 1; // Temporär, später durch Auth::id() ersetzen

        $session = CounselingSession::create($validated);

        // Erstelle entsprechenden Kalender-Event
        CalendarEvent::create([
            'user_id' => $validated['user_id'],
            'client_id' => $session->client_id,
            'counseling_session_id' => $session->id,
            'title' => $session->title.' - '.$session->client->full_name,
            'description' => $session->description,
            'start_time' => $session->scheduled_at,
            'end_time' => $session->scheduled_at->copy()->addMinutes((int) $session->duration_minutes),
            'event_type' => 'session',
            'location' => $session->location,
            'color' => '#3788d8',
        ]);

        return redirect()->route('sessions.show', $session)
            ->with('success', 'Beratungssitzung wurde erfolgreich erstellt.');
    }

    public function show(CounselingSession $session)
    {
        $session->load(['client', 'user', 'sessionNotes', 'documents', 'calendarEvents']);

        return view('sessions.show', compact('session'));
    }

    public function edit(CounselingSession $session)
    {
        $clients = Client::where('status', 'active')->orderBy('last_name')->get();

        return view('sessions.edit', compact('session', 'clients'));
    }

    public function update(Request $request, CounselingSession $session)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'session_type' => 'required|in:individual,group,family,couple,assessment',
            'location' => 'nullable|string|max:255',
            'fee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:scheduled,completed,cancelled,no_show',
        ]);

        $session->update($validated);

        // Aktualisiere entsprechenden Kalender-Event
        $calendarEvent = $session->calendarEvents()->first();
        if ($calendarEvent) {
            $calendarEvent->update([
                'client_id' => $session->client_id,
                'title' => $session->title.' - '.$session->client->full_name,
                'description' => $session->description,
                'start_time' => $session->scheduled_at,
                'end_time' => $session->scheduled_at->copy()->addMinutes($session->duration_minutes),
                'location' => $session->location,
            ]);
        }

        return redirect()->route('sessions.show', $session)
            ->with('success', 'Beratungssitzung wurde erfolgreich aktualisiert.');
    }

    public function destroy(CounselingSession $session)
    {
        // Lösche entsprechende Kalender-Events
        $session->calendarEvents()->delete();

        $session->delete();

        return redirect()->route('sessions.index')
            ->with('success', 'Beratungssitzung wurde erfolgreich gelöscht.');
    }
}

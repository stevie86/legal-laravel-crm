<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\CounselingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_clients' => Client::count(),
            'total_sessions' => CounselingSession::count(),
            'sessions_this_month' => CounselingSession::whereMonth('scheduled_at', now()->month)->count(),
            'recent_users' => User::latest()->take(5)->get(),
            'user_roles' => User::selectRaw('role, COUNT(*) as count')->groupBy('role')->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,counselor,viewer',
            'is_active' => 'boolean',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.users')->with('success', 'Benutzer erfolgreich erstellt.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,counselor,viewer',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'Benutzer erfolgreich aktualisiert.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Sie können sich nicht selbst löschen.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Benutzer erfolgreich gelöscht.');
    }

    public function systemSettings()
    {
        return view('admin.settings');
    }

    public function reports()
    {
        $data = [
            'sessions_by_month' => CounselingSession::selectRaw("strftime('%m', scheduled_at) as month, COUNT(*) as count")
                ->whereRaw("strftime('%Y', scheduled_at) = ?", [now()->year])
                ->groupBy('month')
                ->get(),
            'sessions_by_status' => CounselingSession::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
            'clients_by_month' => Client::selectRaw("strftime('%m', created_at) as month, COUNT(*) as count")
                ->whereRaw("strftime('%Y', created_at) = ?", [now()->year])
                ->groupBy('month')
                ->get(),
        ];

        return view('admin.reports', compact('data'));
    }
}

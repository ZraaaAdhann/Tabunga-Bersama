<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectInvitationController extends Controller
{
    // API Pencarian User (Case-insensitive untuk PostgreSQL)
    public function searchUsers(Request $request)
    {
        $query = trim($request->query('q', ''));

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Gunakan ILIKE agar pencarian tidak terpengaruh huruf besar/kecil di PostgreSQL
        $users = User::where('id', '!=', Auth::id())
            ->where(function($q) use ($query) {
                $q->where('email', 'ILIKE', "%{$query}%")
                  ->orWhere('name', 'ILIKE', "%{$query}%")
                  ->orWhere('username', 'ILIKE', "%{$query}%");
            })
            ->select('id', 'name', 'username', 'email')
            ->limit(5)
            ->get();

        return response()->json($users);
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        $receiverId = $request->receiver_id;

        if ($project->members()->where('user_id', $receiverId)->exists()) {
            return back()->withErrors(['invite' => 'User tersebut sudah menjadi anggota project ini.']);
        }

        $existing = ProjectInvitation::where('project_id', $project->id)
            ->where('receiver_id', $receiverId)
            ->where('status', 'pending')
            ->exists();

        if ($existing) {
            return back()->withErrors(['invite' => 'Undangan sudah dikirim ke user ini sebelumnya.']);
        }

        ProjectInvitation::create([
            'project_id' => $project->id,
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Undangan bergabung berhasil dikirim!');
    }

    public function accept(ProjectInvitation $invitation)
{
    if ($invitation->receiver_id !== Auth::id()) {
        abort(403);
    }

    // Ubah dari ->attach() menjadi ->syncWithoutDetaching()
    $invitation->project->members()->syncWithoutDetaching([
        Auth::id() => ['role' => 'member']
    ]);

    $invitation->update(['status' => 'accepted']);

    return back()->with('success', 'Anda telah bergabung ke project.');
}

    public function decline(ProjectInvitation $invitation)
    {
        if ($invitation->receiver_id !== Auth::id()) {
            abort(403);
        }

        $invitation->update(['status' => 'declined']);

        return back()->with('success', 'Undangan ditolak.');
    }
}
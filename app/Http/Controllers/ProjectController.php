<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectInvitation;
use App\Models\SavingsDeposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil semua project yang diikuti oleh user
        $projects = $user->projects()->with(['members', 'deposits'])->get();

        // Hanya isi $currentProject jika terdapat query parameter ?project_id=...
        $activeProjectId = $request->query('project_id');
        $currentProject = $activeProjectId ? $projects->firstWhere('id', $activeProjectId) : null;

        $deposits = collect();
        $chartDates = [];
        $chartAmounts = [];
        $chartUserLabels = [];
        $chartUserAmounts = [];
        $activityLogs = collect();

        if ($currentProject) {
            // Data untuk Tampilan Detail Project
            $deposits = $currentProject->deposits()
                ->with('user')
                ->latest('deposit_date')
                ->latest('created_at')
                ->get();

            // Data Grafik Line (Tren Setoran 7 Hari Terakhir)
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $chartDates[] = $date->format('d M');
                $chartAmounts[] = $currentProject->deposits()
                    ->where('status', 'verified')
                    ->where('deposit_date', $date->toDateString())
                    ->sum('amount');
            }

            // Data Grafik Pie (Kontribusi Setoran per Anggota)
            foreach ($currentProject->members as $member) {
                $chartUserLabels[] = $member->name;
                $chartUserAmounts[] = $currentProject->deposits()
                    ->where('user_id', $member->id)
                    ->where('status', 'verified')
                    ->sum('amount');
            }

            // Log Aktivitas Project Terkait
            $recentDeposits = $currentProject->deposits()
                ->with('user')
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($d) {
                    return [
                        'type' => 'deposit',
                        'title' => $d->user->name . ' melakukan setoran sebesar Rp ' . number_format($d->amount, 0, ',', '.'),
                        'status' => $d->status,
                        'timestamp' => $d->created_at->diffForHumans(),
                        'created_at' => $d->created_at,
                    ];
                });

            $recentInvitations = ProjectInvitation::where('project_id', $currentProject->id)
                ->with(['sender', 'receiver'])
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($inv) {
                    return [
                        'type' => 'invitation',
                        'title' => $inv->sender->name . ' mengundang ' . $inv->receiver->name,
                        'status' => $inv->status,
                        'timestamp' => $inv->created_at->diffForHumans(),
                        'created_at' => $inv->created_at,
                    ];
                });

            $activityLogs = $recentDeposits->concat($recentInvitations)
                ->sortByDesc('created_at')
                ->take(8)
                ->values();
        } else {
            // Data Aktivitas Keseluruhan (Untuk Halaman Utama Dashboard)
            $recentDeposits = SavingsDeposit::whereIn('project_id', $projects->pluck('id'))
                ->with(['user', 'project'])
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($d) {
                    return [
                        'type' => 'deposit',
                        'title' => $d->user->name . ' setor Rp ' . number_format($d->amount, 0, ',', '.') . ' di "' . $d->project->title . '"',
                        'status' => $d->status,
                        'timestamp' => $d->created_at->diffForHumans(),
                        'created_at' => $d->created_at,
                    ];
                });

            $recentInvitations = ProjectInvitation::whereIn('project_id', $projects->pluck('id'))
                ->with(['sender', 'receiver', 'project'])
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($inv) {
                    return [
                        'type' => 'invitation',
                        'title' => $inv->sender->name . ' mengundang ' . $inv->receiver->name . ' ke "' . $inv->project->title . '"',
                        'status' => $inv->status,
                        'timestamp' => $inv->created_at->diffForHumans(),
                        'created_at' => $inv->created_at,
                    ];
                });

            $activityLogs = $recentDeposits->concat($recentInvitations)
                ->sortByDesc('created_at')
                ->take(8)
                ->values();
        }

        // Ambil daftar undangan project yang masuk untuk user
        $pendingInvitations = ProjectInvitation::with(['project', 'sender'])
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->get();

        return view('dashboard', compact(
            'projects',
            'currentProject',
            'deposits',
            'pendingInvitations',
            'chartDates',
            'chartAmounts',
            'chartUserLabels',
            'chartUserAmounts',
            'activityLogs'
        ));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'category' => 'required|string|max:100',
        'description' => 'nullable|string',
        'daily_target' => 'required|numeric|min:0|max:99999999999',
        'total_target' => 'required|numeric|min:0|max:99999999999',
        'bank_name' => 'required|string|max:50',
        'account_number' => 'required|string|max:50',
        'account_holder' => 'required|string|max:255',
        'qris_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ], [
        'daily_target.max' => 'Target harian tidak boleh melebihi Rp 99.999.999.999 (99,9 Miliar).',
        'total_target.max' => 'Total target tidak boleh melebihi Rp 99.999.999.999 (99,9 Miliar).',
    ]);

    if ($request->hasFile('qris_image')) {
        $validated['qris_image_path'] = $request->file('qris_image')->store('qris', 'public');
    }

    $validated['created_by'] = Auth::id();
    $validated['status'] = 'active';

    $project = Project::create($validated);
    $project->members()->attach(Auth::id(), ['role' => 'owner']);

    return redirect()->route('dashboard', ['project_id' => $project->id])
        ->with('success', 'Project tabungan baru berhasil dibuat.');
}

public function update(Request $request, Project $project)
{
    if ($project->created_by !== Auth::id()) {
        abort(403, 'Hanya pembuat project yang dapat memperbarui pengaturan.');
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'category' => 'required|string|max:100',
        'description' => 'nullable|string',
        'daily_target' => 'required|numeric|min:0|max:99999999999',
        'total_target' => 'required|numeric|min:0|max:99999999999',
        'bank_name' => 'required|string|max:50',
        'account_number' => 'required|string|max:50',
        'account_holder' => 'required|string|max:255',
        'qris_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ], [
        'daily_target.max' => 'Target harian tidak boleh melebihi Rp 99.999.999.999 (99,9 Miliar).',
        'total_target.max' => 'Total target tidak boleh melebihi Rp 99.999.999.999 (99,9 Miliar).',
    ]);

    if ($request->hasFile('qris_image')) {
        if ($project->qris_image_path) {
            Storage::disk('public')->delete($project->qris_image_path);
        }
        $validated['qris_image_path'] = $request->file('qris_image')->store('qris', 'public');
    }

    $project->update($validated);

    return redirect()->route('dashboard', ['project_id' => $project->id])
        ->with('success', 'Pengaturan project berhasil diperbarui.');
}

    public function complete(Project $project)
    {
        if ($project->created_by !== Auth::id()) {
            abort(403, 'Hanya pembuat project yang dapat menyelesaikan project.');
        }

        $project->update(['status' => 'completed']);

        return back()->with('success', 'Project telah ditandai selesai.');
    }

    public function destroy(Project $project)
    {
        if ($project->created_by !== Auth::id()) {
            abort(403, 'Hanya pembuat project yang dapat menghapus project.');
        }

        if ($project->qris_image_path) {
            Storage::disk('public')->delete($project->qris_image_path);
        }

        $project->delete();

        return redirect()->route('dashboard')->with('success', 'Project berhasil dihapus.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\SavingsDeposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SavingsDepositController extends Controller
{
    public function store(Request $request, Project $project)
    {
        // 1. Cek jika project sudah ditandai selesai
        if ($project->status === 'completed') {
            return back()->withErrors(['deposit' => 'Project ini telah ditandai selesai. Setoran baru tidak dapat ditambahkan.']);
        }

        // 2. Cek jika total akumulasi setoran terverifikasi sudah mencapai/melebihi target
        $totalCollected = $project->deposits()->where('status', 'verified')->sum('amount');
        if ($project->total_target > 0 && $totalCollected >= $project->total_target) {
            return back()->withErrors(['deposit' => 'Target tabungan project ini sudah tercapai. Setoran ditutup.']);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1000|max:99999999999',
            'proof_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string|max:255',
        ], [
            'amount.max' => 'Nominal setoran tidak boleh melebihi Rp 99.999.999.999 (99,9 Miliar).',
        ]);

        // Validasi batasan 1x setor per hari per project (hanya memblokir jika status pending atau verified)
        $hasDepositedToday = $project->deposits()
            ->where('user_id', Auth::id())
            ->where('deposit_date', Carbon::today()->toDateString())
            ->whereIn('status', ['pending', 'verified'])
            ->exists();

        if ($hasDepositedToday) {
            return back()->withErrors(['deposit' => 'Anda sudah melakukan setoran aktif untuk project ini hari ini.']);
        }

        // Convert gambar bukti setoran menjadi Base64 string agar aman di Vercel Serverless
        $file = $request->file('proof_image');
        $base64Image = base64_encode(file_get_contents($file->getRealPath()));
        $mimeType = $file->getClientMimeType();
        $proofPath = 'data:' . $mimeType . ';base64,' . $base64Image;

        SavingsDeposit::create([
            'project_id' => $project->id,
            'user_id' => Auth::id(),
            'deposit_date' => Carbon::today()->toDateString(),
            'amount' => $validated['amount'],
            'proof_image' => $proofPath,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Setoran berhasil dikirim dan menunggu verifikasi pemilik project.');
    }

    // Setujui Setoran (Owner Only)
    public function verify(SavingsDeposit $deposit)
    {
        if ($deposit->project->created_by !== Auth::id()) {
            abort(403, 'Hanya pembuat project yang dapat memverifikasi setoran.');
        }

        $deposit->update(['status' => 'verified']);

        return back()->with('success', 'Setoran berhasil diverifikasi.');
    }

    // Tolak Setoran (Owner Only)
    public function reject(SavingsDeposit $deposit)
    {
        if ($deposit->project->created_by !== Auth::id()) {
            abort(403, 'Hanya pembuat project yang dapat menolak setoran.');
        }

        $deposit->update(['status' => 'rejected']);

        return back()->with('success', 'Setoran telah ditolak.');
    }

    // Export Laporan Setoran ke CSV
    public function exportCsv(Project $project)
    {
        $fileName = 'Rekap_Tabungan_' . \Illuminate\Support\Str::slug($project->title) . '_' . date('Ymd_His') . '.csv';
        $deposits = $project->deposits()->with('user')->orderBy('deposit_date', 'desc')->get();

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($deposits) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID Setoran', 'Nama Anggota', 'Tanggal Setor', 'Nominal (Rp)', 'Status Verifikasi', 'Catatan']);

            foreach ($deposits as $deposit) {
                fputcsv($file, [
                    $deposit->id,
                    $deposit->user->name,
                    $deposit->deposit_date->format('d/m/Y'),
                    $deposit->amount,
                    ucfirst($deposit->status),
                    $deposit->notes ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
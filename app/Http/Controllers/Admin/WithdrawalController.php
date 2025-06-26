<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdrawal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    /**
     * Menampilkan daftar semua permintaan penarikan.
     */
    public function index()
    {
        $withdrawals = Withdrawal::with('user')->latest()->paginate(20);
        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    /**
     * Memproses permintaan penarikan (menyetujui atau menolak).
     */
    public function process(Request $request, Withdrawal $withdrawal)
    {
        $request->validate(['status' => 'required|in:processed,rejected']);

        // Pastikan hanya status 'pending' yang bisa diproses
        if ($withdrawal->status != 'pending') {
            return redirect()->back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        // Jika permintaan ditolak, kembalikan saldo ke nasabah
        if ($request->status == 'rejected') {
            try {
                DB::beginTransaction();

                // Update status permintaan menjadi 'rejected'
                $withdrawal->update(['status' => 'rejected']);

                // Kembalikan saldo yang sebelumnya telah dikurangi
                $user = $withdrawal->user;
                $user->increment('balance', $withdrawal->amount);

                DB::commit();
                return redirect()->route('admin.withdrawals.index')->with('success', 'Permintaan telah ditolak dan saldo telah dikembalikan ke nasabah.');

            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('admin.withdrawals.index')->with('error', 'Gagal memproses permintaan.');
            }
        }

        // Jika permintaan disetujui (processed)
        if ($request->status == 'processed') {
            $withdrawal->update([
                'status' => 'processed',
                'processed_at' => now() // Catat waktu diproses
            ]);
            return redirect()->route('admin.withdrawals.index')->with('success', 'Permintaan telah ditandai sebagai selesai.');
        }

        return redirect()->route('admin.withdrawals.index');
    }
}
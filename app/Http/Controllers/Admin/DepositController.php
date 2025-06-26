<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use App\Models\WasteDeposit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function index()
    {
        $pendingDeposits = WasteDeposit::where('status', 'pending_verification')
                                        ->with('user') // Ambil info nasabah
                                        ->latest()
                                        ->paginate(20);
        return view('admin.deposits.index', compact('pendingDeposits'));
    }

    public function approve(WasteDeposit $deposit)
    {
        // Pastikan hanya deposit yang pending yang bisa di-approve
        if ($deposit->status != 'pending_verification') {
            return redirect()->back()->with('error', 'Setoran ini sudah diproses sebelumnya.');
        }

        try {
            DB::beginTransaction();

            // 1. Update status setoran
            $deposit->status = 'completed';
            $deposit->admin_id = Auth::id(); // Catat siapa admin yang menyetujui
            $deposit->save();

            // 2. Tambahkan saldo ke nasabah (INI BAGIAN KRUSIALNYA)
            $nasabah = $deposit->user;
            $nasabah->increment('balance', $deposit->total_value);

            DB::commit();

            return redirect()->route('admin.deposits.index')->with('success', 'Setoran berhasil disetujui dan saldo nasabah telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.deposits.index')->with('error', 'Terjadi kesalahan.');
        }
    }
}
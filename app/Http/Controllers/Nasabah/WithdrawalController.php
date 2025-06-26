<?php

namespace App\Http\Controllers\Nasabah;

use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        
        // Ambil riwayat penarikan milik user, urutkan dari terbaru, dan gunakan paginasi
        $withdrawals = $user->withdrawals()->latest()->paginate(10);

        return view('nasabah.create', compact('withdrawals'));
    }

    /**
     * Menyimpan permintaan penarikan dana baru.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input: Ini adalah bagian paling PENTING untuk keamanan
        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:10000', // Minimal penarikan Rp 10.000
                'lte:' . $user->balance, // Tidak boleh lebih besar dari saldo yang dimiliki
            ],
            'destination_details' => 'required|string|max:500',
        ], [
            // Custom error messages
            'amount.lte' => 'Jumlah penarikan tidak boleh melebihi saldo Anda saat ini.',
            'amount.min' => 'Jumlah penarikan minimal adalah Rp 10.000.',
        ]);

        // 2. Proses Transaksi dengan DB Transaction untuk memastikan integritas data
        try {
            DB::beginTransaction();

            // Kurangi saldo user secara langsung (atomic operation)
            $user->decrement('balance', $validated['amount']);

            // Buat record penarikan baru dengan status 'pending'
            Withdrawal::create([
                'user_id' => $user->id,
                'amount' => $validated['amount'],
                'destination_details' => $validated['destination_details'],
                'status' => 'pending', // Admin akan mengubah status ini nanti
            ]);

            DB::commit(); // Jika semua berhasil, simpan perubahan

            return redirect()->route('nasabah.withdrawals.create')->with('success', 'Permintaan penarikan berhasil diajukan dan sedang menunggu persetujuan admin.');

        } catch (\Exception $e) {
            DB::rollBack(); // Jika ada error, batalkan semua operasi
            return redirect()->back()->with('error', 'Terjadi kesalahan. Permintaan penarikan gagal diajukan.');
        }
    }
}

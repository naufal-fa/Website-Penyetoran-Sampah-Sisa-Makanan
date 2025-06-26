<?php

namespace App\Http\Controllers\Nasabah;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        // --- MENGAMBIL DATA UNTUK KARTU RINGKASAN ---

        // 1. Menghitung total berat sampah yang pernah disetor
        $totalWeight = $user->wasteDeposits()->sum('weight_kg');

        // 2. Menghitung total pendapatan yang pernah diterima dari semua setoran
        $totalEarnings = $user->wasteDeposits()->sum('total_value');
        
        // 3. Menghitung total dana yang sudah berhasil ditarik (hanya yang statusnya 'processed')
        $totalWithdrawals = $user->withdrawals()->where('status', 'processed')->sum('amount');


        // --- MENGAMBIL DATA UNTUK RIWAYAT TRANSAKSI TERBARU ---

        // Ambil 5 setoran terakhir
        $latestDeposits = $user->wasteDeposits()->with('admin')->latest()->take(5)->get();

        // Ambil 5 penarikan terakhir
        $latestWithdrawals = $user->withdrawals()->latest()->take(5)->get();

        // Gabungkan kedua koleksi data menjadi satu
        $transactions = $latestDeposits->concat($latestWithdrawals);

        // Urutkan gabungan transaksi berdasarkan tanggal pembuatan (created_at) dari yang terbaru
        $sortedTransactions = $transactions->sortByDesc('created_at');


        // --- MENGIRIM SEMUA DATA KE VIEW ---
        
        return view('nasabah.dashboard', [
            'totalWeight'      => $totalWeight,
            'totalEarnings'    => $totalEarnings,
            'totalWithdrawals' => $totalWithdrawals,
            'transactions'     => $sortedTransactions,
        ]);
    }
}

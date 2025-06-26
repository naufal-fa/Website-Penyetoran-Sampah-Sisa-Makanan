<?php

namespace App\Http\Controllers\Admin;

use App\Models\Withdrawal;
use App\Models\WasteDeposit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;

class DashboardController extends Controller
{
        public function index()
    {
        // Ambil data untuk kartu statistik
        $totalNasabah = User::where('role', 'nasabah')->count();
        
        $pendingDepositsCount = WasteDeposit::where('status', 'pending_verification')->count();
        
        $pendingWithdrawalsCount = Withdrawal::where('status', 'pending')->count();
        
        $wasteCollectedToday = WasteDeposit::where('status', 'completed')
                                        ->whereDate('created_at', today())
                                        ->sum('weight_kg');

        // Ambil aktivitas terbaru untuk ditampilkan di log
        $latestActivities = WasteDeposit::with('user')
                                        ->latest()
                                        ->take(5) // Ambil 5 aktivitas deposit terakhir
                                        ->get();

        return view('admin.dashboard', compact(
            'totalNasabah',
            'pendingDepositsCount',
            'pendingWithdrawalsCount',
            'wasteCollectedToday',
            'latestActivities'
        ));
    }
}

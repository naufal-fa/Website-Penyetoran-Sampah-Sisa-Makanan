<?php

namespace App\Http\Controllers\Nasabah;

use App\Models\Setting;
use Illuminate\Support\Str;
use App\Models\WasteDeposit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LaporSetoranController extends Controller
{
    public function create()
    {
        return view('nasabah.lapor.create');
    }

    public function store(Request $request)
    {
        $request->validate(['weight_kg' => 'required|numeric|min:0.1']);

        // Ambil harga saat ini
        $hargaPerKg = Setting::where('setting_key', 'current_waste_price_per_kg')->firstOrFail()->setting_value;

        // Langsung hitung nilai totalnya
        $totalValue = $request->weight_kg * $hargaPerKg;

        $depositCode = 'DEP-' . date('ymd') . '-' . strtoupper(Str::random(5));

        WasteDeposit::create([
            'deposit_code'  => $depositCode,
            'user_id'       => Auth::id(),
            'status'        => 'pending_verification',
            'weight_kg'     => $request->weight_kg,
            'price_per_kg'  => $hargaPerKg,
            'total_value'   => $totalValue,
        ]);

        return redirect()->back()
                ->with('success', 'Laporan berhasil dibuat! Tunjukkan kode ini kepada petugas:')
                ->with('deposit_code', $depositCode);
    }
}

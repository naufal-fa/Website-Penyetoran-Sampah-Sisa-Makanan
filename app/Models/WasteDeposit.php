<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteDeposit extends Model {
    protected $fillable = [
        'user_id',
        'admin_id',
        'deposit_code',
        'status',
        'weight_kg',
        'price_per_kg',
        'total_value',
    ];

    // Relasi ke nasabah yang menyetor
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke admin yang memverifikasi
    public function admin() {
        return $this->belongsTo(User::class, 'admin_id');
    }
}

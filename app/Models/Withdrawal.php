<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'destination_details',
        'status',
        'processed_at',
    ];

    // Relasi ke nasabah yang menyetor
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}

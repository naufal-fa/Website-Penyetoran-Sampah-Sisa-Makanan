<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('waste_deposits', function (Blueprint $table) {
            // Tambahkan kolom untuk kode unik
            $table->string('deposit_code')->unique()->after('id');
            
            // Tambahkan kolom status untuk melacak proses
            $table->enum('status', ['pending_verification', 'completed', 'cancelled'])->default('pending_verification')->after('deposit_code');
            
            // Ubah kolom admin_id agar bisa NULL, karena saat dibuat nasabah, admin belum ada
            $table->foreignId('admin_id')->nullable()->change();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waste_deposits', function (Blueprint $table) {
            //
        });
    }
};

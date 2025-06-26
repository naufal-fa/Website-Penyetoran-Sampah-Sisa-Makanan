<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'setting_key' => 'current_waste_price_per_kg',
            'setting_value' => '1000' // Harga awal Rp 1.000 per kg
        ]);
    }
}

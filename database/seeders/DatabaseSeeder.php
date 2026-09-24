<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ReligionSeeder::class,
            ProgramStudiSeeder::class,
            ProvinceRegencySeeder::class, // Import data provinsi & kabupaten/kota
        ]);
    }
}

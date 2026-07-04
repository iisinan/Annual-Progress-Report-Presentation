<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Programme;

class ProgrammeSeeder extends Seeder
{
    public function run(): void
    {
        $programmes = [
            'MSc',
            'PhD'
        ];

        foreach ($programmes as $prog) {
            Programme::firstOrCreate(['name' => $prog]);
        }
    }
}

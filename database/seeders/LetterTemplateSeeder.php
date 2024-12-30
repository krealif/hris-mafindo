<?php

namespace Database\Seeders;

use App\Models\LetterTemplate;
use Illuminate\Database\Seeder;

class LetterTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LetterTemplate::create([
            'name' => 'Surat Test',
            'view' => 'surat-cuti',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            'Solo Raya', 'Jakarta', 'Semarang', 'Surabaya', 'Bandung',
            'Wonosobo', 'Yogyakarta', 'Purworejo', 'Magelang', 'Bogor',
            'Bekasi', 'Pontianak', 'Makassar', 'Maluku', 'Kendari',
            'Depok', 'Jombang', 'Mojokerto', 'Malang', 'Padang',
            'Banjarmasin', 'Bengkulu', 'Samarinda', 'Bangka Belitung', 'Lampung',
            'Bali', 'Mataram', 'Aceh', 'Manado', 'Sidoarjo',
            'Palu', 'Salatiga', 'Banten', 'Maumere', 'Garut',
            'Grobogan', 'Wonogiri', 'Sulawesi Barat', 'Kupang', 'Maluku Utara',
            'Sorong', 'Kalimantan Utara', 'Jayapura', 'Banyuwangi',
        ];

        $branches = array_map(fn ($branch) => ['name' => $branch], $branches);

        // Perform bulk insert
        Branch::insert($branches);
    }
}

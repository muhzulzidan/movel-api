<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KabupatenKota extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Tabel data Kabupaten Kota di Sulawesi Selatan
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Soppeng',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Takalar',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Tana Toraja',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Toraja Utara',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Wajo',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Makassar',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Palopo',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Parepare',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Luwu Timur',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Luwu Utara',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Maros',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Pangkep',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Pinrang',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Kep. Selayar',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Sidrap',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Sinjai',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Barru',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Bone',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Bulukumba',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Enrekang',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Gowa',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Jeneponto',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Luwu',
        ]);
        DB::table('m_kab_kota')->insert([
            'nama_kab_kota' => 'Bantaeng',
        ]);
    }
}

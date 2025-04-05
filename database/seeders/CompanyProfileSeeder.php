<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanyProfile::create([
            'nama' => 'DM Tirta Persada',
            'tagline' => 'HOTEL and RESORT',
            'alamat' => 'Kadubungbang, Kec. Cimanuk, Kabupaten Pandeglang, Banten 42271',
            'email' => '',
            'website' => '',
            'logo' => 'company-profile/logo.png',
            'facebook' => 'https://www.facebook.com/dm.tirtapersada?mibextid=ZbWKwL',
            'instagram' => 'https://www.instagram.com/dmtirtapersada/',
            'tiktok' => 'https://www.tiktok.com/@dm.tirta.persada?_t=8pg4ttr7g5p&_r=1',
            'linkedin' => '',
            'whatsapp1' => 'https://wa.me/081910900990',
            'whatsapp2' => 'https://wa.me/081910900990',
            'whatsapp3' => 'https://wa.me/081910900990',
            'ket' => '',
        ]);
    }
}

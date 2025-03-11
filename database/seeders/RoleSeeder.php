<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arr = [
            [
                'name' => 'sistem'
            ],
            [
                'name' => 'manajer',
                'salary' => '8000000'
            ],
            [
                'name' => 'admin',
                'salary' => '5000000'
            ],
            [
                'name' => 'admin gudang',
                'salary' => '5000000'
            ],
            [
                'name' => 'karyawan',
                'salary' => '5000000'
            ]
        ];
        
        foreach ($arr as $val) {
            Role::create(
                $val
            );
        }
    }
}

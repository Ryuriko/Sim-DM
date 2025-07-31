<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"Manajer","guard_name":"web","permissions":["view_absen::karyawan","view_any_absen::karyawan","create_absen::karyawan","update_absen::karyawan","restore_absen::karyawan","restore_any_absen::karyawan","replicate_absen::karyawan","reorder_absen::karyawan","delete_absen::karyawan","delete_any_absen::karyawan","force_delete_absen::karyawan","force_delete_any_absen::karyawan","view_absensi","view_any_absensi","create_absensi","update_absensi","restore_absensi","restore_any_absensi","replicate_absensi","reorder_absensi","delete_absensi","delete_any_absensi","force_delete_absensi","force_delete_any_absensi","view_barang","view_any_barang","create_barang","update_barang","restore_barang","restore_any_barang","replicate_barang","reorder_barang","delete_barang","delete_any_barang","force_delete_barang","force_delete_any_barang","view_company::profile","view_any_company::profile","create_company::profile","update_company::profile","restore_company::profile","restore_any_company::profile","replicate_company::profile","reorder_company::profile","delete_company::profile","delete_any_company::profile","force_delete_company::profile","force_delete_any_company::profile","view_cuti","view_any_cuti","create_cuti","update_cuti","restore_cuti","restore_any_cuti","replicate_cuti","reorder_cuti","delete_cuti","delete_any_cuti","force_delete_cuti","force_delete_any_cuti","view_cuti::karyawan","view_any_cuti::karyawan","create_cuti::karyawan","update_cuti::karyawan","restore_cuti::karyawan","restore_any_cuti::karyawan","replicate_cuti::karyawan","reorder_cuti::karyawan","delete_cuti::karyawan","delete_any_cuti::karyawan","force_delete_cuti::karyawan","force_delete_any_cuti::karyawan","view_fasilitas","view_any_fasilitas","create_fasilitas","update_fasilitas","restore_fasilitas","restore_any_fasilitas","replicate_fasilitas","reorder_fasilitas","delete_fasilitas","delete_any_fasilitas","force_delete_fasilitas","force_delete_any_fasilitas","view_gym::attendance","view_any_gym::attendance","create_gym::attendance","update_gym::attendance","restore_gym::attendance","restore_any_gym::attendance","replicate_gym::attendance","reorder_gym::attendance","delete_gym::attendance","delete_any_gym::attendance","force_delete_gym::attendance","force_delete_any_gym::attendance","view_gym::class","view_any_gym::class","create_gym::class","update_gym::class","restore_gym::class","restore_any_gym::class","replicate_gym::class","reorder_gym::class","delete_gym::class","delete_any_gym::class","force_delete_gym::class","force_delete_any_gym::class","view_gym::member","view_any_gym::member","create_gym::member","update_gym::member","restore_gym::member","restore_any_gym::member","replicate_gym::member","reorder_gym::member","delete_gym::member","delete_any_gym::member","force_delete_gym::member","force_delete_any_gym::member","view_gym::paket","view_any_gym::paket","create_gym::paket","update_gym::paket","restore_gym::paket","restore_any_gym::paket","replicate_gym::paket","reorder_gym::paket","delete_gym::paket","delete_any_gym::paket","force_delete_gym::paket","force_delete_any_gym::paket","view_gym::pelanggan","view_any_gym::pelanggan","create_gym::pelanggan","update_gym::pelanggan","restore_gym::pelanggan","restore_any_gym::pelanggan","replicate_gym::pelanggan","reorder_gym::pelanggan","delete_gym::pelanggan","delete_any_gym::pelanggan","force_delete_gym::pelanggan","force_delete_any_gym::pelanggan","view_gym::subscription","view_any_gym::subscription","create_gym::subscription","update_gym::subscription","restore_gym::subscription","restore_any_gym::subscription","replicate_gym::subscription","reorder_gym::subscription","delete_gym::subscription","delete_any_gym::subscription","force_delete_gym::subscription","force_delete_any_gym::subscription","view_gym::trainer","view_any_gym::trainer","create_gym::trainer","update_gym::trainer","restore_gym::trainer","restore_any_gym::trainer","replicate_gym::trainer","reorder_gym::trainer","delete_gym::trainer","delete_any_gym::trainer","force_delete_gym::trainer","force_delete_any_gym::trainer","view_history","view_any_history","create_history","update_history","restore_history","restore_any_history","replicate_history","reorder_history","delete_history","delete_any_history","force_delete_history","force_delete_any_history","view_kamar","view_any_kamar","create_kamar","update_kamar","restore_kamar","restore_any_kamar","replicate_kamar","reorder_kamar","delete_kamar","delete_any_kamar","force_delete_kamar","force_delete_any_kamar","view_parkir","view_any_parkir","create_parkir","update_parkir","restore_parkir","restore_any_parkir","replicate_parkir","reorder_parkir","delete_parkir","delete_any_parkir","force_delete_parkir","force_delete_any_parkir","view_parkir::pelanggan","view_any_parkir::pelanggan","create_parkir::pelanggan","update_parkir::pelanggan","restore_parkir::pelanggan","restore_any_parkir::pelanggan","replicate_parkir::pelanggan","reorder_parkir::pelanggan","delete_parkir::pelanggan","delete_any_parkir::pelanggan","force_delete_parkir::pelanggan","force_delete_any_parkir::pelanggan","view_penggajian","view_any_penggajian","create_penggajian","update_penggajian","restore_penggajian","restore_any_penggajian","replicate_penggajian","reorder_penggajian","delete_penggajian","delete_any_penggajian","force_delete_penggajian","force_delete_any_penggajian","view_pengguna","view_any_pengguna","create_pengguna","update_pengguna","restore_pengguna","restore_any_pengguna","replicate_pengguna","reorder_pengguna","delete_pengguna","delete_any_pengguna","force_delete_pengguna","force_delete_any_pengguna","view_qr::verification","view_any_qr::verification","create_qr::verification","update_qr::verification","restore_qr::verification","restore_any_qr::verification","replicate_qr::verification","reorder_qr::verification","delete_qr::verification","delete_any_qr::verification","force_delete_qr::verification","force_delete_any_qr::verification","view_reservasi","view_any_reservasi","create_reservasi","update_reservasi","restore_reservasi","restore_any_reservasi","replicate_reservasi","reorder_reservasi","delete_reservasi","delete_any_reservasi","force_delete_reservasi","force_delete_any_reservasi","view_reservasi::pelanggan","view_any_reservasi::pelanggan","create_reservasi::pelanggan","update_reservasi::pelanggan","restore_reservasi::pelanggan","restore_any_reservasi::pelanggan","replicate_reservasi::pelanggan","reorder_reservasi::pelanggan","delete_reservasi::pelanggan","delete_any_reservasi::pelanggan","force_delete_reservasi::pelanggan","force_delete_any_reservasi::pelanggan","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_supplier","view_any_supplier","create_supplier","update_supplier","restore_supplier","restore_any_supplier","replicate_supplier","reorder_supplier","delete_supplier","delete_any_supplier","force_delete_supplier","force_delete_any_supplier","view_ticket","view_any_ticket","create_ticket","update_ticket","restore_ticket","restore_any_ticket","replicate_ticket","reorder_ticket","delete_ticket","delete_any_ticket","force_delete_ticket","force_delete_any_ticket","view_ticket::waterboom","view_any_ticket::waterboom","create_ticket::waterboom","update_ticket::waterboom","restore_ticket::waterboom","restore_any_ticket::waterboom","replicate_ticket::waterboom","reorder_ticket::waterboom","delete_ticket::waterboom","delete_any_ticket::waterboom","force_delete_ticket::waterboom","force_delete_any_ticket::waterboom","view_tipe","view_any_tipe","create_tipe","update_tipe","restore_tipe","restore_any_tipe","replicate_tipe","reorder_tipe","delete_tipe","delete_any_tipe","force_delete_tipe","force_delete_any_tipe","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}

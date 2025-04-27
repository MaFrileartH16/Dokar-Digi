<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
  public function run(): void
  {
    $this->call([
      RoleSeeder::class,
      PermissionSeeder::class,
    ]);

    // Reset cached roles & permissions
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    // Ambil role yang sudah ada
    $admin = Role::where('name', 'Admin')->firstOrFail();
    $pustakawan = Role::where('name', 'Pustakawan')->firstOrFail();
    $kepala = Role::where('name', 'Kepala Perpustakaan')->firstOrFail();
    $gugusMutu = Role::where('name', 'Gugus Mutu')->firstOrFail();

    // Ambil semua permission yang sudah ada
    $allPermissions = Permission::all()->keyBy('name');

    // Helper function untuk ambil permission objek dari nama, abaikan yang tidak ada
    $getPermissions = fn(array $names) => collect($names)
      ->map(fn($name) => $allPermissions->get($name))
      ->filter()
      ->all();

    // Admin: manajemen pengguna (CRUD), dokumen (CRUD + approve/reject)
    $adminDocActions = ['create', 'read', 'update', 'delete', 'approve', 'reject'];
    $docTypes = ['laporan', 'surat', 'dokumen mutu'];

    $adminPermissions = array_merge(
      ['create user', 'read user', 'update user', 'delete user'],
      collect($docTypes)->flatMap(fn($type) => collect($adminDocActions)->map(fn($action) => "$action $type"))->toArray()
    );
    $admin->syncPermissions($getPermissions($adminPermissions));

    // Pustakawan: create & read dokumen surat & laporan
    $pustakawanPermissions = [];
    foreach (['laporan', 'surat'] as $type) {
      foreach (['create', 'read'] as $action) {
        $pustakawanPermissions[] = "$action $type";
      }
    }
    $pustakawan->syncPermissions($getPermissions($pustakawanPermissions));

    // Kepala Perpustakaan:
    // create & read dokumen mutu,
    // manajemen (CRUD + approve/reject) dokumen surat & laporan
    $kepalaPermissions = [];

    // create & read dokumen mutu
    foreach (['dokumen mutu'] as $type) {
      foreach (['create', 'read'] as $action) {
        $kepalaPermissions[] = "$action $type";
      }
    }

    // CRUD + approve/reject dokumen surat & laporan
    foreach (['laporan', 'surat'] as $type) {
      foreach (['create', 'read', 'update', 'delete', 'approve', 'reject'] as $action) {
        $kepalaPermissions[] = "$action $type";
      }
    }
    $kepala->syncPermissions($getPermissions($kepalaPermissions));

    // Gugus Mutu: manajemen (CRUD + approve/reject) dokumen mutu
    $gugusMutuPermissions = [];
    foreach (['dokumen mutu'] as $type) {
      foreach (['create', 'read', 'update', 'delete', 'approve', 'reject'] as $action) {
        $gugusMutuPermissions[] = "$action $type";
      }
    }
    $gugusMutu->syncPermissions($getPermissions($gugusMutuPermissions));
  }
}

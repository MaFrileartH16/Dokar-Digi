<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
  public function run(): void
  {
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    // Permission dokumen
    collect(['laporan', 'surat', 'dokumen mutu'])->each(function ($type) {
      collect(['create', 'read', 'update', 'delete', 'approve', 'reject'])
        ->each(fn($action) => Permission::firstOrCreate(['name' => "$action $type"]));
    });

    // Permission manajemen pengguna
    collect(['create user', 'read user', 'update user', 'delete user'])
      ->each(fn($perm) => Permission::firstOrCreate(['name' => $perm]));
  }
}

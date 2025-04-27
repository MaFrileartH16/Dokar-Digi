<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
  public function run(): void
  {
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    collect(['Admin', 'Pustakawan', 'Gugus Mutu', 'Kepala Perpustakaan'])
      ->each(fn($role) => Role::firstOrCreate(['name' => $role]));
  }
}

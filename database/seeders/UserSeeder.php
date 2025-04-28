<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  public function run(): void
  {
    // Create admin
    $admin = User::firstOrCreate(
      ['email' => 'admin@dokar-digi.ummi.ac.id'],
      [
        'name' => 'Admin',
        'password' => Hash::make('admin@dokar-digi.ummi.ac.id'),
        'email_verified_at' => now(),
      ]
    );
    $admin->assignRole('Admin');

    // Non-production: create users & assign roles
    if (App::environment(['local', 'development', 'testing']) || config('app.debug')) {
      User::factory(100)->create();

      collect(['Kepala Perpustakaan', 'Gugus Mutu'])->each(function ($role) use ($admin) {
        User::where('id', '!=', $admin->id)
          ->whereDoesntHave('roles')
          ->first()?->assignRole($role);
      });

      User::whereDoesntHave('roles')
        ->get()
        ->each->assignRole('Pustakawan');
    }
  }
}

<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin',
            'password' => bcrypt('admin'),
        ]);

        Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        Permission::create([
            'name' => 'do anything',
            'guard_name' => 'web',
        ]);

        User::first()->assignRole('admin');
        Role::first()->givePermissionTo('do anything');

        User::factory()->count(20)->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // $superadmin = User::firstOrCreate(
        //     ['email' => 'superadmin@inventory.test'],
        //     [
        //         'name'     => 'Super Admin',
        //         'password' => Hash::make('password'),
        //     ]
        // );
        // $superadmin->assignRole('superadmin');

        // $admin = User::firstOrCreate(
        //     ['email' => 'admin@inventory.test'],
        //     [
        //         'name'     => 'Admin Gudang',
        //         'password' => Hash::make('password'),
        //     ]
        // );
        // $admin->assignRole('admin_gudang');

        // $viewer = User::firstOrCreate(
        //     ['email' => 'viewer@inventory.test'],
        //     [
        //         'name'     => 'Viewer',
        //         'password' => Hash::make('password'),
        //     ]
        // );
        // $viewer->assignRole('viewer');
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@inventory.test'],
            [
                'name'      => 'Super Admin',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@inventory.test'],
            [
                'name'      => 'Admin Gudang',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $viewer = User::firstOrCreate(
            ['email' => 'viewer@inventory.test'],
            [
                'name'      => 'Viewer',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ]
        );
    }
}

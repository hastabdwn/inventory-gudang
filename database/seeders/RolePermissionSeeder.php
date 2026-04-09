<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Master data
            'view master-data',
            'manage master-data',

            // Purchase Order
            'view purchase-order',
            'create purchase-order',
            'approve purchase-order',
            'receive goods',

            // Distribusi
            'view distribution',
            'create distribution',

            // Transfer
            'view transfer',
            'create transfer',

            // Retur
            'view return',
            'create return',

            // Stok
            'view stock',
            'adjust stock',

            // Laporan
            'view report',
            'export report',

            // User management
            'manage users',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Superadmin — semua akses
        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        $superadmin->givePermissionTo(Permission::all());

        // Admin gudang — operasional penuh kecuali user management & approve PO
        $adminGudang = Role::firstOrCreate(['name' => 'admin_gudang']);
        $adminGudang->givePermissionTo([
            'view master-data', 'manage master-data',
            'view purchase-order', 'create purchase-order', 'receive goods',
            'view distribution', 'create distribution',
            'view transfer', 'create transfer',
            'view return', 'create return',
            'view stock', 'adjust stock',
            'view report', 'export report',
        ]);

        // Viewer — hanya lihat & ekspor
        $viewer = Role::firstOrCreate(['name' => 'viewer']);
        $viewer->givePermissionTo([
            'view master-data',
            'view purchase-order',
            'view distribution',
            'view transfer',
            'view return',
            'view stock',
            'view report', 'export report',
        ]);
    }
}
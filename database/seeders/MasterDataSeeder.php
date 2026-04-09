<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Gudang
        $warehouses = [
            ['code' => 'GDG-01', 'name' => 'Gudang Utama',   'location' => 'Lantai 1, Gedung A'],
            ['code' => 'GDG-02', 'name' => 'Gudang Cadangan','location' => 'Lantai 2, Gedung A'],
            ['code' => 'GDG-03', 'name' => 'Gudang Transit',  'location' => 'Gedung B'],
        ];
        foreach ($warehouses as $w) {
            Warehouse::firstOrCreate(['code' => $w['code']], $w);
        }

        // Kategori
        $categories = ['Elektronik', 'Alat Tulis', 'Peralatan Kebersihan', 'Spare Part', 'Bahan Baku', 'Kemasan'];
        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => Str::slug($cat)],
                ['name' => $cat, 'slug' => Str::slug($cat)]
            );
        }

        // Satuan
        $units = [
            ['name' => 'Pieces',    'abbreviation' => 'pcs'],
            ['name' => 'Kilogram',  'abbreviation' => 'kg'],
            ['name' => 'Liter',     'abbreviation' => 'L'],
            ['name' => 'Box',       'abbreviation' => 'box'],
            ['name' => 'Karton',    'abbreviation' => 'ktn'],
            ['name' => 'Meter',     'abbreviation' => 'm'],
            ['name' => 'Roll',      'abbreviation' => 'roll'],
        ];
        foreach ($units as $u) {
            Unit::firstOrCreate(['abbreviation' => $u['abbreviation']], $u);
        }

        // Supplier
        $suppliers = [
            [
                'code'           => 'SUP-001',
                'name'           => 'PT. Sumber Makmur',
                'contact_person' => 'Budi Santoso',
                'phone'          => '021-5551234',
                'email'          => 'budi@sumbermakmur.com',
                'address'        => 'Jl. Industri No. 12, Jakarta',
            ],
            [
                'code'           => 'SUP-002',
                'name'           => 'CV. Cahaya Logistik',
                'contact_person' => 'Siti Rahayu',
                'phone'          => '021-5559876',
                'email'          => 'siti@cahayalogistik.com',
                'address'        => 'Jl. Raya Bekasi No. 45, Bekasi',
            ],
        ];
        foreach ($suppliers as $s) {
            Supplier::firstOrCreate(['code' => $s['code']], $s);
        }
    }
}
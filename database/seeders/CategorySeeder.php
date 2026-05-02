<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Main Course',   'description' => 'Makanan berat pilihan Garage Coffee', 'sort_order' => 1],
            ['name' => 'Lite Bites',    'description' => 'Camilan dan snack ringan',            'sort_order' => 2],
            ['name' => 'Eskosu',        'description' => 'Es kopi susu signature Garage',        'sort_order' => 3],
            ['name' => 'Basic Coffee',  'description' => 'Kopi klasik pilihan',                  'sort_order' => 4],
            ['name' => 'Mixology',      'description' => 'Minuman spesial dan mocktail',         'sort_order' => 5],
            ['name' => 'Variant',       'description' => 'Varian teh dan minuman non-kopi',      'sort_order' => 6],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                array_merge($cat, ['is_active' => true])
            );
        }
    }
}

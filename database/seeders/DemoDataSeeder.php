<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\ProductVariant;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 5 Kategori
        $catHotCoffee = Category::create(['name' => 'Kopi Panas', 'slug' => 'kopi-panas', 'sort_order' => 1, 'is_active' => true]);
        $catColdCoffee = Category::create(['name' => 'Kopi Dingin', 'slug' => 'kopi-dingin', 'sort_order' => 2, 'is_active' => true]);
        $catNonCoffee = Category::create(['name' => 'Non-Coffee', 'slug' => 'non-coffee', 'sort_order' => 3, 'is_active' => true]);
        $catSnack = Category::create(['name' => 'Makanan Ringan', 'slug' => 'makanan-ringan', 'sort_order' => 4, 'is_active' => true]);
        $catOther = Category::create(['name' => 'Minuman Lainnya', 'slug' => 'minuman-lainnya', 'sort_order' => 5, 'is_active' => true]);

        // 10 Bahan Baku
        $matCoffeeBeans = RawMaterial::create(['name' => 'Biji Kopi Arabica (Base)', 'unit' => 'gram', 'current_stock' => 5000, 'minimum_stock' => 1000]);
        $matMilk = RawMaterial::create(['name' => 'Susu Fresh Milk', 'unit' => 'ml', 'current_stock' => 10000, 'minimum_stock' => 2000]);
        $matCaramel = RawMaterial::create(['name' => 'Sirup Karamel', 'unit' => 'ml', 'current_stock' => 2000, 'minimum_stock' => 500]);
        $matVanilla = RawMaterial::create(['name' => 'Sirup Vanilla', 'unit' => 'ml', 'current_stock' => 2000, 'minimum_stock' => 500]);
        $matMatcha = RawMaterial::create(['name' => 'Bubuk Matcha', 'unit' => 'gram', 'current_stock' => 1000, 'minimum_stock' => 200]);
        $matChoco = RawMaterial::create(['name' => 'Bubuk Coklat', 'unit' => 'gram', 'current_stock' => 1500, 'minimum_stock' => 300]);
        $matTea = RawMaterial::create(['name' => 'Daun Teh Premium', 'unit' => 'gram', 'current_stock' => 1000, 'minimum_stock' => 200]);
        $matSugar = RawMaterial::create(['name' => 'Gula Cair', 'unit' => 'ml', 'current_stock' => 5000, 'minimum_stock' => 1000]);
        $matCroissant = RawMaterial::create(['name' => 'Croissant Frozen', 'unit' => 'pcs', 'current_stock' => 50, 'minimum_stock' => 10]);
        $matFries = RawMaterial::create(['name' => 'Kentang Goreng Frozen', 'unit' => 'gram', 'current_stock' => 3000, 'minimum_stock' => 500]);

        // 15 Produk

        // Kopi Panas
        $p1 = Product::create(['category_id' => $catHotCoffee->id, 'image' => '/images/espresso.png', 'name' => 'Garage Espresso', 'slug' => 'garage-espresso', 'description' => 'Double shot espresso blend rahasia Garage Coffee', 'base_price' => 18000, 'is_available' => true, 'is_featured' => true]);
        $p1->recipes()->create(['raw_material_id' => $matCoffeeBeans->id, 'quantity_needed' => 18]);

        $p2 = Product::create(['category_id' => $catHotCoffee->id, 'name' => 'Hot Cappuccino', 'slug' => 'hot-cappuccino', 'description' => 'Espresso dengan susu steamed dan foam tebal', 'base_price' => 25000, 'is_available' => true, 'is_featured' => false]);
        $p2->recipes()->create(['raw_material_id' => $matCoffeeBeans->id, 'quantity_needed' => 18]);
        $p2->recipes()->create(['raw_material_id' => $matMilk->id, 'quantity_needed' => 150]);

        $p3 = Product::create(['category_id' => $catHotCoffee->id, 'image' => '/images/espresso.png', 'name' => 'Hot Latte', 'slug' => 'hot-latte', 'description' => 'Espresso dengan susu hangat', 'base_price' => 25000, 'is_available' => true, 'is_featured' => true]);
        $p3->recipes()->create(['raw_material_id' => $matCoffeeBeans->id, 'quantity_needed' => 18]);
        $p3->recipes()->create(['raw_material_id' => $matMilk->id, 'quantity_needed' => 180]);

        // Kopi Dingin
        $p4 = Product::create(['category_id' => $catColdCoffee->id, 'name' => 'Iced Americano', 'slug' => 'iced-americano', 'description' => 'Long black segar dengan es batu', 'base_price' => 22000, 'is_available' => true, 'is_featured' => false]);
        $p4->recipes()->create(['raw_material_id' => $matCoffeeBeans->id, 'quantity_needed' => 18]);

        $p5 = Product::create(['category_id' => $catColdCoffee->id, 'image' => '/images/macchiato.png', 'name' => 'Iced Caramel Macchiato', 'slug' => 'iced-caramel-macchiato', 'description' => 'Iced Latte dengan sirup karamel spesial', 'base_price' => 28000, 'is_available' => true, 'is_featured' => true]);
        $p5->recipes()->create(['raw_material_id' => $matCoffeeBeans->id, 'quantity_needed' => 18]);
        $p5->recipes()->create(['raw_material_id' => $matMilk->id, 'quantity_needed' => 150]);
        $p5->recipes()->create(['raw_material_id' => $matCaramel->id, 'quantity_needed' => 20]);

        $p6 = Product::create(['category_id' => $catColdCoffee->id, 'image' => '/images/macchiato.png', 'name' => 'Garage Coffee Milk', 'slug' => 'garage-coffee-milk', 'description' => 'Es Kopi Susu Gula Aren signature Garage', 'base_price' => 20000, 'is_available' => true, 'is_featured' => true]);
        $p6->recipes()->create(['raw_material_id' => $matCoffeeBeans->id, 'quantity_needed' => 18]);
        $p6->recipes()->create(['raw_material_id' => $matMilk->id, 'quantity_needed' => 120]);
        $p6->recipes()->create(['raw_material_id' => $matSugar->id, 'quantity_needed' => 20]);

        // Non-Coffee
        $p7 = Product::create(['category_id' => $catNonCoffee->id, 'name' => 'Matcha Latte Hot', 'slug' => 'matcha-latte-hot', 'description' => 'Matcha premium Jepang diseduh dengan susu', 'base_price' => 25000, 'is_available' => true, 'is_featured' => false]);
        $p7->recipes()->create(['raw_material_id' => $matMatcha->id, 'quantity_needed' => 15]);
        $p7->recipes()->create(['raw_material_id' => $matMilk->id, 'quantity_needed' => 180]);

        $p8 = Product::create(['category_id' => $catNonCoffee->id, 'name' => 'Iced Chocolate', 'slug' => 'iced-chocolate', 'description' => 'Coklat belgian dingin dengan susu', 'base_price' => 26000, 'is_available' => true, 'is_featured' => true]);
        $p8->recipes()->create(['raw_material_id' => $matChoco->id, 'quantity_needed' => 20]);
        $p8->recipes()->create(['raw_material_id' => $matMilk->id, 'quantity_needed' => 160]);
        
        $p9 = Product::create(['category_id' => $catNonCoffee->id, 'name' => 'Vanilla Milkshake', 'slug' => 'vanilla-milkshake', 'description' => 'Susu vanilla di blend dingin', 'base_price' => 25000, 'is_available' => true, 'is_featured' => false]);
        $p9->recipes()->create(['raw_material_id' => $matMilk->id, 'quantity_needed' => 200]);
        $p9->recipes()->create(['raw_material_id' => $matVanilla->id, 'quantity_needed' => 20]);

        // Makanan Ringan
        $p10 = Product::create(['category_id' => $catSnack->id, 'image' => '/images/croissant.png', 'name' => 'Croissant Butter', 'slug' => 'croissant-butter', 'description' => 'Croissant renyah original premium', 'base_price' => 18000, 'is_available' => true, 'is_featured' => false]);
        $p10->recipes()->create(['raw_material_id' => $matCroissant->id, 'quantity_needed' => 1]);

        $p11 = Product::create(['category_id' => $catSnack->id, 'name' => 'French Fries', 'slug' => 'french-fries', 'description' => 'Kentang goreng crinkle cut ukuran besar', 'base_price' => 20000, 'is_available' => true, 'is_featured' => true]);
        $p11->recipes()->create(['raw_material_id' => $matFries->id, 'quantity_needed' => 150]);

        $p12 = Product::create(['category_id' => $catSnack->id, 'name' => 'Mix Platter', 'slug' => 'mix-platter', 'description' => 'Kentang goreng, sosis, dan nugget (porsi sharing)', 'base_price' => 35000, 'is_available' => true, 'is_featured' => false]);
        $p12->recipes()->create(['raw_material_id' => $matFries->id, 'quantity_needed' => 100]);

        // Minuman Lainnya
        $p13 = Product::create(['category_id' => $catOther->id, 'name' => 'Lemon Tea Iced', 'slug' => 'lemon-tea-iced', 'description' => 'Teh dengan sari lemon segar', 'base_price' => 18000, 'is_available' => true, 'is_featured' => false]);
        $p13->recipes()->create(['raw_material_id' => $matTea->id, 'quantity_needed' => 5]);
        $p13->recipes()->create(['raw_material_id' => $matSugar->id, 'quantity_needed' => 15]);

        $p14 = Product::create(['category_id' => $catOther->id, 'name' => 'Lychee Tea', 'slug' => 'lychee-tea', 'description' => 'Teh rasa leci dengan buah leci asli', 'base_price' => 22000, 'is_available' => true, 'is_featured' => true]);
        $p14->recipes()->create(['raw_material_id' => $matTea->id, 'quantity_needed' => 5]);

        $p15 = Product::create(['category_id' => $catOther->id, 'name' => 'Mineral Water', 'slug' => 'mineral-water', 'description' => 'Air mineral botol 600ml', 'base_price' => 8000, 'is_available' => true, 'is_featured' => false]);
        
        // Variants untuk beberapa produk
        ProductVariant::create(['product_id' => $p6->id, 'name' => 'Regular', 'additional_price' => 0, 'is_available' => true]);
        ProductVariant::create(['product_id' => $p6->id, 'name' => 'Large', 'additional_price' => 5000, 'is_available' => true]);

        ProductVariant::create(['product_id' => $p5->id, 'name' => 'Regular', 'additional_price' => 0, 'is_available' => true]);
        ProductVariant::create(['product_id' => $p5->id, 'name' => 'Large', 'additional_price' => 6000, 'is_available' => true]);
    }
}

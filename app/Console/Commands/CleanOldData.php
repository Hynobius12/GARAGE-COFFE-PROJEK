<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Product;

class CleanOldData extends Command
{
    protected $signature = 'garage:clean-old-data';
    protected $description = 'Remove old categories and products not in the new menu';

    public function handle()
    {
        $keep = ['Main Course', 'Lite Bites', 'Eskosu', 'Basic Coffee', 'Mixology', 'Variant'];

        $oldIds = Category::whereNotIn('name', $keep)->pluck('id');

        if ($oldIds->isEmpty()) {
            $this->info('No old categories found. Nothing to clean.');
            return;
        }

        $products = Product::whereIn('category_id', $oldIds)->delete();
        $this->info("Deleted {$products} old products.");

        $cats = Category::whereNotIn('name', $keep)->delete();
        $this->info("Deleted {$cats} old categories.");

        $this->info('Remaining: ' . Category::count() . ' categories, ' . Product::count() . ' products.');
    }
}

<?php

use App\Models\Category;
use App\Models\Product;

$keep = ['Main Course','Lite Bites','Eskosu','Basic Coffee','Mixology','Variant'];

$oldCatIds = Category::whereNotIn('name', $keep)->pluck('id')->toArray();

if (!empty($oldCatIds)) {
    $deleted = Product::whereIn('category_id', $oldCatIds)->delete();
    echo "Deleted $deleted old products\n";
    $deletedCats = Category::whereNotIn('name', $keep)->delete();
    echo "Deleted $deletedCats old categories\n";
} else {
    echo "No old categories found\n";
}

echo "Remaining: " . Category::count() . " categories, " . Product::count() . " products\n";

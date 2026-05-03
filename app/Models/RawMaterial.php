<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RawMaterial extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    // public function recipes()
    // {
    //     return $this->hasMany(ProductRecipe::class);
    // }

    public function productRecipes()
    {
        // Sesuaikan 'ProductRecipe' dengan nama model resep kamu
        return $this->hasMany(ProductRecipe::class, 'raw_material_id');
    }

    public function transactions()
    {
        return $this->hasMany(StockTransaction::class);
    }
}

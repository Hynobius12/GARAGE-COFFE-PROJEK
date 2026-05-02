<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $guarded = ['id'];

    public function recipes()
    {
        return $this->hasMany(ProductRecipe::class);
    }

    public function transactions()
    {
        return $this->hasMany(StockTransaction::class);
    }
}

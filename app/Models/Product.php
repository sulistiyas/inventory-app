<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'stock', 'category_id'];
    protected $table = 'products';
    protected $primaryKey = 'id_product';
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id_category');
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class, 'product_id', 'id_product');
    }
}

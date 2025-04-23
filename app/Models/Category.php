<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{   
    protected $table = 'categories';
    protected $primaryKey = 'id_category';
    protected $fillable = ['name'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'id_category', 'id_category');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    protected $fillable = ['product_id', 'user_id', 'type', 'amount', 'note'];
    protected $table = 'stock_histories';
    protected $primaryKey = 'id_stock_history';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

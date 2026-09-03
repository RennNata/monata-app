<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'id_kategori',
        'nama_produk',
        'harga',
        'stok',
        'foto'
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'id_category');
    }
    public function transaction_detail() {
        return $this->hasMany(TransactionDetail::class, 'id_produk');
    }
}

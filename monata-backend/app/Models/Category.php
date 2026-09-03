<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'nama_kategori'
    ];

    public function product() {
        return $this->hasMany(Product::class, 'id_category');
    }
}
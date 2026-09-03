<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = [
        'id_transaksi',
        'id_produk',
        'jumlah',
        'subtotal'
    ];

    public function transaksi() {
        return $this->belongsTo(Transaction::class);
    }

    public function product() {
        return $this->belongsTo(Product::class, 'id_produk');
    }
}

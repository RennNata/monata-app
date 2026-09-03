<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'kode_transaksi',
        'id_user',
        'id_admin',
        'total_harga',
        'tipe_transaksi',
        'status'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function transaction_detail() {
        return $this->hasMany(TransactionDetail::class, 'id_transaksi');
    }
}

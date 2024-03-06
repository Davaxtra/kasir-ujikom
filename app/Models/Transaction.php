<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'kasir_id',
        'total_harga',
        'bayar',
        'kembalian'
    ];

    public function details()
    {
        return $this->hasMany(DetailTransaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

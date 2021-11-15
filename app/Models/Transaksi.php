<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $fillable = [
        'tanggal', 'category', 'keterangan', 'pemasukan','pengeluaran','flag'
    ];

    public function ketegori(){
        return $this->hasMany(Ketegori::class);
     }
}

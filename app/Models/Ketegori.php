<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ketegori extends Model
{
    use HasFactory;
    protected $fillable = [
        'category',
    ];
}

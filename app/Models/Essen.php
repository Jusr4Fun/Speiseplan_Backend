<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Essen extends Model
{
    use HasFactory;

    protected $fillable = [
        'bezeichnung',
    ];

    protected $table = 'essen';
    public $timestamps = false;
}

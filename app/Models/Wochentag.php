<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wochentag extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',

    ];

    protected $table = 'wochentag';
    public $timestamps = false;

    public function spezialEssen()
    {
        return $this->hasMany(Spezial_Essen::class);
    }
}

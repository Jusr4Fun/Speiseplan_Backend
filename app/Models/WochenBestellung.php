<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WochenBestellung extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'wochen_id',
        'abteilung_id',
        'anzahl_essen_normal',
        'montag_normal',
        'dienstag_normal',
        'mittwoch_normal',
        'donnerstag_normal',
        'freitag_normal',
    ];

    protected $table = 'wochen_bestellung';
    public $timestamps = false;
}

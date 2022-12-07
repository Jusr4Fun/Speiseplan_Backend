<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WochenBestellung extends Model
{
    use HasFactory;

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

    public function woche()
    {
        return $this->belongsTo(Woche::class);
    }

    public function abteilung()
    {
        return $this->belongsTo(Abteilung::class);
    }

    public function spezialEssen()
    {
        return $this->hasMany(Spezial_Essen::class, 'wochen_bestellung_id');
    }

    public function scopeWoche($query, $value)
    {
        return $query->where('wochen_id', $value);
    }
}

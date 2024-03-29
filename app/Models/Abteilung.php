<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abteilung extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function user() 
    {
        return $this->hasMany(User::class, 'abteilung_id');
    }

    public function teilnehmer() 
    {
        return $this->hasMany(Teilnehmer::class, 'abteilungs_id');
    }

    public function scopeWocheBestellungen() 
    {
        return $this->hasMany(WochenBestellung::class, 'abteilung_id');
    }

    protected $table = 'abteilungen';
    public $timestamps = false;
}

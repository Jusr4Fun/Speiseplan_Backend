<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teilnehmer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'abteilungs_id'
    ];

    protected $table = 'teilnehmer';
    public $timestamps = false;

    public function abteilung()
    {
        return $this->belongsTo(Abteilung::class, 'abteilungs_id');
    }
}

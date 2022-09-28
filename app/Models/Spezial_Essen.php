<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spezial_Essen extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'essen_id',
        'teilnehmer_id',
        'wochen_bestellung_id',
        'wochentag_id',
    ];

    protected $table = 'spezial_essen';
    public $timestamps = false;

    public function essen()
    {
        return $this->belongsTo(Essen::class, 'essen_id');
    }

    public function teilnehmer()
    {
        return $this->belongsTo(Teilnehmer::class, 'teilnehmer_id');
    }

    public function wochentag()
    {
        return $this->belongsTo(Wochentag::class, 'wochentag_id');
    }

    public function wochen_bestellung()
    {
        return $this->belongsTo(WochenBestellung::class, 'wochen_bestellung_id');
    }
}

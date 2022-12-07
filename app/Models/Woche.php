<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Woche extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $table = 'wochen';
    public $timestamps = false;

    public function wochenBestellungen() {
        return $this->hasMany(WochenBestellung::class, 'wochen_id');
    }
}

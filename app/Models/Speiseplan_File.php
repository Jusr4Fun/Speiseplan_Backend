<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speiseplan_File extends Model
{
    use HasFactory;

    protected $fillable = [
        'wochen_id',
        'file_path',
        'file_name',
    ];

    protected $table = 'speiseplan_files';
    public $timestamps = false;

    public function woche()
    {
        return $this->belongsTo(Woche::class, 'wochen_id');
    }
}

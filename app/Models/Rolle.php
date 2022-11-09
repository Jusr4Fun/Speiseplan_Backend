<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rolle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'role_code',
    ];

    protected $table = 'rollen';
    public $timestamps = false;

    public function user() 
    {
        return $this->hasMany(User::class, 'role_id');
    }
}

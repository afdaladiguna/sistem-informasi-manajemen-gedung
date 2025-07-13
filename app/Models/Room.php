<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | HAPUS BAGIAN INI
    |--------------------------------------------------------------------------
    |
    | public function building()
    | {
    |     return $this->belongsTo(Building::class);
    | }
    |
    */

    // Relasi ini benar, biarkan saja
    public function room()
    {
        return $this->hasMany(Rent::class);
    }

    public function getRouteKeyName()
    {
        return 'code';
    }
}

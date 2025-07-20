<?php
// app/Models/Room.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Relasi BARU: satu ruangan punya banyak gambar
    public function images()
    {
        return $this->hasMany(RoomImage::class);
    }

    // Relasi lama ini bisa dibiarkan
    public function room()
    {
        return $this->hasMany(Rent::class);
    }

    public function getRouteKeyName()
    {
        return 'code';
    }
}

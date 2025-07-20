<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_room_images_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('room_images', function (Blueprint $table) {
            $table->id();
            // Foreign key yang terhubung ke tabel rooms
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->string('path'); // Kolom untuk menyimpan path gambar
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('room_images');
    }
};

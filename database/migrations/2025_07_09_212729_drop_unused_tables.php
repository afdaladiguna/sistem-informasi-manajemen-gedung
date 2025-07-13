<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Perintah untuk menghapus tabel-tabel
        Schema::dropIfExists('admins');
        Schema::dropIfExists('buildings');
        Schema::dropIfExists('faculties');
        Schema::dropIfExists('failed_jobs');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // (Opsional) Kode untuk membuat KEMBALI tabel jika migration ini di-rollback
        // Sebaiknya diisi jika Anda ingin migration ini bisa dibatalkan (reversible)
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // Anda bisa menambahkan schema untuk membuat kembali tabel lain di sini jika perlu
    }
};

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
        // 1. Mengubah tabel users
        Schema::table('users', function (Blueprint $table) {
            // Pastikan kolomnya ada sebelum mengubah nama
            if (Schema::hasColumn('users', 'nomor_induk')) {
                $table->renameColumn('nomor_induk', 'phone_number');
            }
        });

        // 2. Mengubah tabel rooms
        Schema::table('rooms', function (Blueprint $table) {
            // Pastikan kolomnya ada sebelum dihapus
            if (Schema::hasColumn('rooms', 'building_id')) {
                // HAPUS BARIS INI: $table->dropForeign(['building_id']);
                $table->dropColumn('building_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};

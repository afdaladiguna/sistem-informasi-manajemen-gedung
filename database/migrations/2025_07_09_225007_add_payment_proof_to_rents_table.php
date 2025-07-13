<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rents', function (Blueprint $table) {
            // Kolom untuk menyimpan path/nama file bukti pembayaran
            $table->string('payment_proof')->nullable()->after('payment_method');
        });
    }

    public function down()
    {
        Schema::table('rents', function (Blueprint $table) {
            $table->dropColumn('payment_proof');
        });
    }
};

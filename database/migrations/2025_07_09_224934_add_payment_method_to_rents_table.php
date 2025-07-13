<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rents', function (Blueprint $table) {
            // Menambahkan kolom setelah kolom 'purpose'
            $table->string('payment_method')->after('purpose')->nullable();
        });
    }

    public function down()
    {
        Schema::table('rents', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
};

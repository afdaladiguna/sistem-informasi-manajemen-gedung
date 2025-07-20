<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Rename column using raw SQL for MariaDB/MySQL compatibility
        if (Schema::hasColumn('users', 'nomor_induk')) {
            DB::statement('ALTER TABLE users CHANGE nomor_induk phone_number VARCHAR(255) UNIQUE NULL');
        }

        // 2. Drop building_id from rooms (no need to drop foreign key if it doesn't exist)
        if (Schema::hasColumn('rooms', 'building_id')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->dropColumn('building_id');
            });
        }
    }

    public function down()
    {
        // You can implement reverse logic if needed
    }
};
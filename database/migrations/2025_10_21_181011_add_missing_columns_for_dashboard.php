<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Cek dan tambahkan kolom yang kurang
        
        if (!Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('avatar');
            });
        }
        
        if (!Schema::hasColumn('archives', 'priority')) {
            Schema::table('archives', function (Blueprint $table) {
                $table->enum('priority', ['urgent', 'high', 'normal', 'low'])->default('normal')->after('jenis_arsip');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('users', 'is_active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
        
        if (Schema::hasColumn('archives', 'priority')) {
            Schema::table('archives', function (Blueprint $table) {
                $table->dropColumn('priority');
            });
        }
    }
};
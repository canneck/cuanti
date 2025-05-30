<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->change();
            $table->string('email', 50)->nullable()->change();
            $table->string('address', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->string('phone', 30)->nullable(false)->change();
            $table->string('email', 50)->nullable(false)->change();
            $table->string('address', 255)->nullable(false)->change();
        });
    }
};

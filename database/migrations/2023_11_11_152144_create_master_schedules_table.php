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
        Schema::create('master_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->integer('day');
            $table->integer('time');
            $table->integer('duration');
            $table->string('name');
            $table->string('class');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_schedules');
    }
};

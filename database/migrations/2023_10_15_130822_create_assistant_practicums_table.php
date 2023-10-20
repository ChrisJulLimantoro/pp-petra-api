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
        Schema::create('assistant_practicums', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assistant_id');
            $table->uuid('practicum_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('assistant_id')->references('user_id')->on('assistants')->onDelete('cascade');
            $table->foreign('practicum_id')->references('id')->on('practicums')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistant_practicums');
    }
};

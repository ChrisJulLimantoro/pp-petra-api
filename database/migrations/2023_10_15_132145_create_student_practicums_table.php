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
        Schema::create('student_practicums', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('practicum_id');
            $table->integer('attempt');
            $table->integer('accepted')->default(0)->comment('0: waiting, 1: accepted, 2: rejected');
            $table->string('rejected_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('student_id')->references('user_id')->on('students');
            $table->foreign('practicum_id')->references('id')->on('practicums');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_practicums');
    }
};

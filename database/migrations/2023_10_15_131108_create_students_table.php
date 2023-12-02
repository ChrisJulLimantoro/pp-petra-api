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
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('user_id')->primary();
            $table->string('program')->comment('i=>infor,s=>sib,d=>dsa');
            $table->string('semester');
            $table->json('prs')->nullable();
            $table->decimal('ipk')->default(0);
            $table->decimal('ips')->default(0);
            $table->string('last_periode')->comment('last_periode of Input');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

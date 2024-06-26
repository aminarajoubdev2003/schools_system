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
        Schema::create('classroms', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->enum('stage',['secondary','highschool']);
            $table->enum('level',['5','6','7','8','9','10','11','12']);
            $table->enum('branch',['scientific','literal'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroms');
    }
};

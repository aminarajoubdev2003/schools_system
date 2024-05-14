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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('subject');
            $table->unsignedBigInteger('classrom_id');
            $table->foreign('classrom_id')->references('id')->on('classroms')->onDelete('CASCADE');
            $table->enum('type',['book','abrief','explain']);
            $table->enum('semester',['first','second']);
            $table->string('path')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};

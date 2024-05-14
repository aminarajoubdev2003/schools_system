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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('title');
            $table->string('address');
            $table->unsignedBigInteger('boss_id');
            $table->foreign('boss_id')->references('id')->on('bosses')->onDelete('CASCADE');
            $table->string('logo');
            $table->string('phone')->unique();
            $table->string('mobile')->unique();
            $table->integer('installment');
            $table->integer('transportation_cost');
            $table->unique(['title','boss_id','logo']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};


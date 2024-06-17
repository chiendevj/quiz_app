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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('quiz_id');
            $table->integer('posission')->nullable();
            $table->string('excerpt');
            $table->integer('point')->default(1);
            $table->integer('time')->default(30);
            $table->text('image')->nullable();
            $table->text('sound')->nullable();
            $table->text('youtube')->nullable();
            $table->string('optional')->default("");
            $table->string('type')->default('radio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }

    
};

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
        Schema::create('competition_sections', function (Blueprint $table) {
            $table->id();
            $table->string('anchor')->unique();
            $table->json('nav_label');
            $table->json('eyebrow')->nullable();
            $table->json('title');
            $table->json('body');
            $table->json('aside')->nullable();
            $table->json('below')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_sections');
    }
};

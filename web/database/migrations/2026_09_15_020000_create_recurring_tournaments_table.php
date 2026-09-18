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
        Schema::create('recurring_tournaments', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('slug_cs')->unique();
            $table->string('slug_en')->unique();
            $table->json('frequency')->nullable();
            $table->json('location_text')->nullable();
            $table->foreignId('herna_id')->nullable()->constrained()->nullOnDelete();
            $table->string('url')->nullable();
            $table->json('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_tournaments');
    }
};

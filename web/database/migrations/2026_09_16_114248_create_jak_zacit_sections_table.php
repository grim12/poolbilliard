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
        Schema::create('jak_zacit_sections', function (Blueprint $table) {
            $table->id();
            $table->string('anchor')->unique();
            $table->json('nav_label');
            $table->json('eyebrow')->nullable();
            $table->json('title');
            $table->json('intro');
            $table->json('steps');
            $table->json('aside_panel_title')->nullable();
            $table->json('aside_panel_text')->nullable();
            $table->json('aside_panel_button_text')->nullable();
            $table->string('aside_panel_button_url')->nullable();
            $table->json('aside_card_eyebrow')->nullable();
            $table->json('aside_card_title')->nullable();
            $table->json('aside_card_text')->nullable();
            $table->json('aside_card_button_text')->nullable();
            $table->string('aside_card_button_url')->nullable();
            $table->json('faq_title')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jak_zacit_sections');
    }
};

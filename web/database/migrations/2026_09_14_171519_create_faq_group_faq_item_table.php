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
        Schema::create('faq_group_faq_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faq_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('faq_item_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['faq_group_id', 'faq_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq_group_faq_item');
    }
};

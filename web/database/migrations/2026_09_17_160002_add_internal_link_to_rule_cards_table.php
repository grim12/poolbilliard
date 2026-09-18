<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rule_cards', function (Blueprint $table) {
            $table->string('link_route')->nullable()->after('button_url');
            $table->nullableMorphs('linkable');
        });
    }

    public function down(): void
    {
        Schema::table('rule_cards', function (Blueprint $table) {
            $table->dropColumn(['link_route', 'linkable_type', 'linkable_id']);
        });
    }
};

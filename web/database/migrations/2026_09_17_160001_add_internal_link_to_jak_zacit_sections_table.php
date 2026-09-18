<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Two independent link targets (aside panel button, aside card button), so two full sets of
     * columns — can't share one linkable_type/linkable_id pair between them.
     */
    public function up(): void
    {
        Schema::table('jak_zacit_sections', function (Blueprint $table) {
            $table->string('aside_panel_link_route')->nullable()->after('aside_panel_button_url');
            $table->nullableMorphs('aside_panel_linkable');
            $table->string('aside_card_link_route')->nullable()->after('aside_card_button_url');
            $table->nullableMorphs('aside_card_linkable');
        });
    }

    public function down(): void
    {
        Schema::table('jak_zacit_sections', function (Blueprint $table) {
            $table->dropColumn([
                'aside_panel_link_route', 'aside_panel_linkable_type', 'aside_panel_linkable_id',
                'aside_card_link_route', 'aside_card_linkable_type', 'aside_card_linkable_id',
            ]);
        });
    }
};

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
        Schema::table('education_contents', function (Blueprint $table) {
            // Make the content field nullable since we're now using sections for structured content
            $table->text('content')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('education_contents', function (Blueprint $table) {
            // Revert content field to not nullable
            $table->text('content')->nullable(false)->change();
        });
    }
};

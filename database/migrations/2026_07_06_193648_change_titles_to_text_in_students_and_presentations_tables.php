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
        Schema::table('students', function (Blueprint $table) {
            $table->text('research_title')->change();
        });

        Schema::table('presentations', function (Blueprint $table) {
            $table->text('presentation_title')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('research_title')->change();
        });

        Schema::table('presentations', function (Blueprint $table) {
            $table->string('presentation_title')->nullable()->change();
        });
    }
};

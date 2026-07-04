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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('examiner_id')->constrained('users')->onDelete('cascade');
            $table->integer('presentation_score')->default(0)->comment('Max 25');
            $table->integer('research_content_score')->default(0)->comment('Max 25');
            $table->integer('methodology_score')->default(0)->comment('Max 25');
            $table->integer('qa_score')->default(0)->comment('Max 25');
            $table->integer('total_score')->default(0)->comment('Max 100');
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            // An examiner can only review a specific student once
            $table->unique(['student_id', 'examiner_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

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
        Schema::create('student_supervisor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Supervisor
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('comments')->nullable();
            $table->timestamps();
            
            // Prevent duplicate entries for the same student-supervisor pair
            $table->unique(['student_id', 'user_id']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('supervisor_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('supervisor_name')->nullable();
        });
        
        Schema::dropIfExists('student_supervisor');
    }
};

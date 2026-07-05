<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->date('presentation_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->string('venue')->nullable();
            $table->enum('status', ['scheduled', 'presented', 'completed'])->default('scheduled');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};

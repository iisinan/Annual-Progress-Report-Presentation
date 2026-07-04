<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presentations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('presentation_title')->nullable();
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->enum('status', ['pending', 'uploaded'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presentations');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('matric_number')->unique();
            $table->string('phone_number');
            $table->foreignId('department_id')->constrained()->onDelete('restrict');
            $table->foreignId('programme_id')->constrained()->onDelete('restrict');
            $table->string('supervisor_name');
            $table->string('research_title');
            $table->string('current_research_stage');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

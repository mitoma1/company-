<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('breaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained()->onDelete('cascade');
            $table->dateTime('break_start_time');
            $table->dateTime('break_end_time');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('breaks');
    }
};

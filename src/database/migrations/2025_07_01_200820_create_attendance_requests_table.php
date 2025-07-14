<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attendance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('attendance_id')->constrained()->onDelete('cascade');
            $table->date('work_date');  // ← ここを追加
            $table->dateTime('request_clock_in_time');
            $table->dateTime('request_clock_out_time');
            $table->text('request_note');
            $table->enum('status', ['承認待ち', '承認済み'])->default('承認待ち');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_requests');
    }
};

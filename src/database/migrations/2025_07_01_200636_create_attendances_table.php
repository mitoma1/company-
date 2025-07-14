<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('work_date');
            $table->dateTime('clock_in_time');
            $table->dateTime('clock_out_time');
            $table->enum('status', ['勤務外', '出勤中', '休憩中', '退勤済']);
            $table->enum('approval_status', ['承認待ち', '承認済み', '却下'])->default('承認待ち');
            $table->text('note');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};

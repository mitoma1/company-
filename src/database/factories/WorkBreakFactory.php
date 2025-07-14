<?php

namespace Database\Factories;

use App\Models\WorkBreak;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkBreakFactory extends Factory
{
    protected $model = WorkBreak::class;

    public function definition(): array
    {
        $attendance = Attendance::inRandomOrder()->first();
        if (!$attendance) {
            $attendance = Attendance::factory()->create();
        }

        $clockIn = \Carbon\Carbon::parse($attendance->clock_in_time);
        $breakStart = (clone $clockIn)->addHours(3);
        $breakEnd = (clone $breakStart)->addMinutes(30);

        return [
            'attendance_id' => $attendance->id,
            'break_start_time' => $breakStart,
            'break_end_time' => $breakEnd,
        ];
    }
}

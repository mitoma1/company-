<?php

namespace Database\Factories;

use App\Models\Break;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

class BreakFactory extends Factory
{
    protected $model = Break::class;

    public function definition(): array
    {
        $attendance = Attendance::inRandomOrder()->first();
        if (!$attendance) {
            $attendance = Attendance::factory()->create();
        }

        $breakStart = (clone $attendance->clock_in_time)->modify('+3 hours');
        $breakEnd = (clone $breakStart)->modify('+30 minutes');

        return [
            'attendance_id' => $attendance->id,
            'break_start_time' => $breakStart,
            'break_end_time' => $breakEnd,
        ];
    }
}

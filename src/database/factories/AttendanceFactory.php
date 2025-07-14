<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        $workDate = $this->faker->date();

        $startTime = $this->faker->dateTimeBetween("$workDate 08:00:00", "$workDate 10:00:00");
        $endTime = (clone $startTime)->modify('+8 hours');

        return [
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory()->create()->id,
            'work_date' => $workDate,
            'clock_in_time' => $startTime,
            'clock_out_time' => $endTime,
            'status' => $this->faker->randomElement(['勤務外', '出勤中', '休憩中', '退勤済']),
            'note' => $this->faker->sentence(),
        ];
    }
}

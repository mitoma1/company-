<?php

namespace Database\Factories;

use App\Models\AttendanceRequest;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AttendanceRequestFactory extends Factory
{
    protected $model = AttendanceRequest::class;

    public function definition(): array
    {
        $workDate = Carbon::today()->subDays(rand(0, 30));

        return [
            'user_id' => User::factory(),
            'attendance_id' => Attendance::factory(),
            'request_clock_in_time' => $workDate->copy()->setTime(rand(7, 9), rand(0, 59)),
            'request_clock_out_time' => $workDate->copy()->setTime(rand(17, 19), rand(0, 59)),
            'work_date' => $workDate->toDateString(),
            'reason' => $this->faker->sentence(),
            'status' => 'pending',
        ];
    }
}

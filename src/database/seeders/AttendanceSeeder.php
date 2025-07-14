<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $userId = 1; // 自分のテストユーザーIDに合わせて

        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            Attendance::create([
                'user_id' => $userId,
                'work_date' => $date->toDateString(),
                'clock_in_time' => $date->copy()->setTime(9, 0),
                'clock_out_time' => $date->copy()->setTime(18, 0),
                'status' => '出勤中',
                'note' => '',
            ]);
        }
    }
}

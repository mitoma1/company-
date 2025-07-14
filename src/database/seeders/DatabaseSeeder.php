<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use App\Models\WorkBreak;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 一般ユーザー10人
        \App\Models\User::factory()->count(10)->create();

        // 管理者ユーザー10人
        \App\Models\User::factory()->count(10)->admin()->create();

        // ダミー勤怠データ50件をファクトリで作成
        Attendance::factory()->count(50)->create();

        // 勤怠データに対し、休憩データを2件ずつ作成
        Attendance::all()->each(function ($attendance) {
            WorkBreak::factory()->count(2)->create([
                'attendance_id' => $attendance->id
            ]);
        });

        // 🌱 今月の勤怠データを user_id=1 に作成（Seeder手動作成）
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $attendance = Attendance::create([
                'user_id' => 1,
                'work_date' => $date->toDateString(),
                'clock_in_time' => $date->copy()->setTime(9, 0),
                'clock_out_time' => $date->copy()->setTime(18, 0),
                'status' => '出勤中',
                'note' => '',
            ]);

            // 休憩を1件作成（例）
            WorkBreak::create([
                'attendance_id' => $attendance->id,
                'break_start_time' => $date->copy()->setTime(12, 0),
                'break_end_time' => $date->copy()->setTime(13, 0),
            ]);
        }
    }
}

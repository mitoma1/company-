<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendanceRequest;

class AttendanceRequestSeeder extends Seeder
{
    public function run()
    {
        // 10件のダミーデータ作成
        AttendanceRequest::factory()->count(10)->create();
    }
}

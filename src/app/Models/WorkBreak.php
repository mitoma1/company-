<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkBreak extends Model
{
    use HasFactory;

    protected $table = 'breaks';  // テーブル名が breaks のため明示

    protected $fillable = [
        'attendance_id',
        'break_start_time',
        'break_end_time',
    ];

    protected $casts = [
        'break_start_time' => 'datetime:H:i',
        'break_end_time' => 'datetime:H:i',
    ];

    // 休憩は1つの勤怠に属する
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}

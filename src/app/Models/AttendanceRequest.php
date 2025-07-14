<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'user_id',
        'work_date',
        'clock_in_time',
        'clock_out_time',
        'break_start_time',
        'break_end_time',
        'note',
        'status',
    ];

    protected $casts = [
        'work_date' => 'date',
        'clock_in_time' => 'datetime',
        'clock_out_time' => 'datetime',
        'break_start_time' => 'datetime',
        'break_end_time' => 'datetime',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

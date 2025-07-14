<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_date',
        'clock_in_time',
        'clock_out_time',
        'status',
        'note',
    ];

    protected $casts = [
        'work_date' => 'date',
        'clock_in_time' => 'datetime',
        'clock_out_time' => 'datetime',
    ];

    public function breaks()
    {
        return $this->hasMany(WorkBreak::class, 'attendance_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

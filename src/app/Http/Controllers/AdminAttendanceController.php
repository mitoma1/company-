<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AdminAttendanceController extends Controller
{
    /**
     * 管理者用: 勤怠一覧（日次）
     */
    public function index(Request $request)
    {
        $date = $request->input('date')
            ? Carbon::createFromFormat('Y-m-d', $request->input('date'))
            : Carbon::today();

        $attendances = Attendance::with('user')
            ->whereDate('work_date', $date->toDateString())
            ->orderBy('user_id')
            ->get();

        return view('admin.attendance.index', compact('attendances', 'date'));
    }

    /**
     * 管理者用: 勤怠詳細
     */
    public function show(Attendance $attendance)
    {
        $attendance->load('user');
        return view('admin.attendance.show', compact('attendance'));
    }

    /**
     * 管理者用: 勤怠修正
     */
    public function update(Request $request, Attendance $attendance)
    {
        $attendance->load('user');

        $request->validate([
            'clock_in_time' => 'required|date_format:H:i',
            'clock_out_time' => 'required|date_format:H:i|after_or_equal:clock_in_time',
            'break_start_time' => 'nullable|date_format:H:i',
            'break_end_time' => 'nullable|date_format:H:i|after_or_equal:break_start_time',
            'note' => 'required|string'
        ], [
            'clock_out_time.after_or_equal' => '出勤時間もしくは退勤時間が不適切な値です。',
            'break_end_time.after_or_equal' => '休憩時間が勤務時間外です。',
            'note.required' => '備考を記入してください。'
        ]);

        // 休憩時間が勤務時間外チェック
        if (
            $request->break_start_time &&
            ($request->break_start_time < $request->clock_in_time || $request->break_end_time > $request->clock_out_time)
        ) {
            return back()->withErrors(['休憩時間が勤務時間外です。'])->withInput();
        }

        // 更新
        $attendance->update([
            'clock_in_time' => $request->clock_in_time,
            'clock_out_time' => $request->clock_out_time,
            'break_start_time' => $request->break_start_time,
            'break_end_time' => $request->break_end_time,
            'note' => $request->note,
        ]);

        return redirect()->route('admin.attendances.show', $attendance->id)->with('success', '修正が完了しました。');
    }
}

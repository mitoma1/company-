<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AdminAttendanceController extends Controller
{
    /**
     * 管理者用: 日次勤怠一覧表示
     *
     * @param Request $request
     * @return \Illuminate\View\View
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
     * 管理者用: 勤怠詳細表示
     *
     * @param Attendance $attendance
     * @return \Illuminate\View\View
     */
    public function show(Attendance $attendance)
    {
        $attendance->load('user', 'breaks'); // 必要ならリレーションをロード
        return view('admin.attendance.show', compact('attendance'));
    }

    /**
     * 管理者用: 勤怠情報更新処理
     *
     * @param Request $request
     * @param Attendance $attendance
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'clock_in_time'    => 'nullable|date_format:H:i',
            'clock_out_time'   => 'nullable|date_format:H:i|after:clock_in_time',
            'break_start_time' => 'nullable|date_format:H:i',
            'break_end_time'   => 'nullable|date_format:H:i|after:break_start_time',
            'note'             => 'nullable|string|max:255',
        ]);

        // noteがnullの場合は空文字に変換してDBエラー回避
        if (!isset($validated['note']) || $validated['note'] === null) {
            $validated['note'] = '';
        }

        $attendance->update($validated);

        return redirect()
            ->route('admin.attendances.show', $attendance->id)
            ->with('success', '勤怠情報を更新しました。');
    }
}

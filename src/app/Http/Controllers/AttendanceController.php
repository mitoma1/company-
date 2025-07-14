<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * 勤怠一覧（月別）
     */
    public function list(Request $request)
    {
        $month = $request->input('month');
        $currentMonth = $month
            ? Carbon::createFromFormat('Y-m', $month)->startOfMonth()
            : Carbon::now()->startOfMonth();

        $startDate = $currentMonth->copy()->startOfMonth();
        $endDate = $currentMonth->copy()->endOfMonth();

        $attendances = Attendance::with('breaks')
            ->where('user_id', Auth::id())
            ->whereBetween('work_date', [$startDate, $endDate])
            ->orderBy('work_date')
            ->get()
            ->keyBy('work_date');

        $days = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $attendance = $attendances->get($date->toDateString(), null);
            $days[] = [
                'date' => $date->copy(),
                'attendance' => $attendance
            ];
        }

        return view('attendance.list', compact('days', 'currentMonth'));
    }

    /**
     * 勤怠詳細（ユーザー用）
     */
    public function detail(Attendance $attendance)
    {
        // ※ 認可チェック外しました

        $attendance->load('breaks', 'user');
        $user = $attendance->user;

        return view('attendance.detail', compact('attendance', 'user'));
    }

    /**
     * 勤怠編集フォーム表示（修正申請画面）
     */
    public function edit(Attendance $attendance)
    {
        // ※ 認可チェック外しました

        $attendance->load('breaks');

        return view('attendance.edit', compact('attendance'));
    }

    /**
     * 勤怠更新処理
     */
    public function update(Request $request, Attendance $attendance)
    {
        // ※ 認可チェック外しました

        $validated = $request->validate([
            'clock_in_time' => 'required|date_format:H:i',
            'clock_out_time' => 'required|date_format:H:i|after:clock_in_time',
            'break_start_times.*' => 'nullable|date_format:H:i',
            'break_end_times.*' => 'nullable|date_format:H:i',
            'new_break_start' => 'nullable|date_format:H:i',
            'new_break_end' => 'nullable|date_format:H:i',
            'note' => 'nullable|string|max:255',
        ]);

        // 出勤・退勤時間更新
        $attendance->clock_in_time = Carbon::createFromFormat('H:i', $validated['clock_in_time']);
        $attendance->clock_out_time = Carbon::createFromFormat('H:i', $validated['clock_out_time']);
        $attendance->note = $validated['note'] ?? '';
        $attendance->save();

        // 既存休憩時間更新
        foreach ($attendance->breaks as $i => $break) {
            $start = $validated['break_start_times'][$i] ?? null;
            $end = $validated['break_end_times'][$i] ?? null;

            $break->break_start_time = $start ? Carbon::createFromFormat('H:i', $start) : null;
            $break->break_end_time = $end ? Carbon::createFromFormat('H:i', $end) : null;
            $break->save();
        }

        // 新規休憩時間追加
        if (!empty($validated['new_break_start']) && !empty($validated['new_break_end'])) {
            $attendance->breaks()->create([
                'break_start_time' => Carbon::createFromFormat('H:i', $validated['new_break_start']),
                'break_end_time' => Carbon::createFromFormat('H:i', $validated['new_break_end']),
            ]);
        }

        return redirect()->route('attendance.detail', $attendance->id)
            ->with('success', '勤怠情報を更新しました。');
    }

    /**
     * 申請画面表示
     */
    public function application()
    {
        // ※ 認可チェックなし（本人の申請一覧取得）
        $applications = Attendance::where('user_id', Auth::id())
            ->where('approval_status', '承認待ち')
            ->orderBy('work_date', 'desc')
            ->get();

        return view('application.index', compact('applications'));
    }
}

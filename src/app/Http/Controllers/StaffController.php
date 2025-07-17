<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StaffController extends Controller
{
    /**
     * 管理者用：スタッフ一覧
     */
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $staffs = User::where('role', 'user')
            ->withCount(['attendances as monthly_attendance_count' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('work_date', [$startOfMonth, $endOfMonth]);
            }])
            ->get();

        return view('admin.staff.index', compact('staffs'));
    }

    /**
     * 管理者用：スタッフの勤怠一覧（月次）
     */
    public function attendanceDetail($id, Request $request)
    {
        $staff = User::where('role', 'user')->findOrFail($id);

        $month = $request->input('month') ?? now()->format('Y-m');
        $currentMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        // 勤怠データ取得
        $attendances = $staff->attendances()
            ->whereBetween('work_date', [$startOfMonth, $endOfMonth])
            ->get()
            ->keyBy(function ($attendance) {
                return Carbon::parse($attendance->work_date)->format('Y-m-d');
            });

        // カレンダーデータ作成
        $days = [];
        for ($date = $startOfMonth->copy(); $date <= $endOfMonth; $date->addDay()) {
            $days[] = [
                'date' => $date->copy(),
                'attendance' => $attendances[$date->format('Y-m-d')] ?? null,
            ];
        }

        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        // ビュー名を統一（attendance_list.blade.php）
        return view('admin.staff.attendance_list', compact(
            'staff',
            'currentMonth',
            'prevMonth',
            'nextMonth',
            'days'
        ));
    }

    /**
     * 管理者用：スタッフ勤怠のCSVダウンロード
     */
    public function attendanceCsv($id, Request $request)
    {
        $staff = User::where('role', 'user')->findOrFail($id);

        $month = $request->input('month') ?? now()->format('Y-m');
        $currentMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        // CSVのために勤怠を取得
        $attendances = $staff->attendances()
            ->whereBetween('work_date', [$startOfMonth, $endOfMonth])
            ->get();

        $response = new StreamedResponse(function () use ($attendances) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['日付', '出勤', '退勤', '休憩', '合計']);

            foreach ($attendances as $attendance) {
                // 休憩時間
                $breakSeconds = $attendance->breaks->reduce(function ($carry, $break) {
                    $start = Carbon::parse($break->break_start_time);
                    $end = Carbon::parse($break->break_end_time);
                    return $carry + $end->diffInSeconds($start);
                }, 0);

                $breakHours = floor($breakSeconds / 3600);
                $breakMinutes = floor(($breakSeconds % 3600) / 60);
                $breakFormatted = sprintf('%d:%02d', $breakHours, $breakMinutes);

                // 合計勤務時間
                if ($attendance->clock_in_time && $attendance->clock_out_time) {
                    $start = Carbon::parse($attendance->clock_in_time);
                    $end = Carbon::parse($attendance->clock_out_time);
                    $totalSeconds = $end->diffInSeconds($start) - $breakSeconds;
                    $totalHours = floor($totalSeconds / 3600);
                    $totalMinutes = floor(($totalSeconds % 3600) / 60);
                    $totalFormatted = sprintf('%d:%02d', $totalHours, $totalMinutes);
                } else {
                    $totalFormatted = '-';
                }

                fputcsv($handle, [
                    $attendance->work_date,
                    optional($attendance->clock_in_time)->format('H:i'),
                    optional($attendance->clock_out_time)->format('H:i'),
                    $breakFormatted,
                    $totalFormatted,
                ]);
            }

            fclose($handle);
        });

        $filename = "attendance_{$staff->id}_{$currentMonth->format('Y-m')}.csv";
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");

        return $response;
    }
}

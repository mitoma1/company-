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
     * 管理者用：スタッフの勤怠詳細（月次）
     */
    public function attendanceDetail($id, Request $request)
    {
        $staff = User::where('role', 'user')->findOrFail($id);

        $month = $request->input('month') ?? now()->format('Y-m');
        $currentMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        $attendances = $staff->attendances()
            ->whereBetween('work_date', [$startOfMonth, $endOfMonth])
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->work_date)->format('Y-m-d');
            });

        $days = [];
        for ($date = $startOfMonth->copy(); $date <= $endOfMonth; $date->addDay()) {
            $days[] = [
                'date' => $date->copy(),
                'attendance' => $attendances[$date->format('Y-m-d')] ?? null
            ];
        }

        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        return view('admin.staff.attendance_detail', compact(
            'staff',
            'currentMonth',
            'prevMonth',
            'nextMonth',
            'days'
        ));
    }

    /**
     * 管理者用：CSVダウンロード
     */
    public function attendanceCsv($id, Request $request)
    {
        $staff = User::where('role', 'user')->findOrFail($id);

        $month = $request->input('month') ?? now()->format('Y-m');
        $currentMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        $attendances = $staff->attendances()
            ->whereBetween('work_date', [$startOfMonth, $endOfMonth])
            ->get();

        $response = new StreamedResponse(function () use ($attendances) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['日付', '出勤', '退勤', '休憩', '合計']);

            foreach ($attendances as $attendance) {
                fputcsv($handle, [
                    $attendance->work_date,
                    $attendance->clock_in_time,
                    $attendance->clock_out_time,
                    $attendance->break_time,
                    $attendance->total_time
                ]);
            }

            fclose($handle);
        });

        $filename = 'attendance_' . $staff->id . '_' . $currentMonth->format('Y-m') . '.csv';
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");

        return $response;
    }
}

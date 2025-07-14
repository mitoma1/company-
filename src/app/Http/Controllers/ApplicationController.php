<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRequest;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApplicationController extends Controller
{
    /**
     * 修正申請一覧画面
     * - URLパラメータで承認待ち or 承認済みを切り替え表示
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        if ($status === 'approved') {
            $applications = AttendanceRequest::with('user', 'attendance')
                ->where('status', '承認済み')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // デフォルトは承認待ち
            $applications = AttendanceRequest::with('user', 'attendance')
                ->where('status', '承認待ち')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('admin.applications.index', compact('applications', 'status'));
    }

    /**
     * 修正申請詳細画面
     */
    public function show($id)
    {
        $request = AttendanceRequest::with('user', 'attendance')->findOrFail($id);

        return view('admin.applications.show', compact('request'));
    }

    /**
     * 修正申請の承認処理
     */
    public function approve(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $attendanceRequest = AttendanceRequest::findOrFail($id);
            $attendance = Attendance::findOrFail($attendanceRequest->attendance_id);

            // 勤怠データを申請内容で更新
            $attendance->update([
                'clock_in_time' => new Carbon($attendanceRequest->clock_in_time),
                'clock_out_time' => new Carbon($attendanceRequest->clock_out_time),
                'note' => $attendanceRequest->note,
                'approval_status' => '承認済み',
            ]);

            // 申請ステータスを更新
            $attendanceRequest->update([
                'status' => '承認済み',
            ]);

            DB::commit();

            return redirect()->route('admin.application.index')->with('success', '申請を承認しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => '承認処理に失敗しました。']);
        }
    }
}

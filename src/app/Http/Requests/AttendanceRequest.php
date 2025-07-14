<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'break_start' => ['nullable', 'date_format:H:i', 'after_or_equal:start_time', 'before_or_equal:end_time'],
            'break_end' => ['nullable', 'date_format:H:i', 'after_or_equal:break_start', 'before_or_equal:end_time'],
            'note' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'start_time.required' => '出勤時間を入力してください',
            'start_time.date_format' => '出勤時間の形式が正しくありません',
            'end_time.required' => '退勤時間を入力してください',
            'end_time.date_format' => '退勤時間の形式が正しくありません',
            'end_time.after' => '退勤時間は出勤時間より後の時間を入力してください',
            'break_start.after_or_equal' => '休憩時間は勤務時間内で入力してください',
            'break_start.before_or_equal' => '休憩時間は勤務時間内で入力してください',
            'break_end.after_or_equal' => '休憩時間は勤務時間内で入力してください',
            'break_end.before_or_equal' => '休憩時間は勤務時間内で入力してください',
            'note.required' => '備考を入力してください',
        ];
    }
}

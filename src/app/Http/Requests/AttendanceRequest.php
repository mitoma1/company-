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
            'clock_in_time' => ['required', 'date_format:H:i'],
            'clock_out_time' => ['required', 'date_format:H:i', 'after:clock_in_time'],

            // 休憩開始時間は配列で任意の数を許容、各要素は時間形式
            'break_start_times' => ['nullable', 'array'],
            'break_start_times.*' => ['nullable', 'date_format:H:i', 'after_or_equal:clock_in_time', 'before_or_equal:clock_out_time'],

            // 休憩終了時間も同様
            'break_end_times' => ['nullable', 'array'],
            'break_end_times.*' => ['nullable', 'date_format:H:i', 'after_or_equal:break_start_times.*', 'before_or_equal:clock_out_time'],

            // 新規休憩（任意）
            'new_break_start' => ['nullable', 'date_format:H:i', 'after_or_equal:clock_in_time', 'before_or_equal:clock_out_time'],
            'new_break_end' => ['nullable', 'date_format:H:i', 'after_or_equal:new_break_start', 'before_or_equal:clock_out_time'],

            'note' => ['nullable'],
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

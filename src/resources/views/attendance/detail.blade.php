@extends('layouts.app')

@section('content')
<h1 class="text-xl font-bold mb-4">勤怠詳細</h1>

<table class="table-auto w-full border-collapse border border-gray-400 mb-4">
    <tr>
        <th class="border px-2 py-1 w-1/4 bg-gray-100">名前</th>
        <td class="border px-2 py-1">{{ $user->name }}</td>
    </tr>
    <tr>
        <th class="border px-2 py-1 bg-gray-100">日付</th>
        <td class="border px-2 py-1">{{ $attendance->work_date->format('Y年m月d日') }}</td>
    </tr>
    <tr>
        <th class="border px-2 py-1 bg-gray-100">出勤・退勤</th>
        <td class="border px-2 py-1">
            {{ optional($attendance->clock_in_time)->format('H:i') ?? '-' }} 〜 {{ optional($attendance->clock_out_time)->format('H:i') ?? '-' }}
        </td>
    </tr>
    <tr>
        <th class="border px-2 py-1 bg-gray-100 align-top">休憩</th>
        <td class="border px-2 py-1">
            @if($attendance->breaks->count())
            @foreach($attendance->breaks as $break)
            {{ optional($break->break_start_time)->format('H:i') ?? '-' }} 〜 {{ optional($break->break_end_time)->format('H:i') ?? '-' }}<br>
            @endforeach
            @else
            -
            @endif
        </td>
    </tr>
    <tr>
        <th class="border px-2 py-1 bg-gray-100">備考</th>
        <td class="border px-2 py-1">{{ $attendance->note ?? '-' }}</td>
    </tr>
</table>

<a href="{{ route('attendance.edit', $attendance->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
    修正申請
</a>
@endsection
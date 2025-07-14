@extends('layouts.app')

@section('content')
<h1 class="text-xl font-bold mb-4">{{ $staff->name }}さんの勤怠</h1>

<div class="flex justify-between items-center mb-4">
    <a href="{{ route('admin.staff.attendance', [$staff->id, 'month' => $prevMonth->format('Y-m')]) }}" class="text-blue-500 hover:underline">前月</a>
    <div>{{ $currentMonth->format('Y/m') }}</div>
    <a href="{{ route('admin.staff.attendance', [$staff->id, 'month' => $nextMonth->format('Y-m')]) }}" class="text-blue-500 hover:underline">翌月</a>
</div>

<table class="min-w-full bg-white mb-4 border border-gray-300">
    <thead>
        <tr class="bg-gray-100 border-b border-gray-300">
            <th class="py-2 px-4 border-r border-gray-300">日付</th>
            <th class="py-2 px-4 border-r border-gray-300 text-center">出勤</th>
            <th class="py-2 px-4 border-r border-gray-300 text-center">退勤</th>
            <th class="py-2 px-4 border-r border-gray-300 text-center">休憩</th>
            <th class="py-2 px-4 border-r border-gray-300 text-center">合計</th>
            <th class="py-2 px-4 text-center">詳細</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($days as $day)
        <tr class="border-b border-gray-300">
            <td class="py-2 px-4 border-r border-gray-300">{{ $day['date']->format('m/d(D)') }}</td>

            {{-- 出勤時間 --}}
            <td class="py-2 px-4 border-r border-gray-300 text-center">
                @if($day['attendance'] && $day['attendance']->clock_in_time)
                {{ \Carbon\Carbon::parse($day['attendance']->clock_in_time)->format('H:i') }}
                @else
                -
                @endif
            </td>

            {{-- 退勤時間 --}}
            <td class="py-2 px-4 border-r border-gray-300 text-center">
                @if($day['attendance'] && $day['attendance']->clock_out_time)
                {{ \Carbon\Carbon::parse($day['attendance']->clock_out_time)->format('H:i') }}
                @else
                -
                @endif
            </td>

            {{-- 休憩時間（合計） --}}
            <td class="py-2 px-4 border-r border-gray-300 text-center">
                @if($day['attendance'] && $day['attendance']->breaks->count())
                @php
                $totalBreakSeconds = 0;
                foreach ($day['attendance']->breaks as $break) {
                $start = \Carbon\Carbon::parse($break->break_start_time);
                $end = \Carbon\Carbon::parse($break->break_end_time);
                $totalBreakSeconds += $end->diffInSeconds($start);
                }
                $breakHours = floor($totalBreakSeconds / 3600);
                $breakMinutes = floor(($totalBreakSeconds % 3600) / 60);
                $breakFormatted = sprintf('%d:%02d', $breakHours, $breakMinutes);
                @endphp
                {{ $breakFormatted }}
                @else
                -
                @endif
            </td>

            {{-- 勤務合計時間（例：退勤 - 出勤 - 休憩） --}}
            <td class="py-2 px-4 border-r border-gray-300 text-center">
                @if($day['attendance'] && $day['attendance']->clock_in_time && $day['attendance']->clock_out_time)
                @php
                $start = \Carbon\Carbon::parse($day['attendance']->clock_in_time);
                $end = \Carbon\Carbon::parse($day['attendance']->clock_out_time);
                $workSeconds = $end->diffInSeconds($start);

                // 休憩時間を差し引く
                $workSeconds -= $totalBreakSeconds ?? 0;

                $workHours = floor($workSeconds / 3600);
                $workMinutes = floor(($workSeconds % 3600) / 60);
                $workFormatted = sprintf('%d:%02d', $workHours, $workMinutes);
                @endphp
                {{ $workFormatted }}
                @else
                -
                @endif
            </td>

            {{-- 詳細リンク --}}
            <td class="py-2 px-4 text-center">
                @if($day['attendance'])
                <a href="{{ route('admin.attendances.show', $day['attendance']->id) }}" class="text-blue-600 hover:underline">詳細</a>
                @else
                -
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('admin.staff.attendance.csv', [$staff->id, 'month' => $currentMonth->format('Y-m')]) }}"
    class="inline-block bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">
    CSV出力
</a>
@endsection
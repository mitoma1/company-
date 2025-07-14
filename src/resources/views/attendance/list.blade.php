@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">勤怠一覧</h1>

<div class="flex justify-center items-center mb-6 space-x-8">
    <a href="{{ route('attendance.list', ['month' => $currentMonth->copy()->subMonth()->format('Y-m')]) }}"
        class="text-gray-600 hover:text-black">
        前月
    </a>
    <span class="text-lg font-semibold">{{ $currentMonth->format('Y年m月') }}</span>
    <a href="{{ route('attendance.list', ['month' => $currentMonth->copy()->addMonth()->format('Y-m')]) }}"
        class="text-gray-600 hover:text-black">
        翌月
    </a>
</div>

<table class="table-auto w-full border-collapse border border-gray-300">
    <thead class="bg-gray-100">
        <tr class="text-center">
            <th class="border px-4 py-2">日付</th>
            <th class="border px-4 py-2">出勤</th>
            <th class="border px-4 py-2">退勤</th>
            <th class="border px-4 py-2">休憩</th>
            <th class="border px-4 py-2">合計</th>
            <th class="border px-4 py-2">詳細</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($days as $day)
        <tr class="text-center">
            <td class="border px-4 py-2">
                {{ $day['date']->format('m/d') }} ({{ ['日','月','火','水','木','金','土'][$day['date']->dayOfWeek] }})
            </td>

            {{-- 出勤時間 --}}
            <td class="border px-4 py-2">
                {{ optional(optional($day['attendance'])->clock_in_time)->format('H:i') ?? '09:00' }}
            </td>

            {{-- 退勤時間 --}}
            <td class="border px-4 py-2">
                {{ optional(optional($day['attendance'])->clock_out_time)->format('H:i') ?? '18:00' }}
            </td>

            {{-- 休憩時間 --}}
            <td class="border px-4 py-2">
                @if($day['attendance'] && $day['attendance']->breaks->count())
                @foreach($day['attendance']->breaks as $break)
                {{ optional($break->break_start_time)->format('H:i') }}〜{{ optional($break->break_end_time)->format('H:i') }}<br>
                @endforeach
                @else
                1:00
                @endif
            </td>

            {{-- 合計時間 --}}
            <td class="border px-4 py-2">
                @php
                if($day['attendance']) {
                $workMinutes = ($day['attendance']->clock_in_time && $day['attendance']->clock_out_time)
                ? $day['attendance']->clock_out_time->diffInMinutes($day['attendance']->clock_in_time)
                : 0;

                $breakMinutes = $day['attendance']->breaks->sum(function($b) {
                return $b->break_end_time && $b->break_start_time
                ? $b->break_end_time->diffInMinutes($b->break_start_time)
                : 0;
                });

                $totalMinutes = max($workMinutes - $breakMinutes, 0);
                $totalHours = floor($totalMinutes / 60) . ':' . str_pad($totalMinutes % 60, 2, '0', STR_PAD_LEFT);
                } else {
                $totalHours = '8:00';
                }
                @endphp
                {{ $totalHours }}
            </td>

            {{-- 詳細リンク --}}
            <td class="border px-4 py-2">
                <a href="{{ route('attendance.detail', ['attendance' => optional($day['attendance'])->id ?? 1]) }}"
                    class="text-blue-600 hover:underline">
                    詳細
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
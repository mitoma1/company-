@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">{{ $date->format('Y年m月d日') }}の勤怠</h1>

    <div class="flex space-x-4 mb-4">
        <a href="{{ route('admin.attendances.index', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}"
            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">前日</a>
        <span class="text-lg font-semibold">{{ $date->format('Y/m/d') }}</span>
        <a href="{{ route('admin.attendances.index', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}"
            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">翌日</a>
    </div>

    <table class="min-w-full border">
        <thead>
            <tr class="bg-gray-100 text-center">
                <th class="border px-4 py-2">名前</th>
                <th class="border px-4 py-2">出勤</th>
                <th class="border px-4 py-2">退勤</th>
                <th class="border px-4 py-2">休憩</th>
                <th class="border px-4 py-2">合計</th>
                <th class="border px-4 py-2">詳細</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $attendance)
            <tr class="text-center">
                <td class="border px-4 py-2">{{ $attendance->user->name }}</td>
                <td class="border px-4 py-2">{{ $attendance->clock_in_time }}</td>
                <td class="border px-4 py-2">{{ $attendance->clock_out_time }}</td>
                <td class="border px-4 py-2">
                    {{ $attendance->break_start_time }}~{{ $attendance->break_end_time }}
                </td>
                <td class="border px-4 py-2">
                    {{ \Carbon\Carbon::parse($attendance->clock_out_time)->diffInHours(\Carbon\Carbon::parse($attendance->clock_in_time)) }}時間
                </td>
                <td class="border px-4 py-2">
                    <a href="{{ route('admin.attendances.show', $attendance->id) }}"
                        class="text-blue-600 hover:underline">詳細</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-4 text-center">データがありません</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
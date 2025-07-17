@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">{{ $date->format('Y年m月d日') }} の勤怠</h1>

    <div class="flex justify-between mb-4">
        <a href="{{ route('admin.attendances.index', ['date' => $date->copy()->subDay()->toDateString()]) }}" class="btn btn-secondary">← 前日</a>
        <input type="date" value="{{ $date->toDateString() }}" onchange="location.href='?date=' + this.value" class="border rounded px-2 py-1" />
        <a href="{{ route('admin.attendances.index', ['date' => $date->copy()->addDay()->toDateString()]) }}" class="btn btn-secondary">翌日 →</a>
    </div>

    <table class="min-w-full bg-white border">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 border">名前</th>
                <th class="px-4 py-2 border">出勤</th>
                <th class="px-4 py-2 border">退勤</th>
                <th class="px-4 py-2 border">休憩</th>
                <th class="px-4 py-2 border">合計</th>
                <th class="px-4 py-2 border">詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
            <tr>
                <td class="border px-4 py-2">{{ $attendance->user->name }}</td>
                <td class="border px-4 py-2">{{ optional($attendance->clock_in_time)->format('H:i') }}</td>
                <td class="border px-4 py-2">{{ optional($attendance->clock_out_time)->format('H:i') }}</td>
                <td class="border px-4 py-2">{{ gmdate('H:i', $attendance->total_break_seconds ?? 0) }}</td>
                <td class="border px-4 py-2">{{ gmdate('H:i', $attendance->total_work_seconds ?? 0) }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('admin.attendances.show', $attendance->id) }}" class="text-blue-600 hover:underline">
                        詳細
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
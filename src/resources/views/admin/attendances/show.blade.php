@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-xl">
    <h1 class="text-2xl font-bold mb-6">勤怠詳細</h1>

    @if(session('success'))
    <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.attendances.update', $attendance->id) }}" class="space-y-4">
        @csrf
        @method('PUT') <!-- ⭐️ これが重要！ -->

        <div><strong>名前:</strong> {{ $attendance->user->name }}</div>
        <div><strong>日付:</strong> {{ \Carbon\Carbon::parse($attendance->work_date)->format('Y年m月d日') }}</div>

        <div>
            <label>出勤・退勤</label><br>
            <input type="time" name="clock_in_time" value="{{ old('clock_in_time', $attendance->clock_in_time) }}" class="border px-2 py-1"> ~
            <input type="time" name="clock_out_time" value="{{ old('clock_out_time', $attendance->clock_out_time) }}" class="border px-2 py-1">
            @error('clock_out_time')<div class="text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label>休憩</label><br>
            <input type="time" name="break_start_time" value="{{ old('break_start_time', $attendance->break_start_time) }}" class="border px-2 py-1"> ~
            <input type="time" name="break_end_time" value="{{ old('break_end_time', $attendance->break_end_time) }}" class="border px-2 py-1">
            @error('break_end_time')<div class="text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label>備考</label><br>
            <textarea name="note" class="border w-full p-2">{{ old('note', $attendance->note) }}</textarea>
            @error('note')<div class="text-red-600">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="bg-black text-white px-6 py-2 rounded hover:bg-gray-800">修正</button>
    </form>
</div>
@endsection
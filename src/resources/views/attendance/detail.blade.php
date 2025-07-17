@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">勤怠詳細</h2>

    <div class="mb-4">
        <label class="block font-semibold">名前</label>
        <div class="border p-2">{{ Auth::user()->name }}</div>
    </div>

    <div class="mb-4">
        <label class="block font-semibold">日付</label>
        <div class="border p-2">
            {{ $attendance->work_date->format('Y年 m月d日') }}
        </div>
    </div>

    <form id="attendanceForm" action="{{ route('attendance.update', $attendance->id) }}" method="POST">
        @csrf


        {{-- 出勤・退勤 --}}
        <div class="mb-4">
            <label class="block font-semibold">出勤・退勤</label>
            <div class="flex gap-2 items-center">
                <input
                    type="time"
                    name="clock_in_time"
                    value="{{ old('clock_in_time', $attendance->clock_in_time->format('H:i')) }}"
                    class="border p-1">
                <span>〜</span>
                <input
                    type="time"
                    name="clock_out_time"
                    value="{{ old('clock_out_time', $attendance->clock_out_time->format('H:i')) }}"
                    class="border p-1">
            </div>
            @error('clock_in_time')
            <div class="text-red-500">{{ $message }}</div>
            @enderror
            @error('clock_out_time')
            <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>

        {{-- 休憩 --}}
        <div class="mb-4">
            <label class="block font-semibold">休憩</label>
            @foreach ($attendance->breaks as $i => $break)
            <div class="flex gap-2 items-center mb-2">
                <input
                    type="time"
                    name="break_start_times[]"
                    value="{{ old("break_start_times.$i", $break->break_start_time->format('H:i')) }}"
                    class="border p-1">
                <span>〜</span>
                <input
                    type="time"
                    name="break_end_times[]"
                    value="{{ old("break_end_times.$i", $break->break_end_time->format('H:i')) }}"
                    class="border p-1">
            </div>
            @endforeach

            {{-- 新規追加 --}}
            <div class="flex gap-2 items-center">
                <input
                    type="time"
                    name="new_break_start"
                    class="border p-1">
                <span>〜</span>
                <input
                    type="time"
                    name="new_break_end"
                    class="border p-1">
            </div>

            @error('break_start_times.*')
            <div class="text-red-500">{{ $message }}</div>
            @enderror
            @error('break_end_times.*')
            <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>

        {{-- 備考 --}}
        <div class="mb-4">
            <label class="block font-semibold">備考</label>
            <textarea
                name="note"
                class="border w-full p-2">{{ old('note', $attendance->note) }}</textarea>
            @error('note')
            <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>

        {{-- ボタン --}}
        <div class="mt-4">
            <button
                type="submit"
                id="submitBtn"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                修正
            </button>
        </div>
    </form>
</div>
@endsection
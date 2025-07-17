@@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">勤怠編集</h2>

    {{-- 承認待ちの場合は編集不可 --}}
    @if ($attendance->approval_status === '承認待ち')
    <div class="p-4 bg-yellow-100 border border-yellow-400 rounded mb-4">
        承認待ちのため修正できません。
    </div>
    <a href="{{ route('attendance.detail', $attendance->id) }}" class="inline-block px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
        詳細へ戻る
    </a>
    @else
    <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- 名前 --}}
        <div class="mb-4">
            <label class="block font-semibold">名前</label>
            <div class="border p-2">{{ $user->name }}</div>
        </div>

        {{-- 日付 --}}
        <div class="mb-4">
            <label class="block font-semibold">日付</label>
            <div class="border p-2">{{ $attendance->work_date->format('Y年m月d日') }}</div>
        </div>

        {{-- 出勤・退勤 --}}
        <div class="mb-4">
            <label class="block font-semibold">出勤・退勤</label>
            <div class="flex gap-2 items-center">
                <input
                    type="time"
                    name="clock_in_time"
                    value="{{ old('clock_in_time', optional($attendance->clock_in_time)->format('H:i')) }}"
                    class="border p-1">
                <span>〜</span>
                <input
                    type="time"
                    name="clock_out_time"
                    value="{{ old('clock_out_time', optional($attendance->clock_out_time)->format('H:i')) }}"
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
                    value="{{ old("break_start_times.$i", optional($break->break_start_time)->format('H:i')) }}"
                    class="border p-1">
                <span>〜</span>
                <input
                    type="time"
                    name="break_end_times[]"
                    value="{{ old("break_end_times.$i", optional($break->break_end_time)->format('H:i')) }}"
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
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                修正
            </button>
            <a href="{{ route('attendance.detail', $attendance->id) }}" class="ml-4 px-4 py-2 border border-gray-400 rounded hover:bg-gray-100">
                キャンセル
            </a>
        </div>
    </form>
    @endif
</div>
@endsection
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endpush

@section('content')
<div x-data="attendance()" x-init="init()" class="text-center mt-12">

    <div class="attendance-datetime" x-text="statusText"></div>
    <div class="attendance-date" x-text="currentDate"></div>
    <div class="attendance-time" x-text="currentTime"></div>

    <!-- 勤務外 -->
    <template x-if="status === '勤務外'">
        <button @click="clockIn" class="attendance-button clock-in">出勤</button>
    </template>

    <!-- 出勤中 -->
    <template x-if="status === '出勤中'">
        <div class="space-x-4">
            <button @click="clockOut" class="attendance-button clock-out">退勤</button>
            <button @click="startBreak" class="attendance-button break-in">休憩入</button>
        </div>
    </template>

    <!-- 休憩中 -->
    <template x-if="status === '休憩中'">
        <div class="space-x-4">
            <button @click="endBreak" class="attendance-button break-out">休憩戻</button>
        </div>
    </template>

    <!-- 退勤済 -->
    <template x-if="status === '退勤済'">
        <div class="attendance-finish">
            お疲れ様でした。
        </div>
    </template>

</div>

<script>
    function attendance() {
        return {
            status: '勤務外',
            statusText: '勤務外',
            currentDate: '',
            currentTime: '',
            hasClockedIn: false,
            hasClockedOut: false,
            breakCount: 0,

            init() {
                this.updateTime();
                setInterval(() => this.updateTime(), 1000);
            },

            updateTime() {
                const now = new Date();
                this.currentDate = `${now.getFullYear()}年${now.getMonth()+1}月${now.getDate()}日(${['日','月','火','水','木','金','土'][now.getDay()]})`;
                this.currentTime = now.toLocaleTimeString('ja-JP', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                this.updateStatusText();
            },

            updateStatusText() {
                this.statusText = this.status;
            },

            clockIn() {
                if (this.hasClockedIn) {
                    alert('本日は既に出勤しています。');
                    return;
                }
                this.status = '出勤中';
                this.hasClockedIn = true;
                this.updateStatusText();
            },

            startBreak() {
                this.status = '休憩中';
                this.updateStatusText();
            },

            endBreak() {
                this.status = '出勤中';
                this.breakCount++;
                this.updateStatusText();
            },

            clockOut() {
                if (this.hasClockedOut) {
                    alert('既に退勤済みです。');
                    return;
                }
                this.status = '退勤済';
                this.hasClockedOut = true;
                this.updateStatusText();
            },
        }
    }
</script>
@endsection
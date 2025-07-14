@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/verify.css') }}">
@endpush

@section('content')
<div class="verify-container">
    <h1 class="verify-message">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </h1>

    {{-- 勤怠登録画面へ --}}
    <div style="margin-top: 20px;">
        <a href="{{ route('attendance.create') }}" class="verify-button">
            認証はこちらから
        </a>
    </div>

    <p class="resend-link">
        <a href="{{ route('verification.send') }}">認証メールを再送する</a>
    </p>
</div>
@endsection
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('content')
<div class="register-main">
    <div class="register-container">
        <h2>会員登録</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label>名前</label>
                <input type="text" name="name" value="{{ old('name') }}">
                @error('name')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label>メールアドレス</label>
                <input type="email" name="email" value="{{ old('email') }}">
                @error('email')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label>パスワード</label>
                <input type="password" name="password">
                @error('password')
                <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label>パスワード確認</label>
                <input type="password" name="password_confirmation">
            </div>

            <button type="submit" class="btn-register">登録する</button>
        </form>

        <div class="login-link">
            <a href="{{ route('login') }}" class="btn-login">ログインはこちら</a>
        </div>
    </div>
</div>
@endsection
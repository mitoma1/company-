@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
<div class="login-main">
    <div class="login-container">
        <h2>ログイン</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf

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

            <button type="submit" class="btn-login-submit">ログインする</button>
        </form>

        <div class="register-link">
            <a href="{{ route('register') }}" class="btn-register-link">会員登録はこちら</a>
        </div>
    </div>
</div>
@endsection
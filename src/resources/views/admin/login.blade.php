@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endpush

@section('content')
<div class="flex justify-center items-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded shadow">
        <h1 class="text-2xl font-bold mb-6 text-center">管理者ログイン</h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block mb-1 font-semibold">メールアドレス</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="w-full border border-gray-300 p-2 rounded" autofocus>
                @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label for="password" class="block mb-1 font-semibold">パスワード</label>
                <input id="password" type="password" name="password" class="w-full border border-gray-300 p-2 rounded">
                @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">管理者ログインする</button>
        </form>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-xl">
    <h1 class="text-2xl font-bold mb-6">修正申請詳細</h1>

    @if(session('success'))
    <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="mb-4 text-red-600">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div><strong>名前:</strong> {{ $request->user->name }}</div>
    <div><strong>対象日時:</strong> {{ \Carbon\Carbon::parse($request->work_date)->format('Y年m月d日') }}</div>
    <div><strong>出勤:</strong> {{ \Carbon\Carbon::parse($request->clock_in_time)->format('H:i') }}</div>
    <div><strong>退勤:</strong> {{ \Carbon\Carbon::parse($request->clock_out_time)->format('H:i') }}</div>
    <div><strong>備考:</strong> {{ $request->note }}</div>
    <div><strong>状態:</strong> {{ $request->status }}</div>

    @if($request->status === '承認待ち')
    <form method="POST" action="{{ route('admin.application.approve', $request->id) }}" class="mt-6">
        @csrf
        @method('PUT')
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            承認する
        </button>
        <a href="{{ route('admin.application.index') }}" class="ml-4 text-blue-500 hover:underline">戻る</a>
    </form>
    @else
    <a href="{{ route('admin.application.index') }}" class="inline-block mt-6 text-blue-500 hover:underline">一覧に戻る</a>
    @endif
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">申請一覧</h1>

    <div class="flex space-x-4 mb-6">
        <a href="?status=pending"
            class="px-4 py-2 rounded {{ request('status', 'pending') === 'pending' ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
            承認待ち
        </a>
        <a href="?status=approved"
            class="px-4 py-2 rounded {{ request('status') === 'approved' ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
            承認済み
        </a>
    </div>

    <table class="min-w-full bg-white shadow rounded">
        <thead class="bg-gray-100">
            <tr>
                <th class="py-2 px-4 border-b">状態</th>
                <th class="py-2 px-4 border-b">名前</th>
                <th class="py-2 px-4 border-b">対象日</th>
                <th class="py-2 px-4 border-b">申請理由</th>
                <th class="py-2 px-4 border-b">申請日</th>
                <th class="py-2 px-4 border-b">詳細</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($applications as $application)
            <tr>
                <td class="py-2 px-4 border-b text-center">{{ $application->status }}</td>
                <td class="py-2 px-4 border-b text-center">{{ $application->user->name }}</td>
                <td class="py-2 px-4 border-b text-center">{{ \Carbon\Carbon::parse($application->work_date)->format('Y/m/d') }}</td>
                <td class="py-2 px-4 border-b text-center">{{ $application->note }}</td>
                <td class="py-2 px-4 border-b text-center">{{ $application->created_at->format('Y/m/d') }}</td>
                <td class="py-2 px-4 border-b text-center">
                    <a href="{{ route('attendance.detail', $application->id) }}" class="text-blue-600 hover:underline">
                        詳細
                    </a>
                </td>
                @empty
            <tr>
                <td colspan="6" class="py-4 text-center text-gray-500">データがありません</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
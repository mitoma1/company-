@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">スタッフ一覧</h1>

    <table class="min-w-full bg-white shadow rounded">
        <thead class="bg-gray-100">
            <tr>
                <th class="py-2 px-4 border-b">名前</th>
                <th class="py-2 px-4 border-b">メールアドレス</th>
                <th class="py-2 px-4 border-b">月次勤怠</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($staffs as $staff)
            <tr>
                <td class="py-2 px-4 border-b">{{ $staff->name }}</td>
                <td class="py-2 px-4 border-b">{{ $staff->email }}</td>
                <td class="py-2 px-4 border-b text-center">
                    <a href="{{ route('admin.staff.attendance', $staff->id) }}"
                        class="bg-black text-white px-4 py-1 rounded hover:bg-gray-800">
                        詳細
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
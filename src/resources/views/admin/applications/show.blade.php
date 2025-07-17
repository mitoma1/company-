@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">承認詳細</h1>

    <div class="bg-white shadow rounded p-6 mb-6">
        <table class="min-w-full">
            <tbody>
                <tr class="border-b">
                    <th class="py-2 px-4 text-left w-40 bg-gray-50">名前</th>
                    <td class="py-2 px-4">西　伶奈</td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 px-4 text-left bg-gray-50">日付</th>
                    <td class="py-2 px-4">2023年6月1日</td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 px-4 text-left bg-gray-50">出勤・退勤</th>
                    <td class="py-2 px-4">09:00 〜 18:00</td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 px-4 text-left bg-gray-50">休憩</th>
                    <td class="py-2 px-4">12:00 〜 13:00</td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 px-4 text-left bg-gray-50">休憩2</th>
                    <td class="py-2 px-4">-</td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 px-4 text-left bg-gray-50">備考</th>
                    <td class="py-2 px-4">電車遅延のため</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex space-x-4">
        <a href="{{ route('admin.application.index') }}"
            class="inline-block bg-gray-400 text-white py-2 px-4 rounded hover:bg-gray-500">
            戻る
        </a>
        <form action="#" method="POST">
            @csrf
            @method('PUT')
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                承認する
            </button>
        </form>
    </div>
</div>
@endsection
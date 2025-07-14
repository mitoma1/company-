<header class="bg-black text-white">
    <div class="container mx-auto flex items-center justify-between px-4 py-3">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('images/logo.svg') }}" alt="ロゴ" class="logo" style="height: 40px;" />
        </div>

        @php
        $hideNavPaths = [
        'admin/login',
        'register',
        'login',
        'email/verify', // メール認証確認画面のURL例
        ];
        @endphp

        @if (!collect($hideNavPaths)->contains(fn($path) => request()->is($path)))
        <nav class="space-x-6">
            @auth
            @if (Auth::user()->isAdmin())
            <!-- 管理者メニュー -->
            <a href="{{ route('admin.attendances.index') }}" class="hover:underline">勤怠一覧</a>
            <a href="{{ route('admin.staff.index') }}" class="hover:underline">スタッフ一覧</a>
            <a href="{{ route('admin.application.index') }}" class="hover:underline">申請一覧</a>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="hover:underline">ログアウト</button>
            </form>
            @else
            <!-- ユーザーメニュー -->
            <a href="{{ route('attendance.create') }}" class="hover:underline">勤怠</a>
            <a href="{{ route('attendance.list') }}" class="hover:underline">勤怠一覧</a>
            <a href="{{ route('application') }}" class="hover:underline">申請</a>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="hover:underline">ログアウト</button>
            </form>
            @endif
            @endauth
        </nav>
        @endif
    </div>
</header>
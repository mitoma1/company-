<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Auth;

class FortifyServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Fortify のログイン画面をURLによって切り替え
        Fortify::loginView(function () {
            // もしURLが /admin/* なら管理者ログインビュー
            if (request()->is('admin/*')) {
                return view('admin.login');
            }
            // それ以外は通常ユーザーログインビュー
            return view('login');
        });

        // Fortify のログイン後リダイレクト制御
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            function () {
                return new class {
                    public function toResponse($request)
                    {
                        if (Auth::user() && Auth::user()->is_admin) {
                            return redirect('/admin/attendance/list');
                        }
                        return redirect('/attendance');
                    }
                };
            }
        );
    }
}

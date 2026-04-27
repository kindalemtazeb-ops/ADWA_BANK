<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// 1. ይህንን መስመር መጨመር እንዳትረሳ
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 2. የገጽ ማውጫው (Pagination) ዲዛይን በ Tailwind እንዲሆን ይሄን ጨምር
        Paginator::useTailwind();
    }
}

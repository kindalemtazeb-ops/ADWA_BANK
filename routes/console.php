<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

// 1. ትክክለኛው መንገድ (Laravel 11 ከሆነ)
Schedule::command('bank:calculate-interest')->lastDayOfMonth('00:00');

// 2. ወይም ደግሞ እንዲህ መጻፍ ትችላለህ (በየወሩ በ 1ኛው ቀን እንዲሰራ)
// Schedule::command('bank:calculate-interest')->monthly();


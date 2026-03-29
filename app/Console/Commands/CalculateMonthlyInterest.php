<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Account;
use Illuminate\Support\Facades\Log;

class CalculateMonthlyInterest extends Command
{
    // ይህ ትዕዛዝ በTerminal የሚጠራበት ስም ነው
    protected $signature = 'bank:calculate-interest';
    protected $description = 'በየወሩ መጨረሻ ለሁሉም ደንበኞች ወለድ ያስላል';

    public function handle()
    {
        $accounts = Account::all();

        foreach ($accounts as $account) {
            // ስሌት፦ ባላንስ * (ወለድ መጠን / 100 / 12 ወር)
            $rate = ($account->interest_rate ?? 7.00) / 100 / 12;
            $interestAmount = $account->balance * $rate;

            if ($interestAmount > 0) {
                $account->increment('balance', $interestAmount);
                // ለሪፖርት ያህል በLog ፋይል ላይ ይመዘግባል
                Log::info("ወለድ ታስቧል፦ {$interestAmount} ብር ለ {$account->full_name}");
            }
        }

        $this->info('የወሩ ወለድ ለሁሉም ደንበኞች በስኬት ታስቦ ተጨምሯል!');
    }
}

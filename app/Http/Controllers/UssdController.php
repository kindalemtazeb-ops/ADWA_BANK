<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;

class UssdController extends Controller
{
    public function handleUssd(Request $request)
    {
        // ከUSSD የመጣ ዳታ (SessionId, PhoneNumber, Text)
        $text = $request->input('text', '');
        $phoneNumber = $request->input('phoneNumber');

        $levels = explode('*', $text);
        $response = "";

        if ($text == "") {
            // መጀመሪያ ሲደወል የሚመጣ አማራጭ
            $response = "CON እንኳን ወደ አድዋ ባንክ በሰላም መጡ\n";
            $response .= "1. ሂሳብ ለማየት (Balance)\n";
            $response .= "2. ብር ለመላክ (Transfer)\n";
        }
        elseif ($levels[0] == "1") {
            // ሂሳብ ለማየት - PIN ይጠይቃል
            if (count($levels) == 1) {
                $response = "CON እባክዎ ሚስጥር ቁጥርዎን (PIN) ያስገቡ:";
            } else {
                $pin = $levels[1];
                $account = Account::where('phone_number', $phoneNumber)->where('pin', $pin)->first();
                if ($account) {
                    $response = "END የእርስዎ ቀሪ ሂሳብ " . number_format($account->balance, 2) . " ብር ነው።";
                } else {
                    $response = "END የተሳሳተ ሚስጥር ቁጥር!";
                }
            }
        }
        elseif ($levels[0] == "2") {
            // ብር ለመላክ
            if (count($levels) == 1) {
                $response = "CON የተቀባይ አካውንት ቁጥር ያስገቡ:";
            } elseif (count($levels) == 2) {
                $response = "CON የሚልኩትን የብር መጠን ያስገቡ:";
            } elseif (count($levels) == 3) {
                $response = "CON የእርስዎን ሚስጥር ቁጥር (PIN) ያስገቡ:";
            } elseif (count($levels) == 4) {
                $receiverAcc = $levels[1];
                $amount = $levels[2];
                $pin = $levels[3];

                $sender = Account::where('phone_number', $phoneNumber)->where('pin', $pin)->first();
                $receiver = Account::where('account_number', $receiverAcc)->first();

                if ($sender && $receiver && $sender->balance >= $amount) {
                    $sender->decrement('balance', $amount);
                    $receiver->increment('balance', $amount);
                    $response = "END በስኬት ተልኳል! ለ {$receiver->full_name} {$amount} ብር ልከዋል።";
                } else {
                    $response = "END ዝውውሩ አልተሳካም። ሚስጥር ቁጥር ወይም ባላንስዎን ያረጋግጡ።";
                }
            }
        }

        return response($response)->header('Content-Type', 'text/plain');
    }
}

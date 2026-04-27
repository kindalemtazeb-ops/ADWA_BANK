<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class UssdController extends Controller
{
    public function handleUssd(Request $request)
    {
        // ከ USSD Gateway የሚመጡ መረጃዎች
        $sessionId   = $request->get('sessionId');
        $phoneNumber = $request->get('phoneNumber');
        $text        = $request->get('text');

        $input = explode("*", $text);
        $level = (empty($text)) ? 0 : count($input);
        $response = "";

        // 1. መጀመሪያ ሲደውሉ የሚመጣ ሜኑ
        if ($level == 0) {
            $response  = "CON Welcome to Adwa Bank \n";
            $response .= "1. Check Balance \n";
            $response .= "2. Transfer Money \n";
            $response .= "3. My Account Info";
        }

        // 2. ባላንስ ለማየት (Check Balance)
        else if ($input[0] == "1") {
            $account = Account::where('phone_number', $phoneNumber)->first();
            if ($account) {
                $response = "END Your balance is: " . number_format($account->balance, 2) . " ETB";
            } else {
                $response = "END Sorry, your phone is not registered.";
            }
        }

        // 3. ብር ለመላክ (Transfer Money)
        else if ($input[0] == "2") {
            if ($level == 1) {
                $response = "CON Enter Receiver Account Number:";
            } else if ($level == 2) {
                $response = "CON Enter Amount to Send:";
            } else if ($level == 3) {
                $response = "CON Enter Your 4-digit PIN:";
            } else if ($level == 4) {
                $receiverAcc = $input[1];
                $amount = $input[2];
                $pin = $input[3];

                $sender = Account::where('phone_number', $phoneNumber)->first();
                $receiver = Account::where('account_number', $receiverAcc)->first();

                // ማረጋገጫዎች (Validations)
                // ማሳሰቢያ: pin የሚለውን ፊልድ አስተካክለነዋል
                if (!$sender || $sender->pin != $pin) {
                    $response = "END Incorrect PIN. Please try again.";
                } else if (!$receiver) {
                    $response = "END Receiver account not found.";
                } else if ($sender->balance < $amount) {
                    $response = "END Insufficient balance.";
                } else if ($sender->account_number == $receiver->account_number) {
                    $response = "END You cannot transfer to yourself.";
                } else {
                    // የገንዘብ ዝውውሩን መፈጸም
                    DB::transaction(function () use ($sender, $receiver, $amount) {
                        $sender->decrement('balance', $amount);
                        $receiver->increment('balance', $amount);
                    });
                    $response = "END Success! " . number_format($amount, 2) . " ETB sent to " . $receiver->full_name;
                }
            }
        }

        // 4. ስለ አካውንት መረጃ ለማየት
        else if ($input[0] == "3") {
            $account = Account::where('phone_number', $phoneNumber)->first();
            if ($account) {
                $response = "END Name: " . $account->full_name . "\nAcc: " . $account->account_number;
            } else {
                $response = "END Account not found.";
            }
        }

        return response($response)->header('Content-Type', 'text/plain');
    }
}

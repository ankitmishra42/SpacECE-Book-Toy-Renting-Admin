<?php

namespace App\Http\Controllers;

use App\Enums\WithdrawStatus;
use App\Models\Transaction;

class TransitionController extends Controller
{
    public function update(Transaction $transaction)
    {
        $status = request()->status;
        if ($status == WithdrawStatus::CANCLE->value) {
            $transaction->update([
                'status' => WithdrawStatus::CANCLE->value,
                'accept' => now(),
            ]);
        }

        if ($status == WithdrawStatus::CONFIRM->value) {

            $currentAmount = $transaction->wallet->amount - $transaction->amount;

            $transaction->wallet->update([
                'amount' => $currentAmount,
            ]);
            $transaction->update([
                'status' => WithdrawStatus::CONFIRM->value,
                'accept' => now(),
            ]);
        }

        return redirect()->back()->with('status', 'transaction updated');
    }
}

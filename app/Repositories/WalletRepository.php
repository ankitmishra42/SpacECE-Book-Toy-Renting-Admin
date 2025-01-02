<?php

namespace App\Repositories;

use App\Models\Wallet;

class WalletRepository extends Repository
{
    public function model()
    {
        return Wallet::class;
    }

    public function store($user)
    {
        return $this->create([
            'user_id' => $user->id,
        ]);
    }

    public function updateCredit(Wallet $wallet, $amount, $purpose, $orderId = null, $note = null, $storeId = null): Wallet
    {
        $totalAmount = $wallet->amount + $amount;
        $this->update($wallet, [
            'amount' => $totalAmount,
        ]);

        (new TransactionRepository())->create([
            'store_id' => $storeId,
            'wallet_id' => $wallet->id,
            'order_id' => $orderId,
            'amount' => $amount,
            'transition_id' => null,
            'transition_type' => 'credit',
            'purpose' => $purpose,
            'note' => $note,
        ]);

        return $wallet;
    }
}

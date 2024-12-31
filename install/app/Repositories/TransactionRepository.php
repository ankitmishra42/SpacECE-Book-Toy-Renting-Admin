<?php

namespace App\Repositories;

use App\Models\Transaction;
use Carbon\Carbon;

class TransactionRepository extends Repository
{
    public function model()
    {
        return Transaction::class;
    }

    public function monthlyTotalTransction()
    {
        $wallet = auth()->user()->wallet;

        $transctions = $wallet->transactions()->whereBetween('created_at', [Carbon::now()->startOfMonth(), now()])->where('wallet_id', $wallet->id)->get();

        $totalTransctions = [];
        foreach ($transctions as $transction) {
            $sum = $wallet->transactions()->whereDate('created_at', '=', $transction->created_at)->sum('amount');
            $totalTransctions[$transction->created_at->format('d-M')] = $sum;
        }

        return $totalTransctions;
    }
}

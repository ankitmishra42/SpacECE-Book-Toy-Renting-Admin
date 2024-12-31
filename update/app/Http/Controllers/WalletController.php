<?php

namespace App\Http\Controllers;

use App\Enums\WithdrawStatus;
use App\Models\Wallet;
use App\Repositories\TransactionRepository;
use App\Repositories\WalletRepository;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(
        private WalletRepository $walletRepo
    ) {
    }

    public function index()
    {
        $transactions = (new TransactionRepository())->monthlyTotalTransction();

        $transactionLable = array_keys($transactions);
        $transactionValue = array_values($transactions);

        $wallet = auth()->user()->wallet;
        $transactions = $wallet->transactions()->latest('id')->take(10)->get();
        $withdraws = $wallet->transactions()->where('is_withdraw', true)->latest('id')->get();
        $total = $wallet->transactions()->sum('amount');

        return view('wallet.index', compact('wallet', 'transactions', 'total', 'transactionLable', 'transactionValue', 'withdraws'));
    }

    public function update(Wallet $wallet, Request $request)
    {
        $author = auth()->user()->hasRole('root') ? 'super admin' : 'vendor';
        $purpose = "add amount from $author";
        $orderID = null;
        $note = $request->note;
        $this->walletRepo->updateCredit($wallet, $request->amount, $purpose, $orderID, $note);

        return back()->with('success', 'Updated successfully');
    }

    public function transaction(Wallet $wallet)
    {
        return view('transaction.index', compact('wallet'));
    }

    public function withdraw(Request $request, Wallet $wallet)
    {
        $minimumAmount = auth()->user()->hasRole('vendor') ? 500 : 100;
        if ($request->amount < $minimumAmount) {
            return redirect()->back()->with('error', "Minimum amount $minimumAmount");
        }

        if ($request->amount > $wallet->amount) {
            return redirect()->back()->with('error', 'Insufficient balance');
        }

        $withdrawRequest = $wallet->transactions()->where('is_withdraw', true)->where('status', WithdrawStatus::PENDING->value)->first();

        if ($withdrawRequest) {
            return redirect()->back()->with('error', 'Already withdraw request pending');
        }

        $purpose = 'withdraw amount';
        $note = $request->note;
        (new TransactionRepository())->create([
            'wallet_id' => $wallet->id,
            'amount' => $request->amount,
            'transition_id' => null,
            'transition_type' => 'debit',
            'purpose' => $purpose,
            'note' => $note,
            'is_withdraw' => true,
            'status' => WithdrawStatus::PENDING->value,
        ]);

        return redirect()->back()->with('success', 'Withdraw request send successfully');
    }
}

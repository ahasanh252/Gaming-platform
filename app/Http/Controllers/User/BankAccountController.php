<?php

namespace App\Http\Controllers\User;

use App\Models\UserBankAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankAccountController extends Controller
{
    /**
     * List user bank accounts
     */
    public function index()
    {
        $bankAccounts = auth()->user()->bankAccounts()->get();
        return view('user.bank-accounts.index', compact('bankAccounts'));
    }

    /**
     * Show add form
     */
    public function create()
    {
        return view('user.bank-accounts.create');
    }

    /**
     * Store new bank account
     */
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:bkash,nagad,binance,usdt',
            'account_number' => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
        ]);

        $bankAccount = auth()->user()->bankAccounts()->create([
            'payment_method' => $request->payment_method,
            'account_number' => $request->account_number,
            'account_holder_name' => $request->account_holder_name,
            'is_primary' => auth()->user()->bankAccounts()->count() === 0,
        ]);

        return redirect()
            ->route('user.bank-accounts.index')
            ->with('success', 'Bank account added successfully');
    }

    /**
     * Delete bank account
     */
    public function destroy(UserBankAccount $bankAccount)
    {
        // Check if owns this account
        if ($bankAccount->user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized');
        }

        $bankAccount->delete();

        return back()->with('success', 'Bank account deleted successfully');
    }

    /**
     * Set primary account
     */
    public function setPrimary(UserBankAccount $bankAccount)
    {
        if ($bankAccount->user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized');
        }

        $bankAccount->makePrimary();

        return back()->with('success', 'Primary account updated');
    }
}

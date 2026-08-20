<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user()->fresh();

        $transactions = $user->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalCredits = $user->transactions()
            ->where('type', 'credit')
            ->where('status', 'settled')
            ->sum('amount');

        $totalDebits = $user->transactions()
            ->where('type', 'debit')
            ->where('status', 'settled')
            ->sum('amount');

        $totalWithdrawn = $user->withdrawals()
            ->where('payout_status', 'completed')
            ->sum('requested_coins');

        return view('profile.index', compact(
            'user',
            'transactions',
            'totalCredits',
            'totalDebits',
            'totalWithdrawn'
        ));
    }

    public function update(Request $request)
    {
        $user = Auth::user()->fresh();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user->update(['name' => $request->name]);

        return back()->with('success', 'Profile updated successfully!');
    }
}

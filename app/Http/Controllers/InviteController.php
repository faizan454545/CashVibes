<?php

namespace App\Http\Controllers;

use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    public function __construct(
        private ReferralService $referralService
    ) {}

    public function index()
    {
        $user = Auth::user()->fresh();

        $referralsMade = $user->referralsMade()
            ->with('referee')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalInvites = $referralsMade->count();

        $totalEarned = $user->transactions()
            ->where('source_ref', 'REFERRAL_BONUS')
            ->where('type', 'credit')
            ->sum('amount');

        $recentReferrals = $referralsMade->take(5);

        return view('invite.index', compact(
            'user',
            'referralsMade',
            'totalInvites',
            'totalEarned',
            'recentReferrals'
        ));
    }

    public function applyCode(Request $request)
    {
        $request->validate([
            'referral_code' => 'required|string',
        ]);

        $user = Auth::user();

        if ($user->referred_by) {
            return back()->withErrors(['referral_code' => 'You have already applied a referral code.']);
        }

        $success = $this->referralService->applyReferral($user, $request->referral_code);

        if (! $success) {
            return back()->withErrors(['referral_code' => 'Invalid referral code.']);
        }

        return back()->with('success', 'Referral code applied successfully!');
    }

    public function getReferralLink()
    {
        $user = Auth::user();
        $link = url('/register?ref='.$user->referral_code);

        return response()->json(['link' => $link]);
    }
}

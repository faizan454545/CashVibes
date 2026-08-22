<?php

use App\Http\Controllers\AdminContactController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\AdminTaskController;
use App\Http\Controllers\AdminWithdrawalController;
use App\Http\Controllers\Auth\EmailLoginController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\BitLabsPostbackController;
use App\Http\Controllers\CpxPostbackController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EarnController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\LegalPagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SurveysController;
use App\Http\Controllers\TimeWallPostbackController;
use App\Http\Controllers\UserTaskClaimController;
use App\Http\Controllers\WithdrawController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
Route::post('/auth/firebase/callback', [GoogleAuthController::class, 'handleFirebaseCallback'])->name('firebase.callback');
Route::post('/auth/email/login', [EmailLoginController::class, 'login'])->name('email.login');
Route::post('/auth/email/register', [EmailLoginController::class, 'register'])->name('email.register');
Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

// Legal Pages (public)
Route::get('/privacy-policy', [LegalPagesController::class, 'privacy'])->name('legal.privacy');
Route::get('/terms', [LegalPagesController::class, 'terms'])->name('legal.terms');
Route::get('/faq', [LegalPagesController::class, 'faq'])->name('legal.faq');
Route::get('/contact', [LegalPagesController::class, 'contact'])->name('legal.contact');
Route::post('/contact', [LegalPagesController::class, 'submitContact'])->name('legal.contact.submit');

// Protected Routes
Route::middleware(['auth', 'check.account.status'])->group(function () {

    // Unified user status + balance check (AJAX polling)
    Route::get('/api/ban-status', function () {
        $user = auth()->user()->fresh();

        $coins = (float) $user->coin_balance;
        $pkrValue = round($coins * config('app.coin_value_pkr'), 2);

        return response()->json([
            'user_id' => (int) $user->id,
            'email' => $user->email,
            'coins' => $coins,
            'formatted_coins' => number_format($coins, 2),
            'pkr_value' => number_format($pkrValue, 2),
            'is_banned' => $user->account_status === 'suspended',
            'ban_reason' => $user->ban_reason ?: 'Violation of Terms of Service',
        ]);
    })->name('api.ban-status');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Earn
    Route::get('/earn', [EarnController::class, 'index'])->name('earn');
    Route::post('/earn/complete-task', [EarnController::class, 'completeTask'])->name('earn.complete-task');

    // Invite & Referrals
    Route::get('/invite', [InviteController::class, 'index'])->name('invite');
    Route::post('/invite/apply-code', [InviteController::class, 'applyCode'])->name('invite.apply-code');
    Route::get('/invite/referral-link', [InviteController::class, 'getReferralLink'])->name('invite.link');

    // Withdraw
    Route::get('/withdraw', [WithdrawController::class, 'index'])->name('withdraw');
    Route::post('/withdraw', [WithdrawController::class, 'store'])->name('withdraw.store');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Surveys (CPX Research)
    Route::get('/surveys', [SurveysController::class, 'index'])->name('surveys');

    // BitLabs Surveys
    Route::get('/surveys/bitlabs', [SurveysController::class, 'bitlabs'])->name('surveys.bitlabs');

    // Custom Tasks (User)
    Route::get('/task/{task}/visit', [UserTaskClaimController::class, 'visit'])->name('task.visit');
    Route::post('/task/{task}/claim', [UserTaskClaimController::class, 'claim'])->name('task.claim');
});

// CPX Research Postback (server-to-server, no auth required)
Route::get('/api/postback/cpx', [CpxPostbackController::class, 'handle'])->name('cpx.postback');

// TimeWall Postback (server-to-server, no auth required)
Route::get('/api/postback/timewall', [TimeWallPostbackController::class, 'handle'])->name('timewall.postback');

// BitLabs Postback (server-to-server, no auth required)
Route::get('/api/postback/bitlabs', [BitLabsPostbackController::class, 'handle'])->name('bitlabs.postback');

// ========================================
// ADMIN ROUTES
// ========================================
Route::prefix('admin')->name('admin.')->group(function () {

    // Admin Login (no auth required)
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware(['auth:admin', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Admin Settings
        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
        Route::put('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');

        // Task Management
        Route::get('/tasks', [AdminTaskController::class, 'index'])->name('tasks');
        Route::post('/tasks', [AdminTaskController::class, 'store'])->name('tasks.store');
        Route::put('/tasks/{task}', [AdminTaskController::class, 'update'])->name('tasks.update');
        Route::patch('/tasks/{task}/toggle', [AdminTaskController::class, 'toggle'])->name('tasks.toggle');
        Route::delete('/tasks/{task}', [AdminTaskController::class, 'destroy'])->name('tasks.destroy');

        // User Management
        Route::patch('/user/{user}/toggle', [AdminDashboardController::class, 'toggleUser'])->name('user.toggle');
        Route::patch('/user/{user}/update-coins', [AdminDashboardController::class, 'updateCoins'])->name('user.update-coins');

        // Withdrawal Management
        Route::get('/withdrawals', [AdminWithdrawalController::class, 'index'])->name('withdrawals');
        Route::patch('/withdrawals/{withdrawal}/complete', [AdminWithdrawalController::class, 'complete'])->name('withdrawals.complete');
        Route::patch('/withdrawals/{withdrawal}/reject', [AdminWithdrawalController::class, 'reject'])->name('withdrawals.reject');

        // Contact Messages
        Route::get('/messages', [AdminContactController::class, 'index'])->name('messages');
        Route::delete('/messages/{message}', [AdminContactController::class, 'destroy'])->name('messages.destroy');
    });
});

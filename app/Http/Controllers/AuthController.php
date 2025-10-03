<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    // ログインフォーム表示
    public function showLogin(): View
    {
        return view('bkc.home');
    }

    // ログイン処理
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            // 最終ログイン時刻とIPを更新
            Auth::user()->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);
            return redirect()->intended('/my'); // マイページへリダイレクト
        }

        return back()->withErrors([
            'login_id' => '入力された認証情報が記録と一致しません。',
        ])->onlyInput('login_id');
    }

    // ユーザー登録フォーム表示
    public function showRegister(): View
    {
        return view('auth.register');
    }

    // 登録処理
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'login_id' => ['required', 'string', 'max:120', 'unique:' . User::class],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'login_id' => $request->login_id,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'password_algo' => 'bcrypt', // 使用するハッシュアルゴリズム
            'role' => 'user', // デフォルトロール
            'is_active' => 1,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/my'); // マイページへリダイレクト
    }

    // パスワード忘れた方フォーム表示
    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    // パスワードリセットリンク送信
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }

    // 新パスワード設定フォーム表示
    public function showResetPassword(string $token): View
    {
        return view('auth.reset-password', ['token' => $token, 'email' => request()->query('email')]);
    }

    // パスワード更新処理
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password_hash' => Hash::make($request->password),
                    'password_algo' => 'bcrypt',
                    'remember_token' => \Illuminate\Support\Str::random(60),
                ])->save();

                event(new \Illuminate\Auth\Events\PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }

    // ログアウト処理
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}

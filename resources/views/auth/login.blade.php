<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name') }} | ログイン</title>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
  <style>
    :root {
      --primary: #00a000;
      --primary-dark: #008000;
      --bg: #f8fdf8;
      --card: #ffffff;
      --line: #e0f0e0;
      --ink: #1a3a1a;
      --muted: #6b8e6b;
      --blur: 12px;
    }

    body {
      margin: 0;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-container {
      width: 100%;
      max-width: 420px;
      padding: 20px;
    }

    .login-card {
      background: var(--card);
      border-radius: 16px;
      box-shadow: 0 8px 32px rgba(0, 160, 0, 0.12);
      padding: 40px;
      border: 1px solid var(--line);
    }

    .brand {
      text-align: center;
      margin-bottom: 32px;
    }

    .brand h1 {
      font-size: 28px;
      font-weight: 700;
      color: var(--primary);
      margin: 0 0 8px 0;
    }

    .brand p {
      font-size: 14px;
      color: var(--muted);
      margin: 0;
    }

    .field {
      margin-bottom: 20px;
    }

    .field label {
      display: block;
      font-size: 14px;
      font-weight: 500;
      color: var(--ink);
      margin-bottom: 6px;
    }

    .field input[type="email"],
    .field input[type="password"],
    .field input[type="text"] {
      width: 100%;
      padding: 12px 14px;
      border: 1px solid var(--line);
      border-radius: 8px;
      font-size: 15px;
      transition: all 0.2s ease;
      box-sizing: border-box;
    }

    .field input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(0, 160, 0, 0.1);
    }

    .error {
      color: #dc2626;
      font-size: 13px;
      margin-top: 4px;
    }

    .checkbox-field {
      display: flex;
      align-items: center;
      margin: 20px 0;
    }

    .checkbox-field input[type="checkbox"] {
      width: 18px;
      height: 18px;
      margin-right: 8px;
      cursor: pointer;
      accent-color: var(--primary);
    }

    .checkbox-field label {
      font-size: 14px;
      color: var(--ink);
      cursor: pointer;
      margin: 0;
    }

    .btn {
      width: 100%;
      padding: 14px;
      background: var(--primary);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s ease;
      box-shadow: 0 2px 8px rgba(0, 160, 0, 0.2);
    }

    .btn:hover {
      background: var(--primary-dark);
      box-shadow: 0 4px 12px rgba(0, 160, 0, 0.3);
      transform: translateY(-1px);
    }

    .btn:active {
      transform: translateY(0);
    }

    .links {
      margin-top: 20px;
      text-align: center;
    }

    .links a {
      color: var(--primary);
      text-decoration: none;
      font-size: 14px;
      transition: color 0.2s ease;
    }

    .links a:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }

    .alert {
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 14px;
    }

    .alert-success {
      background: #d1fae5;
      color: #065f46;
      border: 1px solid #a7f3d0;
    }

    .alert-error {
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #fecaca;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="brand">
        <h1>BKCアプリ</h1>
        <p>ログインして続ける</p>
      </div>

      <!-- Session Status -->
      @if (session('status'))
        <div class="alert alert-success">
          {{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="field">
          <label for="email">メールアドレス</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
          @error('email')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>

        <!-- Password -->
        <div class="field">
          <label for="password">パスワード</label>
          <input id="password" type="password" name="password" required autocomplete="current-password" />
          @error('password')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>

        <!-- Remember Me -->
        <div class="checkbox-field">
          <input id="remember_me" type="checkbox" name="remember">
          <label for="remember_me">ログイン状態を保持</label>
        </div>

        <button type="submit" class="btn">ログイン</button>

        <div class="links">
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">パスワードをお忘れですか？</a>
          @endif
        </div>
      </form>
    </div>
  </div>
</body>
</html>

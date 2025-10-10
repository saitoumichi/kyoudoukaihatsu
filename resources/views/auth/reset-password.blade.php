<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>BKC生のためのアプリ – 新しいパスワード設定</title>
  <style>
    :root {
      --bg: #f7f9fc;
      --card: #ffffff;
      --ink: #0f172a;
      --muted: #64748b;
      --line: #e5e7eb;
      --primary: #00a000;
      --accent: #a78bfa;
      --pink: #00a000;
      --green: #10b981;
      --amber: #f59e0b;
      --rose: #f43f5e;
    }

    * { box-sizing: border-box; }
    html, body { height: 100%; }

    body {
      margin: 0;
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Hiragino Kaku Gothic ProN", "Noto Sans JP", "Yu Gothic", "Meiryo", sans-serif;
      color: var(--ink);
      background: linear-gradient(180deg, #ffffff 0%, var(--bg) 100%);
      line-height: 1.6;
      overflow-x: hidden;
    }

    #bg { position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 1; transition: opacity .25s ease;
      background:
        radial-gradient(1200px 800px at 50% -10%, rgba(147, 197, 253, 0.45), transparent 60%),
        radial-gradient(900px 600px at 100% 20%, rgba(196, 181, 253, 0.38), transparent 60%),
        radial-gradient(700px 600px at 0% 80%, rgba(110, 231, 183, 0.28), transparent 60%),
        radial-gradient(800px 500px at 50% 110%, rgba(59, 130, 246, 0.15), transparent 60%),
        linear-gradient(180deg, #e0f2fe 0%, #dbeafe 50%, #f5f3ff 100%);
    }

    #app { position: relative; z-index: 1; min-height: 100dvh;
      --ink:#0f172a; --card: rgba(255,255,255,.92); --line: rgba(15,23,42,.12); --muted: #64748b;
    }

    /* Sakura theme */
    #app[data-skin="sakura"]{
      --ink:#eaf1ff;
      --muted:#9fb0c6;
      --line:rgba(255,255,255,.10);
      --card:rgba(8,12,20,.52);
      --primary:#00a000;
      color:var(--ink);
    }

    body:has(#app[data-skin="sakura"]) #bg{
      background:
        radial-gradient(1200px 800px at 50% -20%, rgba(0,160,0,.18), transparent 60%),
        radial-gradient(900px 600px at 0% 30%,   rgba(0,200,0,.18), transparent 60%),
        radial-gradient(900px 600px at 100% 70%, rgba(0,180,0,.14), transparent 60%),
        linear-gradient(180deg, #0b0f18 0%, #0a1420 50%, #08121c 100%) !important;
    }

    .container { max-width: 1200px; margin: 0 auto; padding: 0 16px; }
    .row { display: flex; align-items: center; gap: 12px; }

    header { padding: 20px 0; border-bottom: 1px solid var(--line); }
    .brand { font-size: 24px; font-weight: 700; color: var(--primary); }
    .brand span { color: var(--muted); font-weight: 400; }

    main { padding: 40px 0; }
    .h1 { font-size: 32px; font-weight: 700; margin: 0 0 8px 0; color: var(--ink); }
    .sub { color: var(--muted); margin-bottom: 32px; }

    .card { background: var(--card); border-radius: 16px; padding: 32px; backdrop-filter: blur(var(--blur, 0px)); border: 1px solid var(--line); }
    .title { font-size: 20px; font-weight: 600; margin-bottom: 24px; color: var(--ink); }

    .field { margin-bottom: 20px; }
    .field label { display: block; font-weight: 500; margin-bottom: 8px; color: var(--ink); }
    .field input, .field textarea { width: 100%; padding: 12px 16px; border: 1px solid var(--line); border-radius: 8px; background: var(--card); color: var(--ink); font-size: 14px; }
    .field input:focus, .field textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }

    .btn { display: inline-flex; align-items: center; padding: 12px 24px; border-radius: 8px; font-weight: 500; text-decoration: none; border: 1px solid var(--primary); background: var(--primary); color: white; cursor: pointer; transition: all 0.2s; }
    .btn:hover { opacity: 0.9; transform: translateY(-1px); }
    .btn.secondary { background: transparent; color: var(--primary); }
    .btn.secondary:hover { background: var(--primary); color: white; }

    .btn-row { display: flex; gap: 12px; align-items: center; margin-top: 24px; }
    .hint { color: var(--muted); font-size: 12px; margin-top: 8px; }
    .hint a { color: var(--primary); text-decoration: underline; }

    .alert { padding: 16px; border-radius: 8px; margin-bottom: 24px; }
    .alert.success { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #10b981; }
    .alert.error { background: rgba(244, 63, 94, 0.1); border: 1px solid rgba(244, 63, 94, 0.3); color: #f43f5e; }

    .error { color: #f43f5e; font-size: 12px; margin-top: 4px; }
  </style>
</head>
<body>
  <div id="bg" aria-hidden="true"></div>
  <div id="app" data-skin="sakura">
    <header>
      <div class="container">
        <div class="row" style="justify-content: space-between;">
          <div class="row"><div class="brand">BKC<span>アプリ</span></div></div>
          <nav style="display:flex; gap:8px;">
            <a href="/login" class="btn secondary">ログインに戻る</a>
          </nav>
        </div>
      </div>
    </header>

    <main>
      <div class="container" style="max-width: 500px;">
        <h1 class="h1">新しいパスワード設定</h1>
        <p class="sub">新しいパスワードを設定してください。</p>

        <div class="card">
          <div class="title">パスワードを入力してください</div>
          <form method="POST" action="/reset-password/{{ $token }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="field">
              <label for="email">メールアドレス</label>
              <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required autofocus placeholder="you@example.com" />
              @if ($errors->get('email'))
                <div class="error">{{ $errors->get('email')[0] }}</div>
              @endif
            </div>

            <div class="field">
              <label for="password">新しいパスワード</label>
              <input type="password" id="password" name="password" required placeholder="••••••••" />
              @if ($errors->get('password'))
                <div class="error">{{ $errors->get('password')[0] }}</div>
              @endif
            </div>

            <div class="field">
              <label for="password_confirmation">パスワード確認</label>
              <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••" />
              @if ($errors->get('password_confirmation'))
                <div class="error">{{ $errors->get('password_confirmation')[0] }}</div>
              @endif
            </div>

            <div class="btn-row">
              <button type="submit" class="btn">パスワードを更新</button>
            </div>
          </form>

          <div class="hint">
            <a href="/login">ログインページに戻る</a>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>

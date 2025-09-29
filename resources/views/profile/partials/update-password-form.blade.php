<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <div class="mt-6">
        <div class="card" style="margin-top:14px; max-width:720px;">
            <div class="title">パスワード変更</div>
            <form id="form-password" method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')
                
                <div class="field">
                    <label for="pw-current">現在のパスワード</label>
                    <input id="pw-current" type="password" placeholder="現在のパスワード"
                           name="current_password" autocomplete="current-password" required />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>
                <div class="field">
                    <label for="pw-new">新しいパスワード</label>
                    <input id="pw-new" type="password" placeholder="8文字以上"
                           name="password" minlength="8" autocomplete="new-password" required />
                    <div class="hint">英数字・記号を混ぜると安全性が上がります。</div>
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>
                <div class="field">
                    <label for="pw-new-confirm">新しいパスワード（確認）</label>
                    <input id="pw-new-confirm" type="password" placeholder="もう一度入力"
                           name="password_confirmation" minlength="8" autocomplete="new-password" required />
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>
                <div class="btn-row">
                    <button type="submit" class="btn primary">更新する</button>
                </div>
                
                @if (session('status') === 'password-updated')
                    <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ __('Saved.') }}
                    </div>
                @endif
            </form>
        </div>
    </div>
</section>

<style>
    :root {
        --bg: #f7f9fc;
        --card: #ffffff;
        --ink: #0f172a;
        --muted: #64748b;
        --line: #e5e7eb;
        --primary: #2563eb;
        --accent: #a78bfa;
        --pink: #f472b6;
        --green: #10b981;
        --amber: #f59e0b;
        --rose: #f43f5e;
    }

    .card { 
        background: var(--card); 
        border: 1px solid var(--line); 
        border-radius: 16px; 
        padding: 14px; 
        box-shadow: 0 8px 28px rgba(15,23,42,0.08);
        backdrop-filter: blur(10px) saturate(1.05);
        -webkit-backdrop-filter: blur(10px) saturate(1.05);
    }
    .card .title { font-weight: 700; margin: 2px 0 6px; }
    .meta { color: var(--muted); font-size: 13px; }

    .btn { 
        display:inline-block; 
        padding:10px 14px; 
        border-radius: 12px; 
        border: 1px solid var(--line); 
        background: #fff; 
        text-decoration:none; 
        color:var(--ink); 
        font-weight:700; 
        cursor: pointer;
    }
    .btn.primary { 
        background: var(--primary); 
        color: #fff; 
        border-color: transparent; 
    }
    .btn-row { 
        display:flex; 
        gap:10px; 
        flex-wrap: wrap; 
    }

    form .field { 
        display:grid; 
        gap:6px; 
        margin-bottom:12px; 
    }
    form input[type="text"],
    form input[type="email"],
    form input[type="password"],
    form input[type="url"],
    form input[type="number"],
    form input[type="file"],
    form textarea,
    form select {
        width: 100%;
        padding: 12px 12px;
        border-radius: 12px;
        border: 1px solid var(--line);
        background: #fff;
        font-size: 14px;
    }
    .hint { color: var(--muted); font-size: 12px; }
</style>

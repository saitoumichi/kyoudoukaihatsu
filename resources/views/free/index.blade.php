<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>フリマ商品一覧</title>
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

        /* 桜背景 */
        #bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            opacity: 1;
            transition: opacity .25s ease;
            background:
                radial-gradient(1200px 800px at 50% -20%, rgba(255,106,169,.18), transparent 60%),
                radial-gradient(900px 600px at 0% 30%, rgba(255,193,220,.18), transparent 60%),
                radial-gradient(900px 600px at 100% 70%, rgba(255,142,187,.14), transparent 60%),
                linear-gradient(180deg, #0b0f18 0%, #0a1420 50%, #08121c 100%);
        }

        #bg::before {
            content: "";
            position: absolute;
            inset: 0;
            background-repeat: no-repeat;
            animation: twinkle 6s ease-in-out infinite alternate;
            background-image:
                radial-gradient(1.4px 1.4px at 7% 12%, rgba(255,255,255,.95) 52%, transparent 53%),
                radial-gradient(1.2px 1.2px at 18% 34%, rgba(255,255,255,.85) 52%, transparent 53%),
                radial-gradient(1.2px 1.2px at 29% 72%, rgba(255,255,255,.9) 52%, transparent 53%),
                radial-gradient(1.2px 1.2px at 41% 22%, rgba(255,255,255,.82) 52%, transparent 53%),
                radial-gradient(1.2px 1.2px at 53% 68%, rgba(255,255,255,.85) 52%, transparent 53%),
                radial-gradient(1.2px 1.2px at 66% 18%, rgba(255,255,255,.9) 52%, transparent 53%),
                radial-gradient(1.2px 1.2px at 73% 56%, rgba(255,255,255,.86) 52%, transparent 53%),
                radial-gradient(1.2px 1.2px at 82% 84%, rgba(255,255,255,.9) 52%, transparent 53%),
                radial-gradient(1.2px 1.2px at 91% 28%, rgba(255,255,255,.95) 52%, transparent 53%),
                radial-gradient(1.2px 1.2px at 12% 88%, rgba(255,255,255,.85) 52%, transparent 53%);
            filter: drop-shadow(0 0 2px rgba(255,255,255,.28));
            opacity: .6;
        }

        #bg::after {
            content: "";
            position: absolute;
            inset: 0;
            mix-blend-mode: screen;
            filter: saturate(1.03);
            background:
                radial-gradient(260px 200px at 16% 78%, rgba(255,106,169,.22), transparent 60%),
                radial-gradient(320px 220px at 78% 18%, rgba(255,193,220,.20), transparent 60%),
                radial-gradient(220px 220px at 80% 86%, rgba(255,142,187,.16), transparent 60%),
                radial-gradient(100% 100% at 50% 100%, rgba(255,255,255,.10), transparent 45%);
            opacity: .9;
        }

        @keyframes twinkle {
            0%   { opacity:.65; transform: translateY(0) scale(1); }
            50%  { opacity:1;   transform: translateY(-.25px) scale(1.02); }
            100% { opacity:.65; transform: translateY(-.5px) scale(1); }
        }

        #app {
            position: relative;
            z-index: 1;
            min-height: 100dvh;
            --ink:#eaf1ff;
            --muted:#9fb0c6;
            --line:rgba(255,255,255,.10);
            --card:rgba(8,12,20,.52);
            --card-strong:rgba(8,12,20,.66);
            --blur:12px;
            --theme-0:#ff6aa9;
            --theme-1:#ffc1dc;
            --theme-2:#ffe4ef;
            --primary:var(--theme-0);
            color:var(--ink);
        }

        /* ヘッダー */
        header {
            position: sticky;
            top: 0;
            z-index: 10;
            backdrop-filter: blur(12px) saturate(1.1);
            background: var(--card-strong);
            border-bottom: 1px solid rgba(255,255,255,.08);
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.04), 0 4px 18px rgba(0,0,0,.35);
        }

        .container { max-width: 1120px; margin: 0 auto; padding: 14px 20px; }
        .row { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
        .brand { font-weight: 800; letter-spacing: .5px; color: var(--ink); }
        .brand span { color: var(--primary); }

        /* Tabs */
        .tabs { display: flex; gap: 6px; flex-wrap: wrap; }
        .tabs label,
        .tabs .tabs-link {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 12px; border-radius: 999px; cursor: pointer;
            border: 1px solid rgba(255,255,255,.10); color: #ffe4ef; text-decoration: none;
            background: rgba(12,18,30,.56);
            transition: box-shadow .2s ease, transform .05s ease;
            user-select: none;
            box-shadow:
                inset 0 0 0 1px rgba(255,255,255,.04),
                0 0 0 2px rgba(255,152,177,.08);
        }
        .tabs label:hover,
        .tabs .tabs-link:hover {
            box-shadow:
                inset 0 0 0 2px rgba(255,152,177,.16),
                0 6px 18px rgba(0,0,0,.35);
        }
        .tabs .tabs-link[data-color="blue"]{
            background: rgba(59,130,246,.2);
            border-color: rgba(59,130,246,.3);
            color: #dbeafe;
        }
        .tabs .tabs-link[data-color="violet"]{
            background: rgba(139,92,246,.2);
            border-color: rgba(139,92,246,.3);
            color: #e9d5ff;
        }
        .tabs .tabs-link[data-color="rose"]{
            background: rgba(244,63,94,.2);
            border-color: rgba(244,63,94,.3);
            color: #fecaca;
        }
        .tabs .tabs-link[data-color="amber"]{
            background: rgba(245,158,11,.2);
            border-color: rgba(245,158,11,.3);
            color: #fde68a;
        }
        .tabs .tabs-link[data-color="green"]{
            background: rgba(34,197,94,.2);
            border-color: rgba(34,197,94,.3);
            color: #bbf7d0;
        }

        /* カード */
        .card {
            background: var(--card);
            border: 1px solid rgba(255,255,255,.06);
            color: var(--ink);
            backdrop-filter: blur(12px) saturate(1.05);
            -webkit-backdrop-filter: blur(12px) saturate(1.05);
            box-shadow:
                inset 0 0 0 1px rgba(255,255,255,.04),
                0 1px 0 rgba(255,255,255,.05),
                0 8px 30px rgba(0,0,0,.45),
                0 0 0 1.5px rgba(255,122,150,.08),
                0 0 22px 2px rgba(255,122,150,.10);
        }

        /* ボタン */
        .btn {
            display: inline-block;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,.10);
            background: rgba(12,18,30,.6);
            text-decoration: none;
            color: var(--ink);
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .btn.primary {
            background: var(--primary);
            color: #0b0f18;
            border-color: transparent;
            box-shadow: 0 8px 26px color-mix(in oklab, var(--primary) 35%, black);
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(0,0,0,.35);
        }

        /* フォーム要素 */
        input, select, textarea {
            background: rgba(10,16,26,.66);
            color: var(--ink);
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 8px;
            padding: 8px 12px;
        }

        /* その他のスタイル */
        .h1 { font-size: clamp(24px, 3.2vw, 32px); font-weight: 800; letter-spacing: .3px; margin: 0 0 12px 0; }
        .sub { color: var(--muted); font-size: 16px; margin-bottom: 32px; line-height: 1.5; }
        .grid { display: grid; gap: 20px; }
        .grid.cards { grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px; }
        .meta { color: var(--muted); font-size: 14px; line-height: 1.4; }
        .pill { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; background:#f1f5f9; border:1px solid var(--line); font-size:13px; font-weight: 500; }
        .title { font-size: 18px; font-weight: 700; margin: 0 0 8px 0; line-height: 1.3; }

        /* カードの改善 */
        .card {
            padding: 20px;
            border-radius: 20px;
            transition: all 0.3s ease;
            margin-bottom: 0;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow:
                inset 0 0 0 1px rgba(255,255,255,.06),
                0 1px 0 rgba(255,255,255,.08),
                0 12px 40px rgba(0,0,0,.55),
                0 0 0 2px rgba(255,122,150,.12),
                0 0 30px 3px rgba(255,122,150,.15);
        }

        /* セクション間のスペーシング */
        .section { margin-bottom: 40px; }
        .section-sm { margin-bottom: 24px; }

        /* フォームの改善 */
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .form-group { margin-bottom: 0; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: var(--ink); font-size: 14px; }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255,106,169,.2);
        }

        /* ボタングループ */
        .btn-group { display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px; }

        /* レスポンシブ改善 */
        @media (max-width: 768px) {
            .grid.cards { grid-template-columns: 1fr; gap: 20px; }
            .form-grid { grid-template-columns: 1fr; gap: 16px; }
            .btn-group { flex-direction: column; }
            .btn-group .btn { width: 100%; text-align: center; }
        }

        @media (prefers-reduced-motion: reduce) {
            #bg::before { animation: none; }
        }
    </style>
</head>
<body>
    <div id="bg" aria-hidden="true"></div>

    <div id="app" data-skin="sakura">
        @include('components.header')

        <main style="max-width: 1200px; margin: 32px auto 120px; padding: 0 24px;">
            <div class="section">
                <h1 class="h1">フリマ商品一覧</h1>
                <p class="sub">カテゴリ別に商品を探してみましょう。お気に入りの商品を見つけてください。</p>
            </div>

            <!-- 検索・フィルタフォーム -->
            <div class="section">
                <div class="card">
                    <form method="GET" action="{{ route('free.index') }}">
                        <div class="form-grid">
                            <!-- 検索 -->
                            <div class="form-group">
                                <label for="search">🔍 検索</label>
                                <input id="search" type="text" name="search"
                                    value="{{ request('search') }}" placeholder="商品名、説明で検索" />
                            </div>

                            <!-- カテゴリ選択 -->
                            <div class="form-group">
                                <label for="category">📂 カテゴリ</label>
                                <select id="category" name="category">
                                    <option value="">すべてのカテゴリ</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                            {{ ucfirst($category) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- 最低価格 -->
                            <div class="form-group">
                                <label for="price_min">💰 最低価格</label>
                                <input id="price_min" type="number" name="price_min"
                                    value="{{ request('price_min') }}" placeholder="0" min="0" />
                            </div>

                            <!-- 最高価格 -->
                            <div class="form-group">
                                <label for="price_max">💴 最高価格</label>
                                <input id="price_max" type="number" name="price_max"
                                    value="{{ request('price_max') }}" placeholder="上限なし" min="0" />
                            </div>

                            <!-- ソート -->
                            <div class="form-group">
                                <label for="sort">🔄 並び順</label>
                                <select id="sort" name="sort">
                                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>新着順</option>
                                    <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>価格の安い順</option>
                                    <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>価格の高い順</option>
                                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>名前順</option>
                                </select>
                            </div>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn" onclick="window.location.href='{{ route('free.index') }}'">リセット</button>
                            <button type="submit" class="btn primary">検索する</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- アクションボタン -->
            @auth
                <div class="section-sm">
                    <a href="{{ route('my.free.create') }}" class="btn primary" style="font-size: 16px; padding: 14px 24px;">
                        ✨ 商品を出品する
                    </a>
                </div>
            @endauth

            <!-- 商品一覧 -->
            <div class="section">
                <div class="grid cards">
                @forelse($items as $item)
                <div class="card">
                    <!-- 商品画像 -->
                    @if($item->image_url)
                        <div style="margin: -20px -20px 20px -20px; border-radius: 20px 20px 0 0; overflow: hidden;">
                            <img src="{{ $item->image_url }}" alt="{{ $item->title }}"
                                 style="width: 100%; height: 200px; object-fit: cover;">
                        </div>
                    @else
                        <div style="margin: -20px -20px 20px -20px; height: 200px; background: linear-gradient(135deg, rgba(255,106,169,.1), rgba(255,193,220,.1)); display: flex; align-items: center; justify-content: center; border-radius: 20px 20px 0 0;">
                            <span style="color: var(--muted); font-size: 14px;">📷 画像なし</span>
                        </div>
                    @endif

                    <!-- バッジ（カテゴリと状態） -->
                    <div style="margin-bottom: 12px; display: flex; gap: 8px; flex-wrap: wrap;">
                        <span class="pill" style="background: rgba(255,106,169,.2); border-color: rgba(255,106,169,.3); color: #ffc1dc;">
                            {{ ucfirst($item->category) }}
                        </span>
                        
                        @if($item->condition == 'new')
                          <span class="pill" style="background: rgba(34,197,94,.2); border-color: rgba(34,197,94,.3); color: #bbf7d0;">
                            ✨ 新品
                          </span>
                        @elseif($item->condition == 'like_new')
                          <span class="pill" style="background: rgba(59,130,246,.2); border-color: rgba(59,130,246,.3); color: #dbeafe;">
                            ⭐ ほぼ新品
                          </span>
                        @elseif($item->condition == 'good')
                          <span class="pill" style="background: rgba(245,158,11,.2); border-color: rgba(245,158,11,.3); color: #fde68a;">
                            👍 良い
                          </span>
                        @else
                          <span class="pill" style="background: rgba(100,116,139,.2); border-color: rgba(100,116,139,.3); color: #cbd5e1;">
                            📦 普通
                          </span>
                        @endif
                    </div>

                    <!-- 商品名 -->
                    <h3 class="title">
                        <a href="{{ route('free.show', $item->id) }}" style="color: var(--ink); text-decoration: none; transition: color 0.2s ease;">
                            {{ $item->title }}
                        </a>
                    </h3>

                    <!-- 価格 -->
                    <div style="font-size: 20px; font-weight: bold; color: var(--primary); margin: 8px 0 12px 0;">
                        ¥{{ number_format($item->price) }}
                    </div>

                    <!-- 説明 -->
                    @if($item->description)
                        <p class="meta" style="line-height: 1.5; margin-bottom: 16px;">
                            {{ Str::limit($item->description, 120) }}
                        </p>
                    @endif

                    <!-- 出品者情報 -->
                    <div class="meta" style="margin-bottom: 8px;">
                        👤 {{ $item->user->login_id ?? '不明' }}
                    </div>

                    <!-- 出品日 -->
                    <div class="meta" style="margin-bottom: 20px;">
                        📅 {{ $item->created_at->format('Y年m月d日') }}
                    </div>

                    <!-- アクション -->
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 1px solid rgba(255,255,255,.1);">
                        <a href="{{ route('free.show', $item->id) }}" class="btn" style="padding: 8px 16px; font-size: 13px;">
                            詳細を見る
                        </a>

                        @auth
                            @if($item->user_id === auth()->id())
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('my.free.messages', $item->id) }}" class="btn" style="padding: 8px 12px; font-size: 12px; background: rgba(59,130,246,.2); border-color: rgba(59,130,246,.3); color: #dbeafe;">
                                        💬 DM
                                    </a>
                                    <a href="{{ route('my.free.edit', $item->id) }}" class="btn" style="padding: 8px 12px; font-size: 12px; background: rgba(34,197,94,.2); border-color: rgba(34,197,94,.3); color: #bbf7d0;">
                                        編集
                                    </a>
                                    <form method="POST" action="{{ route('my.free.destroy', $item->id) }}"
                                          style="display: inline;" onsubmit="return confirm('削除しますか？')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn" style="padding: 8px 12px; font-size: 12px; background: rgba(244,63,94,.2); border-color: rgba(244,63,94,.3); color: #fecaca;">
                                            削除
                                        </button>
                                    </form>
                                </div>
                            @else
                                <a href="{{ route('free.dm', $item->id) }}" class="btn primary" style="padding: 8px 16px; font-size: 13px;">
                                    💬 DMする
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn primary" style="padding: 8px 16px; font-size: 13px;">
                                💬 DMする（ログイン必要）
                            </a>
                        @endauth
                    </div>
                </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 48px; border: 2px dashed rgba(255,255,255,.2); border-radius: 16px; background: var(--card);">
                        <p class="meta" style="font-size: 18px;">出品されている商品がありません。</p>
                        @auth
                            <a href="{{ route('my.free.create') }}" class="btn primary" style="margin-top: 16px; display: inline-block;">
                                最初の商品を出品する
                            </a>
                        @endauth
                    </div>
                @endforelse
            </div>
        </main>
    </div>
</body>
</html>

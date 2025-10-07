<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>BKC生のためのアプリ – {{ $place->name }}</title>
  <style>
    :root {
      --bg: #f7f9fc;
      --card: #ffffff;
      --ink: #0f172a;
      --muted: #64748b;
      --line: #e5e7eb;
      --primary: #2563eb; /* blue */
      --accent: #a78bfa;  /* violet */
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

    /* ===== 実行プレビュー切替のための土台 ===== */
    #bgprev { display: none; }
    #bg { position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 1; transition: opacity .25s ease;
      background:
        radial-gradient(1200px 800px at 50% -10%, rgba(147, 197, 253, 0.45), transparent 60%),
        radial-gradient(900px 600px at 100% 20%, rgba(196, 181, 253, 0.38), transparent 60%),
        radial-gradient(700px 600px at 0% 80%, rgba(110, 231, 183, 0.28), transparent 60%),
        radial-gradient(800px 500px at 50% 110%, rgba(59, 130, 246, 0.15), transparent 60%),
        linear-gradient(180deg, #e0f2fe 0%, #dbeafe 50%, #f5f3ff 100%);
    }
    #app { position: relative; z-index: 1; min-height: 100dvh;
      /* Readability-first variables */
      --ink:#0f172a; --card: rgba(255,255,255,.92); --line: rgba(15,23,42,.12); --muted: #64748b;
    }

    /* ★ 青系の層 + 星の瞬き + グロウ（画像なしCSSのみ） */
    #bg::before {
      content: ""; position: absolute; inset: 0; background-repeat: no-repeat;
      /* 強めの瞬き：スピードUP & 明るさUP */
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
      /* きらめき感を上げる軽いグロー */
      filter: drop-shadow(0 0 2px rgba(255,255,255,.28));
      opacity: .6; transition: opacity .25s ease;
    }
    #bgprev:checked ~ #bg::before { opacity: .9; }

    #bg::after { content: ""; position: absolute; inset: 0; mix-blend-mode: screen; filter: saturate(1.03);
      background:
        radial-gradient(260px 200px at 16% 78%, rgba(147, 197, 253, .25), transparent 60%),
        radial-gradient(320px 220px at 78% 18%, rgba(196, 181, 253, .22), transparent 60%),
        radial-gradient(220px 220px at 80% 86%, rgba(110, 231, 183, .18), transparent 60%),
        radial-gradient(100% 100% at 50% 100%, rgba(255,255,255,.18), transparent 40%);
      /* 背景の発光を少し抑えて、テキストの視認性UP */
      opacity: .82; transition: opacity .25s ease;
    }

    @keyframes twinkle {
      0%   { opacity:.65; transform: translateY(0) scale(1); }
      50%  { opacity:1;   transform: translateY(-.25px) scale(1.02); }
      100% { opacity:.65; transform: translateY(-.5px) scale(1); }
    }
@media (prefers-reduced-motion: reduce) { #bg::before { animation: none; } }

    /* ---------- App Shell ---------- */
    header {
      position: sticky; top: 0; z-index: 10;
      backdrop-filter: blur(8px) saturate(1.1);
      background: rgba(255,255,255,.86);
      border-bottom: 1px solid rgba(15,23,42,.08);
      transition: background .2s ease, border-color .2s ease;
    }

    .container { max-width: 1120px; margin: 0 auto; padding: 14px 20px; }
    .row { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
    .brand { font-weight: 800; letter-spacing: .5px; }
    .brand span { color: var(--primary); }

    /* ---------- UI atoms ---------- */
    .h1 { font-size: clamp(20px, 2.8vw, 28px); font-weight: 800; letter-spacing: .3px; margin: 6px 0 8px; }
    .sub { color: var(--muted); font-size: 14px; margin-bottom: 18px; }
    .grid { display: grid; gap: 14px; }
    .card { background: var(--card); border: 1px solid var(--line); border-radius: 16px; padding: 14px; box-shadow: 0 8px 28px rgba(15,23,42,0.08);
    /* 常時ガラスUI（可読性を保つため白ベースは維持） */
    backdrop-filter: blur(10px) saturate(1.05);
    -webkit-backdrop-filter: blur(10px) saturate(1.05);
  }
    .card .title { font-weight: 700; margin: 2px 0 6px; }
    .meta { color: var(--muted); font-size: 13px; }
    .pill { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; background:#f1f5f9; border:1px solid var(--line); font-size:12px; }

    .btn { display:inline-block; padding:10px 14px; border-radius: 12px; border: 1px solid var(--line); background: #fff; text-decoration:none; color:var(--ink); font-weight:700; }
    .btn.primary { background: var(--primary); color: #fff; border-color: transparent; }
    .btn.ghost { background: #fff; }
    .btn.full { width: 100%; text-align: center; }

    main { max-width: 1120px; margin: 18px auto 96px; padding: 0 20px; }

    /* Sakuraテーマ（ダークガラス + 桜色グロー） */
    #app[data-skin="sakura"]{
      /* ベース色も暗色系に上書き（重要） */
      --ink:#eaf1ff;
      --muted:#9fb0c6;
      --line:rgba(255,255,255,.10);
      --card:rgba(8,12,20,.52);
      --card-strong:rgba(8,12,20,.66);
      --blur:12px;

      /* テーマ色 */
      --theme-0:#ff6aa9;
      --theme-1:#ffc1dc;
      --theme-2:#ffe4ef;
      --primary:var(--theme-0);
      color:var(--ink);
    }

    /* #bg が #app の前にあっても効くように :has で背景を更新 */
    body:has(#app[data-skin="sakura"]) #bg{
      background:
        radial-gradient(1200px 800px at 50% -20%, rgba(255,106,169,.18), transparent 60%),
        radial-gradient(900px 600px at 0% 30%,   rgba(255,193,220,.18), transparent 60%),
        radial-gradient(900px 600px at 100% 70%, rgba(255,142,187,.14), transparent 60%),
        linear-gradient(180deg, #0b0f18 0%, #0a1420 50%, #08121c 100%) !important;
    }
    body:has(#app[data-skin="sakura"]) #bg::after{
      background:
        radial-gradient(420px 320px at 18% 78%, rgba(255,106,169,.22), transparent 60%),
        radial-gradient(380px 260px at 80% 22%, rgba(255,193,220,.20), transparent 60%),
        radial-gradient(280px 240px at 78% 86%, rgba(255,142,187,.16), transparent 60%),
        radial-gradient(100% 100% at 50% 100%, rgba(255,255,255,.10), transparent 45%);
      opacity:.9;
    }

    /* コンポーネントのダークガラス上書き */
    #app[data-skin="sakura"] header{
      background:var(--card-strong);
      border-bottom:1px solid rgba(255,255,255,.08);
      backdrop-filter:blur(var(--blur)) saturate(1.1);
      -webkit-backdrop-filter:blur(var(--blur)) saturate(1.1);
      box-shadow:inset 0 0 0 1px rgba(255,255,255,.04), 0 4px 18px rgba(0,0,0,.35);
    }
    #app[data-skin="sakura"] .brand span{ color:var(--primary); }

    #app[data-skin="sakura"] .card{
      background:var(--card);
      border:1px solid rgba(255,255,255,.06);
      color:var(--ink);
      backdrop-filter:blur(var(--blur)) saturate(1.05);
      -webkit-backdrop-filter:blur(var(--blur)) saturate(1.05);
      box-shadow:
        inset 0 0 0 1px rgba(255,255,255,.04),
        0 1px 0 rgba(255,255,255,.05),
        0 8px 30px rgba(0,0,0,.45),
        0 0 0 1.5px rgba(255,122,150,.08),
        0 0 22px 2px rgba(255,122,150,.10);
    }

    #app[data-skin="sakura"] .btn{
      background: rgba(12,18,30,.6);
      border: 1px solid rgba(255,255,255,.10);
      color: var(--ink);
    }
    #app[data-skin="sakura"] .btn.primary{
      background: var(--primary);
      border-color: transparent;
      color: #0b0f18;
      box-shadow: 0 8px 26px color-mix(in oklab, var(--primary) 35%, black);
    }

    #app[data-skin="sakura"] .empty{
      background: rgba(8,12,20,.4);
      border-color: rgba(255,255,255,.1);
    }
    #app[data-skin="sakura"] .meta{ color: var(--muted); }

    /* 場所詳細ページ専用スタイル */
    .place-container { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; margin-top: 32px; }
    .place-image { width: 100%; height: 400px; object-fit: cover; border-radius: 16px; }
    .place-placeholder { width: 100%; height: 400px; background: var(--line); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: var(--muted); font-size: 18px; }
    .place-info { display: flex; flex-direction: column; gap: 16px; }
    .place-title { font-size: 32px; font-weight: 800; margin: 0; }
    .place-address { font-size: 18px; color: var(--muted); margin: 8px 0; }
    .place-description { margin: 16px 0; line-height: 1.6; }
    .place-details { margin: 16px 0; }
    .actions { display: flex; justify-content: space-between; align-items: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--line); }

    @media (max-width: 768px) {
      .place-container { grid-template-columns: 1fr; gap: 24px; }
      .place-title { font-size: 24px; }
      .actions { flex-direction: column; gap: 16px; align-items: stretch; }
    }
  </style>
</head>
<body>
  <!-- 実行プレビュー：背景切替トグル（チェックで有効） -->
  <div id="bg" aria-hidden="true"></div>

  <!-- ======= APP WRAPPER ======= -->
  <div id="app" data-skin="sakura">
    <header>
      <div class="container">
        <div class="row" style="justify-content: space-between;">
          <div class="row"><div class="brand">BKC<span>アプリ</span></div></div>
          <nav style="display:flex; gap:8px;">
            <a href="{{ route('places.index', request()->route('type')) }}" class="btn">一覧に戻る</a>
          </nav>
        </div>
      </div>
    </header>

    <main>
      <h1 class="h1">{{ $place->name }}</h1>
      <p class="sub">場所の詳細情報を確認できます。</p>

      <div class="place-container">
        <!-- 場所画像 -->
        <div>
          @if($place->images && $place->images->count() > 0)
            <img src="{{ $place->images->first()->path }}" alt="{{ $place->name }}" class="place-image">
          @else
            <div class="place-placeholder">
              <span>画像なし</span>
            </div>
          @endif
        </div>

        <!-- 場所詳細 -->
        <div class="place-info">
          <!-- タイプバッジ -->
          <div>
            @switch($place->type)
              @case('drive')
                <span class="pill" style="background: var(--green); color: #fff;">
                  ドライブ
                </span>
                @break
              @case('karaoke')
                <span class="pill" style="background: var(--accent); color: #fff;">
                  カラオケ
                </span>
                @break
              @case('izakaya')
                <span class="pill" style="background: var(--amber); color: #fff;">
                  居酒屋
                </span>
                @break
            @endswitch
          </div>

          <!-- 住所 -->
          @if($place->address)
            <div class="place-address">{{ $place->address }}</div>
          @endif

          <!-- 説明 -->
          @if($place->description)
            <div class="place-description">
              <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">説明</h3>
              <p>{{ $place->description }}</p>
            </div>
          @endif

          <!-- 詳細情報 -->
          <div class="place-details">
            @if($place->tel)
              <div style="margin-bottom: 12px;">
                <h4 style="font-weight: 600; margin-bottom: 4px;">電話番号</h4>
                <p class="meta">{{ $place->tel }}</p>
              </div>
            @endif

            @if($place->url)
              <div style="margin-bottom: 12px;">
                <h4 style="font-weight: 600; margin-bottom: 4px;">URL</h4>
                <a href="{{ $place->url }}" target="_blank" style="color: var(--primary); text-decoration: underline;">
                  {{ $place->url }}
                </a>
              </div>
            @endif

            @if($place->campus_time_min)
              <div style="margin-bottom: 12px;">
                <h4 style="font-weight: 600; margin-bottom: 4px;">大学からの時間</h4>
                <p class="meta">{{ $place->campus_time_min }}分</p>
              </div>
            @endif

            @if($place->score > 0)
              <div style="margin-bottom: 12px;">
                <h4 style="font-weight: 600; margin-bottom: 4px;">評価</h4>
                <div style="display: flex; align-items: center;">
                  @for($i = 1; $i <= 5; $i++)
                    @if($i <= $place->score)
                      <span style="color: var(--amber);">★</span>
                    @else
                      <span style="color: var(--line);">★</span>
                    @endif
                  @endfor
                  <span class="meta" style="margin-left: 8px;">({{ $place->score }}/5)</span>
                </div>
              </div>
            @endif
          </div>

          <!-- 出品者情報 -->
          @if($place->user)
            <div style="margin: 16px 0;">
              <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">出品者情報</h3>
              <p class="meta">出品者: {{ $place->user->login_id }}</p>
              <p class="meta">投稿日: {{ $place->created_at->format('Y年m月d日') }}</p>
            </div>
          @endif

          <!-- アクションボタン -->
          <div class="actions">
            <a href="{{ route('places.index', request()->route('type')) }}" class="btn">
              ← 一覧に戻る
            </a>

            @auth
              @if($place->user_id === auth()->id())
                <div style="display: flex; gap: 12px;">
                  <a href="{{ route('my.places.edit', $place) }}" class="btn primary">
                    編集
                  </a>
                  <form method="POST" action="{{ route('my.places.destroy', $place) }}" style="display: inline;" onsubmit="return confirm('削除しますか？')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn" style="color: var(--rose);">
                      削除
                    </button>
                  </form>
                </div>
              @endif
            @endauth
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>

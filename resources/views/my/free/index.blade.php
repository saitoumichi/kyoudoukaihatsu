<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>BKC生のためのアプリ – マイページ（フリマ管理）</title>
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

    /* Tabs */
    .tabs { display: flex; gap: 6px; flex-wrap: wrap; }
    .tabs label,
    .tabs .tabs-link {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 8px 12px; border-radius: 999px; cursor: pointer;
      border: 1px solid var(--line); color: var(--ink); text-decoration: none;
      background: var(--card);
      transition: box-shadow .2s ease, transform .05s ease;
      user-select: none;
    }
    .tabs label:hover,
    .tabs .tabs-link:hover { box-shadow: 0 1px 0 #e5e7eb, 0 0 0 4px rgba(37,99,235,.08) inset; }
    .tabs label[data-color="blue"],
    .tabs .tabs-link[data-color="blue"]{ border-color:#dbeafe; background:#eff6ff; }
    .tabs label[data-color="violet"],
    .tabs .tabs-link[data-color="violet"]{ border-color:#ede9fe; background:#f5f3ff; }
    .tabs label[data-color="rose"],
    .tabs .tabs-link[data-color="rose"]{ border-color:#ffe4e6; background:#fff1f2; }
    .tabs label[data-color="amber"],
    .tabs .tabs-link[data-color="amber"]{ border-color:#ffedd5; background:#fff7ed; }
    .tabs label[data-color="green"],
    .tabs .tabs-link[data-color="green"]{ border-color:#dcfce7; background:#f0fdf4; }

    /* ---------- UI atoms ---------- */
    .h1 { font-size: clamp(20px, 2.8vw, 28px); font-weight: 800; letter-spacing: .3px; margin: 6px 0 8px; }
    .sub { color: var(--muted); font-size: 14px; margin-bottom: 18px; }
    .grid { display: grid; gap: 14px; }
    .grid.cards { grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); }
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
    .btn-row { display:flex; gap:10px; flex-wrap: wrap; }

    .toolbar { display:flex; gap:8px; flex-wrap: wrap; align-items:center; background:#fff; border:1px solid var(--line); border-radius:14px; padding:8px; }
    .toolbar .field { display:flex; align-items:center; gap:6px; padding:6px 10px; background:#f8fafc; border-radius:10px; border:1px solid var(--line); }
    .toolbar input, .toolbar select { border: none; background: transparent; outline: none; font-size:14px; min-width: 120px; }

    .empty { padding: 24px; border: 2px dashed var(--line); border-radius: 16px; background: #fff; display: grid; gap: 8px; justify-items: start; }

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

    #app[data-skin="sakura"] .tabs label,
    #app[data-skin="sakura"] .tabs .tabs-link{
      border-color: rgba(255,255,255,.08);
      background: rgba(12,18,30,.56);
      color: #ffe4ef;
      box-shadow:
        inset 0 0 0 1px rgba(255,255,255,.04),
        0 0 0 2px rgba(255,152,177,.08);
    }
    #app[data-skin="sakura"] .tabs label:hover,
    #app[data-skin="sakura"] .tabs .tabs-link:hover{
      box-shadow:
        inset 0 0 0 2px rgba(255,152,177,.16),
        0 6px 18px rgba(0,0,0,.35);
    }
    #app[data-skin="sakura"] .tabs .tabs-link[data-color="blue"]{
      background: rgba(59,130,246,.2);
      border-color: rgba(59,130,246,.3);
      color: #dbeafe;
    }
    #app[data-skin="sakura"] .tabs .tabs-link[data-color="violet"]{
      background: rgba(139,92,246,.2);
      border-color: rgba(139,92,246,.3);
      color: #e9d5ff;
    }
    #app[data-skin="sakura"] .tabs .tabs-link[data-color="rose"]{
      background: rgba(244,63,94,.2);
      border-color: rgba(244,63,94,.3);
      color: #fecaca;
    }
    #app[data-skin="sakura"] .tabs .tabs-link[data-color="amber"]{
      background: rgba(245,158,11,.2);
      border-color: rgba(245,158,11,.3);
      color: #fde68a;
    }
    #app[data-skin="sakura"] .tabs .tabs-link[data-color="green"]{
      background: rgba(34,197,94,.2);
      border-color: rgba(34,197,94,.3);
      color: #bbf7d0;
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

    #app[data-skin="sakura"] .toolbar{
      background: rgba(10,16,26,.66);
      border: 1px solid rgba(255,255,255,.10);
    }
    #app[data-skin="sakura"] .toolbar .field{
      background: rgba(8,12,20,.8);
      border: 1px solid rgba(255,255,255,.08);
    }
    #app[data-skin="sakura"] .toolbar input,
    #app[data-skin="sakura"] .toolbar select{
      color: var(--ink);
    }

    #app[data-skin="sakura"] .empty{
      background: rgba(8,12,20,.4);
      border-color: rgba(255,255,255,.1);
    }
    #app[data-skin="sakura"] .meta{ color: var(--muted); }
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
            <a href="/my" class="btn">マイページに戻る</a>
          </nav>
        </div>
      </div>
    </header>

    <main>
      <h1 class="h1">フリマ管理</h1>
      <p class="sub">あなたが出品したフリマ商品を管理できます。</p>

      <!-- やり取り中のDM -->
      @if(isset($activeConversations) && $activeConversations->count() > 0)
      <div class="card" style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
          <div class="title" style="margin: 0;">💬 やり取り中のDM ({{ $activeConversations->count() }}件)</div>
          <a href="{{ route('my.messages') }}" class="btn" style="padding: 6px 12px; font-size: 13px;">全て見る</a>
        </div>
        <div style="margin-top: 16px;">
          @foreach($activeConversations as $key => $conversation)
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; margin-bottom: 8px; background: rgba(59,130,246,.08); border-radius: 8px; border: 1px solid rgba(59,130,246,.2);">
              <div style="flex: 1;">
                <div style="font-weight: 700; font-size: 14px; margin-bottom: 4px; color: var(--ink);">
                  👤 {{ $conversation['user']->login_id }}
                </div>
                <div class="meta" style="margin-bottom: 4px;">
                  商品: {{ $conversation['free_market']->title }}
                </div>
                <div class="meta" style="font-size: 12px;">
                  {{ Str::limit($conversation['last_message']->message, 40) }}
                </div>
                <div class="meta" style="font-size: 11px; margin-top: 2px;">
                  {{ $conversation['last_message']->created_at->diffForHumans() }}
                  @if($conversation['unread_count'] > 0)
                    <span style="color: var(--rose); font-weight: 700;">
                      • {{ $conversation['unread_count'] }}件未読
                    </span>
                  @endif
                </div>
              </div>
              <a href="{{ route('free.dm', $conversation['free_market']->id) }}" class="btn primary" style="padding: 8px 16px; font-size: 13px;">
                返信する
              </a>
            </div>
          @endforeach
        </div>
      </div>
      @endif

      <!-- アクションツールバー -->
      <div class="toolbar">
        <div class="field">
          <label>🔍</label>
          <input type="text" placeholder="商品名で検索..." />
        </div>
        <div class="field">
          <label>🏷️</label>
          <select>
            <option>すべて</option>
            <option>教科書</option>
            <option>家電・デジタル</option>
            <option>ファッション</option>
            <option>本・雑誌</option>
            <option>スポーツ・アウトドア</option>
            <option>ホビー・グッズ</option>
            <option>その他</option>
          </select>
        </div>
        <div class="field">
          <label>📅</label>
          <select>
            <option>新着順</option>
            <option>更新順</option>
            <option>名前順</option>
            <option>価格順</option>
          </select>
        </div>
        <div class="field">
          <label>📊</label>
          <select>
            <option>すべて</option>
            <option>販売中</option>
            <option>売り切れ</option>
            <option>キャンセル</option>
          </select>
        </div>
        <a href="/my/free/create" class="btn primary">新しい商品を出品</a>
      </div>

      <!-- フリマ商品一覧 -->
      <div class="grid cards" style="margin-top: 18px;">
        @forelse($freeItems ?? [] as $item)
        <div class="card">
          <!-- 商品画像 -->
          @if($item->image_url)
            <div style="margin: -14px -14px 14px -14px; border-radius: 16px 16px 0 0; overflow: hidden;">
              <img src="{{ $item->image_url }}" alt="{{ $item->title }}"
                   style="width: 100%; height: 160px; object-fit: cover;">
            </div>
          @else
            <div style="margin: -14px -14px 14px -14px; height: 160px; background: linear-gradient(135deg, rgba(255,106,169,.1), rgba(255,193,220,.1)); display: flex; align-items: center; justify-content: center; border-radius: 16px 16px 0 0;">
              <span style="color: var(--muted); font-size: 13px;">📷 画像なし</span>
            </div>
          @endif

          <div class="title">{{ $item->title }}</div>
          <div class="meta">価格: ¥{{ number_format($item->price) }}</div>
          <div class="meta">カテゴリ: {{ $item->category }}</div>
          <div style="margin: 8px 0;">
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
          <div class="meta">出品日: {{ $item->created_at->format('Y/m/d') }}</div>
          <div class="meta">
            @if($item->status == 'active')
              <span style="color: var(--green);">販売中</span>
            @elseif($item->status == 'sold')
              <span style="color: var(--rose);">売り切れ</span>
            @else
              <span style="color: var(--muted);">キャンセル</span>
            @endif
          </div>
          @if($item->description)
          <p style="margin: 8px 0; font-size: 14px; line-height: 1.4;">{{ Str::limit($item->description, 100) }}</p>
          @endif
          <div class="btn-row" style="margin-top: 12px;">
            <a href="/free/{{ $item->id }}" class="btn">詳細を見る</a>
            <a href="/my/free/{{ $item->id }}/edit" class="btn">編集</a>
            <a href="{{ route('my.free.messages', $item->id) }}" class="btn" style="position: relative; background: rgba(59,130,246,.2); border-color: rgba(59,130,246,.3); color: #dbeafe;">
              💬 DM
              @if($item->messages_count > 0)
                <span style="position: absolute; top: -6px; right: -6px; background: var(--rose); color: white; border-radius: 999px; padding: 2px 6px; font-size: 10px; font-weight: 700;">
                  {{ $item->messages_count }}
                </span>
              @endif
            </a>
            <form method="POST" action="/my/free/{{ $item->id }}" style="display: inline;" onsubmit="return confirm('この商品を削除しますか？')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn" style="color: #f43f5e;">削除</button>
            </form>
          </div>
        </div>
        @empty
        <div class="empty" style="grid-column: 1 / -1;">
          <h3>まだ出品がありません</h3>
          <p>最初のフリマ商品を出品してみませんか？</p>
          <a href="/my/free/create" class="btn primary">新しい商品を出品</a>
        </div>
        @endforelse
      </div>
    </main>
  </div>
</body>
</html>

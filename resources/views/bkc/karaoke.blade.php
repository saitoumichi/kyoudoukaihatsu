<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>BKC生のためのアプリ – カラオケ</title>
  <style>
    :root {
      --bg: #f7f9fc;
      --card: #ffffff;
      --ink: #0f172a;
      --muted: #64748b;
      --line: #e5e7eb;
      --primary: #00a000; /* green */
      --accent: #a78bfa;  /* violet */
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

    /* ★ チェック時：色変数を"暗色ガラスUI"に上書き（デモ用） */
    #bgprev:checked ~ #app {
      --ink:#0f172a;
      --card: rgba(255,255,255,.92);
      --line: rgba(15,23,42,.12);
      --muted: #64748b;
    }

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

    input[type="radio"].tab { display: none; }
    main { max-width: 1120px; margin: 18px auto 96px; padding: 0 20px; }
    section.view { display: none; }

    /* show selected section */
    #tab-home:checked ~ header label[for="tab-home"],
    #tab-drive:checked ~ header label[for="tab-drive"],
    #tab-karaoke:checked ~ header label[for="tab-karaoke"],
    #tab-izakaya:checked ~ header label[for="tab-izakaya"],
    #tab-fleamarket:checked ~ header label[for="tab-fleamarket"],
    #tab-mypage:checked ~ header label[for="tab-mypage"] {
      outline: 2px solid var(--primary);
      box-shadow: 0 6px 16px rgba(37,99,235,.15);
      transform: translateY(-1px);
    }

    #tab-home:checked ~ main #home,
    #tab-drive:checked ~ main #drive,
    #tab-karaoke:checked ~ main #karaoke,
    #tab-izakaya:checked ~ main #izakaya,
    #tab-fleamarket:checked ~ main #fleamarket,
    #tab-mypage:checked ~ main #mypage { display: block; }

    /* ---------- UI atoms ---------- */
    .h1 { font-size: clamp(20px, 2.8vw, 28px); font-weight: 800; letter-spacing: .3px; margin: 6px 0 8px; }
    .sub { color: var(--muted); font-size: 14px; margin-bottom: 18px; }
    .grid { display: grid; gap: 14px; }
    .grid.cards { grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); }

    /* 詳細表示モーダル */
    .detail-modal {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 60;
      background: rgba(15,23,42,.45);
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .detail-modal.is-open {
      display: flex;
    }
    .detail-modal-card {
      width: min(800px, 92vw);
      max-height: 80vh;
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: 16px;
      padding: 20px;
      box-shadow: 0 20px 60px rgba(15,23,42,.25);
      overflow-y: auto;
      backdrop-filter: blur(var(--blur)) saturate(1.05);
      -webkit-backdrop-filter: blur(var(--blur)) saturate(1.05);
    }
    .detail-modal .title {
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 16px;
      color: var(--ink);
    }
    .detail-modal .kvs {
      line-height: 1.6;
    }
    .detail-modal .kvs div:nth-child(odd) {
      font-weight: 600;
      color: var(--ink);
      margin-top: 12px;
    }
    .detail-modal .kvs div:nth-child(even) {
      color: var(--muted);
      margin-bottom: 8px;
    }
    .detail-btn {
      background: var(--primary);
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      margin-top: 8px;
    }
    .detail-btn:hover {
      background: color-mix(in oklab, var(--primary) 80%, black);
    }
    .close-btn {
      background: var(--muted);
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      margin-top: 16px;
    }
    .close-btn:hover {
      background: color-mix(in oklab, var(--muted) 80%, black);
    }
    .card { background: var(--card); border: 1px solid var(--line); border-radius: 16px; padding: 14px; box-shadow: 0 8px 28px rgba(15,23,42,0.08);
    /* 常時ガラスUI（可読性を保つため白ベースは維持） */
    backdrop-filter: blur(10px) saturate(1.05);
    -webkit-backdrop-filter: blur(10px) saturate(1.05);
  }
    #bgprev:checked ~ #app .card { box-shadow: 0 6px 30px rgba(0,0,0,.24); backdrop-filter: blur(10px); }
    .card .title { font-weight: 700; margin: 2px 0 6px; }
    .meta { color: var(--muted); font-size: 13px; }
    .pill { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; background:#f1f5f9; border:1px solid var(--line); font-size:12px; }

    .btn { display:inline-block; padding:10px 14px; border-radius: 12px; border: 1px solid var(--line); background: #fff; text-decoration:none; color:var(--ink); font-weight:700; }
    .btn.primary { background: var(--primary); color: #fff; border-color: transparent; }
    .btn.ghost { background: #fff; }
    .btn.full { width: 100%; text-align: center; }
    .btn-row { display:flex; gap:10px; flex-wrap: wrap; }

    .kvs { display:grid; grid-template-columns: auto 1fr; gap:6px 12px; font-size: 14px; }
    .star { color: #f59e0b; font-size: 16px; }

    .toolbar { display:flex; gap:8px; flex-wrap: wrap; align-items:center; background:#fff; border:1px solid var(--line); border-radius:14px; padding:8px; }
    .toolbar .field { display:flex; align-items:center; gap:6px; padding:6px 10px; background:#f8fafc; border-radius:10px; border:1px solid var(--line); }
    .toolbar input, .toolbar select { border: none; background: transparent; outline: none; font-size:14px; min-width: 120px; }

    form .field { display:grid; gap:6px; margin-bottom:12px; }
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
    form textarea { min-height: 120px; resize: vertical; }
    .hint { color: var(--muted); font-size: 12px; }

    .cols { display:grid; gap: 16px; grid-template-columns: 1.2fr .8fr; }
    @media (max-width: 960px) { .cols { grid-template-columns: 1fr; } }

    .empty { padding: 24px; border: 2px dashed var(--line); border-radius: 16px; background: #fff; display: grid; gap: 8px; justify-items: start; }

    .chips { display:flex; flex-wrap:wrap; gap:8px; }
    .chip { padding:6px 10px; border:1px solid var(--line); background:#fff; border-radius:999px; cursor:pointer; }

    table { width:100%; border-collapse: collapse; }
    th, td { padding: 10px 8px; text-align: left; border-bottom: 1px solid var(--line); }
    th { font-size: 13px; color: var(--muted); font-weight: 700; }

    footer { text-align:center; color:var(--muted); font-size:12px; padding: 28px 16px; }

    /* toggle helpers */
    .toggle { display:none; }

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
      --theme-0:#00a000;
      --theme-1:#00cc00;
      --theme-2:#ccffcc;
      --primary:var(--theme-0);
      color:var(--ink);
    }

    /* #bg が #app の前にあっても効くように :has で背景を更新 */
    body:has(#app[data-skin="sakura"]) #bg{
      background:
        radial-gradient(1200px 800px at 50% -20%, rgba(0,160,0,.18), transparent 60%),
        radial-gradient(900px 600px at 0% 30%,   rgba(0,200,0,.18), transparent 60%),
        radial-gradient(900px 600px at 100% 70%, rgba(0,180,0,.14), transparent 60%),
        linear-gradient(180deg, #001a00 0%, #002200 50%, #001500 100%) !important;
    }
    body:has(#app[data-skin="sakura"]) #bg::after{
      background:
        radial-gradient(420px 320px at 18% 78%, rgba(0,160,0,.22), transparent 60%),
        radial-gradient(380px 260px at 80% 22%, rgba(0,200,0,.20), transparent 60%),
        radial-gradient(280px 240px at 78% 86%, rgba(0,180,0,.16), transparent 60%),
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
    #app[data-skin="sakura"] .chip{
      border-color: rgba(255,255,255,.08);
      background: rgba(12,18,30,.56);
      color: #ffffff;
      box-shadow:
        inset 0 0 0 1px rgba(255,255,255,.04),
        0 0 0 2px rgba(255,152,177,.08);
    }
    #app[data-skin="sakura"] .tabs label:hover{
      box-shadow:
        inset 0 0 0 2px rgba(255,152,177,.16),
        0 6px 18px rgba(0,0,0,.35);
    }
    #app[data-skin="sakura"] .tabs .tabs-link{
      border-color: rgba(255,255,255,.08);
      background: rgba(12,18,30,.56);
      color: #ffe4ef;
      box-shadow:
        inset 0 0 0 1px rgba(255,255,255,.04),
        0 0 0 2px rgba(255,152,177,.08);
    }
    #app[data-skin="sakura"] .tabs .tabs-link:hover{
      box-shadow:
        inset 0 0 0 2px rgba(255,152,177,.16),
        0 6px 18px rgba(0,0,0,.35);
    }
    #app[data-skin="sakura"] .tabs .tabs-link[data-color="blue"]{
      background: rgba(59,130,246,.3);
      border-color: rgba(59,130,246,.5);
      color: #dbeafe;
      box-shadow: 0 0 8px rgba(59,130,246,.2);
    }
    #app[data-skin="sakura"] .tabs .tabs-link[data-color="violet"]{
      background: rgba(139,92,246,.3);
      border-color: rgba(139,92,246,.5);
      color: #e9d5ff;
      box-shadow: 0 0 8px rgba(139,92,246,.2);
    }
    #app[data-skin="sakura"] .tabs .tabs-link[data-color="rose"]{
      background: rgba(244,63,94,.3);
      border-color: rgba(244,63,94,.5);
      color: #fecaca;
      box-shadow: 0 0 8px rgba(244,63,94,.2);
    }
    #app[data-skin="sakura"] .tabs .tabs-link[data-color="amber"]{
      background: rgba(245,158,11,.3);
      border-color: rgba(245,158,11,.5);
      color: #fde68a;
      box-shadow: 0 0 8px rgba(245,158,11,.2);
    }
    #app[data-skin="sakura"] .tabs .tabs-link[data-color="green"]{
      background: rgba(34,197,94,.3);
      border-color: rgba(34,197,94,.5);
      color: #bbf7d0;
      box-shadow: 0 0 8px rgba(34,197,94,.2);
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

    #app[data-skin="sakura"] form input,
    #app[data-skin="sakura"] form textarea,
    #app[data-skin="sakura"] form select{
      background: rgba(10,16,26,.66);
      color: var(--ink);
      border: 1px solid rgba(255,255,255,.10);
    }
    #app[data-skin="sakura"] .meta{ color: var(--muted); }

    /* 選択中タブのアウトラインをテーマ色に */
    #app[data-skin="sakura"] #tab-home:checked ~ header label[for="tab-home"],
    #app[data-skin="sakura"] #tab-drive:checked ~ header label[for="tab-drive"],
    #app[data-skin="sakura"] #tab-karaoke:checked ~ header label[for="tab-karaoke"],
    #app[data-skin="sakura"] #tab-izakaya:checked ~ header label[for="tab-izakaya"],
    #app[data-skin="sakura"] #tab-fleamarket:checked ~ header label[for="tab-fleamarket"],
    #app[data-skin="sakura"] #tab-mypage:checked ~ header label[for="tab-mypage"]{
      outline: 2px solid var(--primary);
      box-shadow: 0 6px 16px color-mix(in oklab, var(--primary) 35%, black);
    }
  </style>
</head>
<body>
  <!-- 実行プレビュー：背景切替トグル（チェックで有効） -->
  <div id="bg" aria-hidden="true"></div>

  <!-- ======= APP WRAPPER ======= -->
  <div id="app" data-skin="sakura">
    <!-- NAV RADIO TABS (no JS) must be before header/main for CSS ~ selectors -->
    <input id="tab-home" class="tab" type="radio" name="nav">
    <input id="tab-drive" class="tab" type="radio" name="nav">
    <input id="tab-karaoke" class="tab" type="radio" name="nav" checked>
    <input id="tab-izakaya" class="tab" type="radio" name="nav">
    <input id="tab-fleamarket" class="tab" type="radio" name="nav">
    <input id="tab-mypage" class="tab" type="radio" name="nav">

    @include('components.header')

    <main>
      <!-- ================= KARAOKE ================= -->
      <section id="karaoke" class="view" aria-labelledby="karaoke-title">
        <h2 id="karaoke-title" class="h1">カラオケ</h2>
        <p class="sub"></p>

        <!-- ユーザー投稿の場所 -->
        @if(isset($places) && $places->count() > 0)
        <div style="margin: 32px 0 24px;">
          <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 16px;">みんなの投稿</h3>
          <div class="grid cards">
            @foreach($places as $place)
            <article class="card">
              <!-- 場所の画像 -->
              @if($place->images && $place->images->count() > 0)
                <div style="margin: -14px -14px 14px -14px; border-radius: 16px 16px 0 0; overflow: hidden;">
                  <img src="{{ $place->images->first()->path }}" alt="{{ $place->name }}"
                       style="width: 100%; height: 160px; object-fit: cover;">
                </div>
              @else
                <div style="margin: -14px -14px 14px -14px; height: 160px; background: linear-gradient(135deg, rgba(0,160,0,.1), rgba(0,200,0,.1)); display: flex; align-items: center; justify-content: center; border-radius: 16px 16px 0 0;">
                  <span style="color: var(--muted); font-size: 13px;">📷 画像なし</span>
                </div>
              @endif

              <div class="title">{{ $place->name }}</div>
              <div class="kvs" style="margin:8px 0;">
                @if($place->address)
                  <div>住所</div><div>{{ $place->address }}</div>
                @endif
                @if($place->campus_time_min)
                  <div>大学からの時間</div><div>{{ $place->campus_time_min }}分</div>
                @endif
                @if($place->url)
                  <div>URL</div><div><a href="{{ $place->url }}" target="_blank" rel="noopener">公式サイト</a></div>
                @endif
                @if($place->description)
                  <div>詳細</div><div class="detail-preview">{{ $place->description }}</div>
                @endif
                @if($place->score > 0)
                  <div>評価</div>
                  <div>
                    @for($i = 1; $i <= 5; $i++)
                      @if($i <= $place->score)
                        <span class="star">★</span>
                      @else
                        <span style="color: var(--line);">★</span>
                      @endif
                    @endfor
                  </div>
                @endif
                @if($place->user)
                  <div>投稿者</div><div>{{ $place->user->login_id ?? $place->user->name }}</div>
                @endif
              </div>
              <a href="{{ route('places.show', ['type' => 'karaoke', 'place' => $place->id]) }}" class="detail-btn" style="text-decoration: none;">詳細を表示する</a>
            </article>
            @endforeach
          </div>
        </div>
        @endif

        <h3 style="font-size: 20px; font-weight: 700; margin: 32px 0 16px;">公式おすすめスポット</h3>
        <div class="grid cards">
          <article class="card">
            <div class="title">JOYJOY 南草津店</div>
            <div class="meta">大学からの時間：車で10分</div>
            <div class="kvs" style="margin:10px 0 6px;">
              <div>住所</div><div>滋賀県草津市矢倉2丁目7-18</div>
              <div>持ち込み</div><div>自由（夜はアルコール飲み放題）</div>
              <div>機種</div><div>JOYSOUND / DAM（部屋ごとに選択）</div>
              <div>営業時間</div><div>平日 10:00–翌5:00、金夜〜日朝は通し営業・土は24時間</div>
              <div>設備</div><div>無料Wi‑Fi、ダーツ・ビリヤード併設</div>
            </div>
            <div aria-label="評価" class="star">★★★★☆</div>
            <button class="detail-btn" onclick="openDetailModal('karaoke-1')">詳細を表示する</button>
          </article>
          <article class="card">
            <div class="title">ジャンカラ 草津駅東口店</div>
            <div class="meta">大学からの時間：車で15分</div>
            <div class="kvs" style="margin:10px 0 6px;">
              <div>住所</div><div>滋賀県草津市大路1丁目11-9</div>
              <div>持ち込み</div><div>自由</div>
              <div>機種</div><div>JOYSOUND / DAM（部屋ごとに選択）</div>
              <div>営業時間</div><div>24時間営業</div>
              <div>設備</div><div>店内Wi‑Fi、HDMI貸出など</div>
            </div>
            <div aria-label="評価" class="star">★★★☆☆</div>
            <button class="detail-btn" onclick="openDetailModal('karaoke-2')">詳細を表示する</button>
          </article>
          <article class="card">
            <div class="title">カラオケStyle 南草津店</div>
            <div class="meta">大学からの時間：車で5分</div>
            <div class="kvs" style="margin:10px 0 6px;">
              <div>住所</div><div>滋賀県草津市野路5丁目18-5 ビーバーワールド 1-3F</div>
              <div>持ち込み</div><div>自由(アルコール持ち込み不可)</div>
              <div>機種</div><div>JOYSOUNDとDAMを部屋ごとに選べる</div>
              <div>営業時間</div><div>24時間営業</div>
              <div>設備</div><div>プロジェクター付き大部屋やダーツ、コミック読み放題</div>
            </div>
            <div aria-label="評価" class="star">★★★☆☆</div>
            <button class="detail-btn" onclick="openDetailModal('karaoke-3')">詳細を表示する</button>
          </article>
        </div>

        <!-- 詳細表示モーダル -->
        <div id="detail-modal" class="detail-modal">
          <div class="detail-modal-card">
            <div class="title" id="modal-title"></div>
            <div class="kvs" id="modal-content"></div>
            <button class="close-btn" onclick="closeDetailModal()">閉じる</button>
          </div>
        </div>

        <script>
          const detailData = {
            'karaoke-1': {
              title: 'JOYJOY 南草津店',
              content: `
                <div>詳細</div><div>南草津駅から徒歩5分の好立地。個室は清潔で音響も良好。夜はアルコール飲み放題プランがあり、学生の飲み会に人気。ダーツやビリヤードも楽しめるので、カラオケ以外の遊びも充実。平日の昼間は比較的空いており、授業の合間にも利用しやすい。</div>
              `
            },
            'karaoke-2': {
              title: 'ジャンカラ 草津駅東口店',
              content: `
                <div>詳細</div><div>草津駅東口から徒歩3分の便利な立地。24時間営業なので、深夜までカラオケを楽しめる。HDMIケーブルの貸出があるので、スマホやタブレットの画面を大画面に映して動画鑑賞も可能。個室は広めで、グループでの利用に適している。</div>
              `
            },
            'karaoke-3': {
              title: 'カラオケStyle 南草津店',
              content: `
                <div>詳細</div><div>BKCから最も近いカラオケ店。プロジェクター付きの大部屋があり、グループでの利用に最適。コミック読み放題サービスで、カラオケの合間にマンガを読むこともできる。アルコール持ち込みは不可だが、店内でドリンクを購入可能。学生割引もあり、コスパが良い。</div>
              `
            }
          };

          function openDetailModal(id) {
            const data = detailData[id];
            if (data) {
              document.getElementById('modal-title').textContent = data.title;
              document.getElementById('modal-content').innerHTML = data.content;
              document.getElementById('detail-modal').classList.add('is-open');
            }
          }

          function closeDetailModal() {
            document.getElementById('detail-modal').classList.remove('is-open');
          }

          // モーダル外をクリックで閉じる
          document.getElementById('detail-modal').addEventListener('click', function(e) {
            if (e.target === this) {
              closeDetailModal();
            }
          });
        </script>
      </section>
    </main>
  </div>
</body>
</html>

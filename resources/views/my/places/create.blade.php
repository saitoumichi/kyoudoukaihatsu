<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>BKC生のためのアプリ – 新しく掲載を作成</title>
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

    input[type="radio"].tab { display: none; }
    main { max-width: 1120px; margin: 18px auto 96px; padding: 0 20px; }
    section.view { display: none; }

    /* ---------- UI atoms ---------- */
    .h1 { font-size: clamp(20px, 2.8vw, 28px); font-weight: 800; letter-spacing: .3px; margin: 6px 0 8px; }
    .sub { color: var(--muted); font-size: 14px; margin-bottom: 18px; }
    .card { background: var(--card); border: 1px solid var(--line); border-radius: 16px; padding: 14px; box-shadow: 0 8px 28px rgba(15,23,42,0.08);
    /* 常時ガラスUI（可読性を保つため白ベースは維持） */
    backdrop-filter: blur(10px) saturate(1.05);
    -webkit-backdrop-filter: blur(10px) saturate(1.05);
  }
    #bgprev:checked ~ #app .card { box-shadow: 0 6px 30px rgba(0,0,0,.24); backdrop-filter: blur(10px); }
    .card .title { font-weight: 700; margin: 2px 0 6px; }
    .meta { color: var(--muted); font-size: 13px; }

    .btn { display:inline-block; padding:10px 14px; border-radius: 12px; border: 1px solid var(--line); background: #fff; text-decoration:none; color:var(--ink); font-weight:700; }
    .btn.primary { background: var(--primary); color: #fff; border-color: transparent; }
    .btn.ghost { background: #fff; }
    .btn.full { width: 100%; text-align: center; }
    .btn-row { display:flex; gap:10px; flex-wrap: wrap; }

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

    .chips { display:flex; flex-wrap:wrap; gap:8px; }
    .chip { padding:6px 10px; border:1px solid var(--line); background:#fff; border-radius:999px; cursor:pointer; }

    /* toggle helpers */
    .toggle { display:none; }

    /* カテゴリ切替タブの強調 */
    #mpnew-cat-drive:checked ~ .tabs label[for="mpnew-cat-drive"],
    #mpnew-cat-karaoke:checked ~ .tabs label[for="mpnew-cat-karaoke"],
    #mpnew-cat-izakaya:checked ~ .tabs label[for="mpnew-cat-izakaya"] {
      outline: 2px solid var(--primary); box-shadow: 0 6px 16px rgba(37,99,235,.15); transform: translateY(-1px);
    }
    /* フォーム表示切替 */
    #mpnew-cat-drive:checked ~ .mpnew-forms #mpnew-form-drive { display:block; }
    #mpnew-cat-drive:checked ~ .mpnew-forms #mpnew-form-karaoke,
    #mpnew-cat-drive:checked ~ .mpnew-forms #mpnew-form-izakaya { display:none; }
    #mpnew-cat-karaoke:checked ~ .mpnew-forms #mpnew-form-karaoke { display:block; }
    #mpnew-cat-karaoke:checked ~ .mpnew-forms #mpnew-form-drive,
    #mpnew-cat-karaoke:checked ~ .mpnew-forms #mpnew-form-izakaya { display:none; }
    #mpnew-cat-izakaya:checked ~ .mpnew-forms #mpnew-form-izakaya { display:block; }
    #mpnew-cat-izakaya:checked ~ .mpnew-forms #mpnew-form-drive,
    #mpnew-cat-izakaya:checked ~ .mpnew-forms #mpnew-form-karaoke { display:none; }
    /* ドライブ種類チップのON表現 */
    #mpnew-drive-type-shopping:checked + label.chip,
    #mpnew-drive-type-scenery:checked + label.chip,
    #mpnew-drive-type-break:checked + label.chip { background:#eef2ff; border-color:#c7d2fe; box-shadow: 0 0 0 2px rgba(37,99,235,.12) inset; }

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
    #app[data-skin="sakura"] #mpnew-cat-drive:checked ~ .tabs label[for="mpnew-cat-drive"],
    #app[data-skin="sakura"] #mpnew-cat-karaoke:checked ~ .tabs label[for="mpnew-cat-karaoke"],
    #app[data-skin="sakura"] #mpnew-cat-izakaya:checked ~ .tabs label[for="mpnew-cat-izakaya"]{
      outline: 2px solid var(--primary);
      box-shadow: 0 6px 16px color-mix(in oklab, var(--primary) 35%, black);
    }

    /* ドライブ種類チップのON表現（Sakuraテーマ） */
    #app[data-skin="sakura"] #mpnew-drive-type-shopping:checked + label.chip,
    #app[data-skin="sakura"] #mpnew-drive-type-scenery:checked + label.chip,
    #app[data-skin="sakura"] #mpnew-drive-type-break:checked + label.chip {
      background: rgba(255,106,169,.2);
      border-color: rgba(255,106,169,.3);
      color: #ffc1dc;
      box-shadow: 0 0 0 2px rgba(255,106,169,.12) inset;
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
            <a href="/my" class="btn">マイページに戻る</a>
          </nav>
                            </div>
                        </div>
    </header>

    <main>
      <h1 class="h1">新しく掲載を作成</h1>
      <p class="sub">新しい掲載を作成して、他のBKC生と情報を共有しましょう。</p>

      <!-- 掲載管理：新規作成 & 一覧/編集/削除 -->
      <div class="card">
        <div class="title">新しく掲載を作成</div>

        <!-- 作成カテゴリ切替タブ -->
        <input id="mpnew-cat-drive" class="tab" type="radio" name="mpnewcat" checked>
        <input id="mpnew-cat-karaoke" class="tab" type="radio" name="mpnewcat">
        <input id="mpnew-cat-izakaya" class="tab" type="radio" name="mpnewcat">
        <div class="tabs" style="margin:10px 0 12px;">
          <label for="mpnew-cat-drive" data-color="violet">ドライブ</label>
          <label for="mpnew-cat-karaoke" data-color="rose">カラオケ</label>
          <label for="mpnew-cat-izakaya" data-color="amber">居酒屋</label>
                                </div>

        <div class="mpnew-forms">
          <!-- ドライブ新規作成フォーム -->
          <form id="mpnew-form-drive" class="view" method="POST" action="{{ route('my.places.store') }}">
            @csrf
            <input type="hidden" name="type" value="drive">

            <div class="field"><label>場所名</label><input type="text" name="name" placeholder="例）メタセコイア並木 / 三井アウトレット滋賀竜王" required /></div>
            <div class="field"><label>住所</label><input type="text" name="address" placeholder="例）滋賀県高島市… / 滋賀県蒲生郡竜王町…" /></div>
            <div class="field"><label>大学からの時間（分）</label><input type="number" name="campus_time_min" placeholder="例）30 / 105" min="0" /></div>
            <div class="field"><label>URL</label><input type="url" name="url" placeholder="https://example.com" /></div>
            <div class="field"><label>種類（ドライブ）</label>
              <div class="chips">
                <input id="mpnew-drive-type-shopping" type="radio" name="category_id" value="1" class="toggle" checked>
                <label for="mpnew-drive-type-shopping" class="chip">ショッピング</label>
                <input id="mpnew-drive-type-scenery" type="radio" name="category_id" value="2" class="toggle">
                <label for="mpnew-drive-type-scenery" class="chip">景色</label>
                <input id="mpnew-drive-type-break" type="radio" name="category_id" value="3" class="toggle">
                <label for="mpnew-drive-type-break" class="chip">息抜き</label>
                                </div>
              <div class="hint">※ ドライブは「ショッピング / 景色 / 息抜き」から1つ選択</div>
                            </div>
                            <div class="field">
            <label>評価</label>
            <select name="score">
              <option value="0" {{ old('score', 0) == 0 ? 'selected' : '' }}>評価なし</option>
              <option value="1" {{ old('score', 0) == 1 ? 'selected' : '' }}>★☆☆☆☆ (1)</option>
              <option value="2" {{ old('score', 0) == 2 ? 'selected' : '' }}>★★☆☆☆ (2)</option>
              <option value="3" {{ old('score', 0) == 3 ? 'selected' : '' }}>★★★☆☆ (3)</option>
              <option value="4" {{ old('score', 0) == 4 ? 'selected' : '' }}>★★★★☆ (4)</option>
              <option value="5" {{ old('score', 0) == 5 ? 'selected' : '' }}>★★★★★ (5)</option>
            </select>
            @error('score')
              <div class="hint" style="color: var(--rose);">{{ $message }}</div>
            @enderror
          </div>
                            <div class="field"><label>おすすめ理由</label><textarea name="description" placeholder="例）見どころ・設備・注意事項など"></textarea></div>
            <div class="btn-row"><button type="submit" class="btn primary">掲載する</button><button type="reset" class="btn">消去</button></div>
          </form>

          <!-- カラオケ新規作成フォーム -->
          <form id="mpnew-form-karaoke" class="view" method="POST" action="{{ route('my.places.store') }}">
            @csrf
            <input type="hidden" name="type" value="karaoke">

            <div class="field"><label>店名</label><input type="text" name="name" placeholder="例）JOYJOY 南草津店" required /></div>
            <div class="field"><label>住所</label><input type="text" name="address" placeholder="例）滋賀県草津市…" /></div>
            <div class="field"><label>持ち込み</label><input type="text" name="byo_allowed" placeholder="例）自由（夜はアルコール飲み放題）" /></div>
            <div class="field"><label>機種</label><input type="text" name="machine_types" placeholder="例）JOYSOUND / DAM" /></div>
            <div class="field"><label>営業時間</label><input type="text" name="business_hours" placeholder="例）24時間 / 平日10:00–翌5:00" /></div>
            <div class="field"><label>設備</label><input type="text" name="facilities" placeholder="例）店内Wi‑Fi、HDMI貸出、ダーツ等" /></div>
            <div class="field"><label>URL</label><input type="url" name="url" placeholder="https://example.com" /></div>
            <div class="field"><label>詳細</label><textarea name="description" placeholder="例）予約可否、注意事項など"></textarea></div>
            <div class="btn-row"><button type="submit" class="btn primary">掲載する</button><button type="reset" class="btn">消去</button></div>
          </form>

          <!-- 居酒屋新規作成フォーム -->
          <form id="mpnew-form-izakaya" class="view" method="POST" action="{{ route('my.places.store') }}">
            @csrf
            <input type="hidden" name="type" value="izakaya">

            <div class="field"><label>店名</label><input type="text" name="name" placeholder="例）○○酒場" required /></div>
            <div class="field"><label>距離/時間</label><input type="text" name="distance" placeholder="例）大学から徒歩8分" /></div>
            <div class="field"><label>予算</label><input type="text" name="budget" placeholder="例）¥2,500〜¥3,500" /></div>
            <div class="field"><label>特徴</label><input type="text" name="features" placeholder="例）飲み放題あり / 個室 / 喫煙可" /></div>

            <!-- 絞り込み分類 -->
            <div class="field"><label>分類</label>
              <div class="chips">
                <input id="mpnew-izakaya-cheap" type="checkbox" name="categories[]" value="cheap" class="toggle">
                <label for="mpnew-izakaya-cheap" class="chip">安い</label>
                <input id="mpnew-izakaya-near" type="checkbox" name="categories[]" value="near" class="toggle">
                <label for="mpnew-izakaya-near" class="chip">近い</label>
                <input id="mpnew-izakaya-nomihodai" type="checkbox" name="categories[]" value="nomihodai" class="toggle">
                <label for="mpnew-izakaya-nomihodai" class="chip">飲み放題あり</label>
                        </div>
              <div class="hint">※ 複数選択可能。絞り込み表示に使用されます。</div>
                        </div>

            <div class="field"><label>URL</label><input type="url" name="url" placeholder="https://example.com" /></div>
            <div class="field"><label>詳細</label><textarea name="description" placeholder="例）席数、予約、注意事項など"></textarea></div>
            <div class="btn-row"><button type="submit" class="btn primary">掲載する</button><button type="reset" class="btn">消去</button></div>
                    </form>
                </div>
            </div>
    </main>
        </div>
</body>
</html>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>BKC生のためのアプリ – 実行プレビュー対応（青系・星空・ガラスUI切替）</title>
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
  </style>
</head>
<body>
  <!-- 実行プレビュー：背景切替トグル（チェックで有効） -->
  <div id="bg" aria-hidden="true"></div>

  <!-- ======= APP WRAPPER ======= -->
  <div id="app">
    <!-- NAV RADIO TABS (no JS) must be before header/main for CSS ~ selectors -->
    <input id="tab-home" class="tab" type="radio" name="nav" checked>
    <input id="tab-drive" class="tab" type="radio" name="nav">
    <input id="tab-karaoke" class="tab" type="radio" name="nav">
    <input id="tab-izakaya" class="tab" type="radio" name="nav">
    <input id="tab-fleamarket" class="tab" type="radio" name="nav">
    <input id="tab-mypage" class="tab" type="radio" name="nav">

    <header>
      <div class="container">
        <div class="row" style="justify-content: space-between;">
          <div class="row"><div class="brand">BKC<span>アプリ</span></div></div>
          <nav class="tabs" aria-label="主要ナビゲーション">
            <a href="/places" class="tabs-link" data-color="blue">ホーム</a>
            <a href="/places/type/drive" class="tabs-link" data-color="violet">ドライブ</a>
            <a href="/places/type/karaoke" class="tabs-link" data-color="rose">カラオケ</a>
            <a href="/places/type/izakaya" class="tabs-link" data-color="amber">居酒屋</a>
            <a href="/free" class="tabs-link" data-color="green">フリマ</a>
          </nav>
        </div>
      </div>
    </header>

    <main>
      <!-- ================= HOME ================= -->
      <section id="home" class="view" aria-labelledby="home-title">
        <h1 id="home-title" class="h1">ログイン</h1>
        <p class="sub">BKCアプリへようこそ。メールアドレス・ユーザーネーム・パスワードを入力してログインしてください。アカウント未作成の方は下のボタンから新規作成へ。</p>
        <div class="card" style="max-width:520px; margin: 0 auto;">
          <form>
            <div class="field"><label>メールアドレス</label><input type="email" placeholder="you@example.com" /></div>
            <div class="field"><label>ユーザーネーム</label><input type="text" placeholder="例）bkc_student" /></div>
            <div class="field"><label>パスワード</label><input type="password" placeholder="••••••••" /></div>
            <div class="btn-row">
              <a href="/places/type/register" class="btn primary">ログイン</a>
              <a href="/places/type/register" class="btn">新規作成へ</a>
            </div>
            <div class="hint">アカウントをお持ちでない方は「新規作成へ」を押してください。</div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>

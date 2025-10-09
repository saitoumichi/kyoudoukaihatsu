<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>BKC生のためのアプリ – 商品情報編集</title>
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

    /* フォームスタイル */
    .field { margin-bottom: 16px; }
    .field label { display: block; margin-bottom: 4px; font-weight: 600; color: var(--ink); }
    .field input, .field textarea, .field select {
      width: 100%; padding: 10px 12px; border: 1px solid var(--line); border-radius: 8px;
      background: rgba(255,255,255,.8); color: var(--ink); font-size: 14px;
      backdrop-filter: blur(4px);
    }
    .field input:focus, .field textarea:focus, .field select:focus {
      outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    .hint { font-size: 12px; color: var(--muted); margin-top: 4px; }
    .btn-row { display: flex; gap: 12px; margin-top: 20px; justify-content: flex-end; }

    #app[data-skin="sakura"] .field input,
    #app[data-skin="sakura"] .field textarea,
    #app[data-skin="sakura"] .field select {
      background: rgba(8,12,20,.4);
      border-color: rgba(255,255,255,.15);
      color: var(--ink);
    }
    #app[data-skin="sakura"] .field input:focus,
    #app[data-skin="sakura"] .field textarea:focus,
    #app[data-skin="sakura"] .field select:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(255, 106, 169, 0.2);
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
            <a href="{{ route('my.free.index') }}" class="btn">出品一覧に戻る</a>
          </nav>
        </div>
      </div>
    </header>

    <main>
      <h1 class="h1">商品情報編集</h1>
      <p class="sub">{{ $free->title }} の情報を編集できます。</p>

      <div class="card">
        <div class="title">フリマ商品を編集</div>
        <form method="POST" action="/my/free/{{ $free->id }}" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="field">
            <label>商品名</label>
            <input type="text" name="title" value="{{ old('title', $free->title) }}" placeholder="例）〇〇の教科書" required />
            @error('title')
              <div class="hint" style="color: var(--rose);">{{ $message }}</div>
            @enderror
          </div>

          <div class="field">
            <label>商品説明</label>
            <textarea name="description" rows="4" placeholder="例）商品の状態、受け渡し方法、注意事項など">{{ old('description', $free->description) }}</textarea>
            @error('description')
              <div class="hint" style="color: var(--rose);">{{ $message }}</div>
            @enderror
          </div>

          <div class="field">
            <label>価格</label>
            <input type="number" name="price" value="{{ old('price', $free->price) }}" placeholder="例）1500" min="0" required />
            @error('price')
              <div class="hint" style="color: var(--rose);">{{ $message }}</div>
            @enderror
          </div>

          <div class="field">
            <label>カテゴリ</label>
            <select name="category" required>
              <option value="">カテゴリを選択してください</option>
              <option value="textbooks" {{ old('category', $free->category) == 'textbooks' ? 'selected' : '' }}>教科書</option>
              <option value="electronics" {{ old('category', $free->category) == 'electronics' ? 'selected' : '' }}>家電・デジタル</option>
              <option value="fashion" {{ old('category', $free->category) == 'fashion' ? 'selected' : '' }}>ファッション</option>
              <option value="books" {{ old('category', $free->category) == 'books' ? 'selected' : '' }}>本・雑誌</option>
              <option value="sports" {{ old('category', $free->category) == 'sports' ? 'selected' : '' }}>スポーツ・アウトドア</option>
              <option value="hobby" {{ old('category', $free->category) == 'hobby' ? 'selected' : '' }}>ホビー・グッズ</option>
              <option value="other" {{ old('category', $free->category) == 'other' ? 'selected' : '' }}>その他</option>
            </select>
            @error('category')
              <div class="hint" style="color: var(--rose);">{{ $message }}</div>
            @enderror
          </div>

          <div class="field">
            <label>商品状態</label>
            <select name="condition" required>
              <option value="">状態を選択してください</option>
              <option value="new" {{ old('condition', $free->condition) == 'new' ? 'selected' : '' }}>新品</option>
              <option value="like_new" {{ old('condition', $free->condition) == 'like_new' ? 'selected' : '' }}>ほぼ新品</option>
              <option value="good" {{ old('condition', $free->condition) == 'good' ? 'selected' : '' }}>良い</option>
              <option value="fair" {{ old('condition', $free->condition) == 'fair' ? 'selected' : '' }}>普通</option>
            </select>
            @error('condition')
              <div class="hint" style="color: var(--rose);">{{ $message }}</div>
            @enderror
          </div>

          <div class="field">
            <label>商品画像</label>
            @if($free->image_url)
              <div style="margin-bottom: 8px;">
                <img src="{{ $free->image_url }}" alt="{{ $free->title }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                <p class="meta">現在の画像</p>
              </div>
            @endif
            <input type="file" name="image" accept="image/*" />
            <div class="hint">新しい画像を選択すると、既存の画像が置き換えられます</div>
            @error('image')
              <div class="hint" style="color: var(--rose);">{{ $message }}</div>
            @enderror
          </div>

          <div class="field">
            <label>出品状態</label>
            <select name="status">
              <option value="active" {{ old('status', $free->status) == 'active' ? 'selected' : '' }}>出品中</option>
              <option value="sold" {{ old('status', $free->status) == 'sold' ? 'selected' : '' }}>売り切れ</option>
              <option value="cancelled" {{ old('status', $free->status) == 'cancelled' ? 'selected' : '' }}>キャンセル</option>
            </select>
            @error('status')
              <div class="hint" style="color: var(--rose);">{{ $message }}</div>
            @enderror
          </div>

          <div class="btn-row">
            <button type="submit" class="btn primary">更新する</button>
            <a href="{{ route('my.free.index') }}" class="btn">キャンセル</a>
          </div>
        </form>
      </div>
    </main>
  </div>
</body>
</html>

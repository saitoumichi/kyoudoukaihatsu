<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>BKC生のためのアプリ – 実行プレビュー対応（青系・星空・ガラスUI切替）</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                    <div class="row">
                        <div class="brand">BKC<span>アプリ</span></div>
                    </div>
                    <nav class="tabs" aria-label="主要ナビゲーション">
                        <label for="tab-home" data-color="blue">ホーム</label>
                        <label for="tab-drive" data-color="violet">ドライブ</label>
                        <label for="tab-karaoke" data-color="rose">カラオケ</label>
                        <label for="tab-izakaya" data-color="amber">居酒屋</label>
                        <label for="tab-fleamarket" data-color="green">フリマ</label>
                        <label for="tab-mypage">マイページ</label>
                    </nav>
                </div>
            </div>
        </header>

        <main>
            <!-- ================= HOME ================= -->
            <section id="home" class="view" aria-labelledby="home-title">
                @include('bkc.home')
            </section>

            <!-- ================= DRIVE ================= -->
            <section id="drive" class="view" aria-labelledby="drive-title">
                @include('bkc.drive')
            </section>

            <!-- ================= KARAOKE ================= -->
            <section id="karaoke" class="view" aria-labelledby="karaoke-title">
                @include('bkc.karaoke')
            </section>

            <!-- ================= IZAKAYA ================= -->
            <section id="izakaya" class="view" aria-labelledby="izakaya-title">
                @include('bkc.izakaya')
            </section>

            <!-- ================= FLEA MARKET ================= -->
            <section id="fleamarket" class="view" aria-labelledby="fleamarket-title">
                @include('bkc.fleamarket')
            </section>

            <!-- ================= MY PAGE ================= -->
            <section id="mypage" class="view" aria-labelledby="mypage-title">
                @include('bkc.mypage')
            </section>
        </main>

        <footer>
            <p>&copy; 2024 BKC App. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>

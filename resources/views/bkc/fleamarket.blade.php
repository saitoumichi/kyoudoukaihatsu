<h2 id="fleamarket-title" class="h1">フリマ（教科書）</h2>
<p class="sub">出品 / 買取 の2タブ。<strong>買取はキーワード1つで検索</strong>し、結果からDMできます。詳細は常に表示されます。</p>

<input id="sub-sell" class="tab" type="radio" name="fm" checked>
<input id="sub-buy" class="tab" type="radio" name="fm">

<div class="tabs" style="margin: 10px 0 14px;">
    <label for="sub-sell" data-color="green">出品</label>
    <label for="sub-buy" data-color="amber">買取</label>
</div>

<div id="fm-sell" class="view">
    <div class="cols">
        <div class="card">
            <div class="title">表紙の作成 / 掲載</div>
            <form>
                <div class="field">
                    <label>教科書の写真</label>
                    <input type="file" accept="image/*" />
                </div>
                <div class="field">
                    <label>教科書名</label>
                    <input type="text" placeholder="例）線形代数入門" />
                </div>
                <div class="field">
                    <label>大学からの距離 / 時間</label>
                    <input type="text" placeholder="例）徒歩10分" />
                </div>
                <div class="field">
                    <label>金額</label>
                    <input type="number" placeholder="例）1500" />
                </div>
                <div class="field">
                    <label>お店 / 参考URL</label>
                    <input type="url" placeholder="https://example.com" />
                </div>
                <div class="field">
                    <label>詳細（利用した授業など）</label>
                    <textarea placeholder="例）経済学概論（2024年度）で使用"></textarea>
                </div>
                <div class="btn-row">
                    <button type="button" class="btn primary">掲載</button>
                    <button type="reset" class="btn">消去</button>
                </div>
            </form>
        </div>
        
        <div class="card">
            <div class="title">あなたの掲載一覧（編集・削除）</div>
            <div class="grid">
                <article class="card" style="border-style:dashed;">
                    <div class="title">数学入門</div>
                    <div class="meta">¥1,500 / 大学から徒歩5分</div>
                    <div class="btn-row" style="margin-top:8px;">
                        <label for="sell-edit-1" class="btn">編集</label>
                        <label for="sell-del-1" class="btn ghost">削除</label>
                    </div>
                    <input id="sell-edit-1" class="toggle" type="checkbox">
                    <div class="edit-panel">
                        <form>
                            <div class="field">
                                <label>教科書名</label>
                                <input type="text" value="数学入門" />
                            </div>
                            <div class="field">
                                <label>金額</label>
                                <input type="number" value="1500" />
                            </div>
                            <div class="field">
                                <label>距離/時間</label>
                                <input type="text" value="大学から徒歩5分" />
                            </div>
                            <div class="field">
                                <label>URL</label>
                                <input type="url" placeholder="https://" />
                            </div>
                            <div class="field">
                                <label>詳細</label>
                                <textarea>状態良好。書き込みなし。</textarea>
                            </div>
                            <div class="btn-row">
                                <label for="sell-edit-1" class="btn primary">保存</label>
                                <label for="sell-edit-1" class="btn">閉じる</label>
                            </div>
                        </form>
                    </div>
                    <input id="sell-del-1" class="toggle" type="checkbox">
                    <div id="sell-md-del-1" class="modal">
                        <div class="modal-card">
                            <div class="title">削除しますか？</div>
                            <p class="meta">「数学入門」を削除すると元に戻せません。</p>
                            <div class="btn-row" style="margin-top:10px;">
                                <label for="sell-del-1" class="btn primary">削除する</label>
                                <label for="sell-del-1" class="btn">キャンセル</label>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</div>

<div id="fm-buy" class="view">
    <div class="card">
        <div class="title">検索（キーワード1つ）</div>
        <form role="search" class="toolbar" style="margin:8px 0 12px;">
            <div class="field" style="flex:1 1 260px;">
                <input type="text" placeholder="例）統計 / 線形代数 / 山田太郎 / 経済学" aria-label="検索キーワード"/>
            </div>
            <button type="button" class="btn primary">検索</button>
        </form>
        <div class="grid cards">
            <article class="card">
                <div class="title">1. 統計学入門</div>
                <div class="meta">掲載者：bkc_stats / ¥1,800</div>
                <div class="kvs" style="margin:10px 0;">
                    <div>著者</div><div>東京太郎 ほか</div>
                    <div>科目</div><div>統計学（基礎）</div>
                    <div>状態</div><div>書き込み少しあり</div>
                    <div>詳細</div><div>第3版。</div>
                </div>
                <div class="btn-row" style="margin-top:6px;">
                    <a class="btn" href="#">DM</a>
                </div>
            </article>
        </div>
    </div>
</div>

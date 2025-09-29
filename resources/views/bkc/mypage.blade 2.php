<h2 id="mypage-title" class="h1">マイページ</h2>
<p class="sub">ここでは <strong>ログアウト</strong>・<strong>パスワード変更</strong>・<strong>各カテゴリの掲載管理（作成 / 編集 / 削除）</strong> ができます。（デモUI）</p>

<!-- アカウント行 -->
<div class="card" style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
    <div>
        <div class="title" style="margin-bottom:4px;">アカウント</div>
        <div class="meta">ログイン中：<strong>bkc_student</strong>（例）</div>
    </div>
    <div class="btn-row">
        <label for="tab-home" class="btn">ログアウト</label>
    </div>
</div>

<!-- パスワード変更 -->
<div class="card" style="margin-top:14px; max-width:720px;">
    <div class="title">パスワード変更</div>
    <form>
        <div class="field">
            <label>現在のパスワード</label>
            <input type="password" placeholder="現在のパスワード" />
        </div>
        <div class="field">
            <label>新しいパスワード</label>
            <input type="password" placeholder="8文字以上" />
        </div>
        <div class="field">
            <label>新しいパスワード（確認）</label>
            <input type="password" placeholder="もう一度入力" />
        </div>
        <div class="btn-row">
            <button type="button" class="btn primary">更新する</button>
        </div>
    </form>
</div>

<!-- 掲載管理：新規作成 & 一覧/編集/削除 -->
<div class="cols" style="margin-top:14px;">
    <!-- 新規作成 -->
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
            <form id="mpnew-form-drive" class="view">
                <div class="field">
                    <label>名称</label>
                    <input type="text" placeholder="例）メタセコイア並木 / 三井アウトレット滋賀竜王" />
                </div>
                <div class="field">
                    <label>住所</label>
                    <input type="text" placeholder="例）滋賀県高島市… / 滋賀県蒲生郡竜王町…" />
                </div>
                <div class="field">
                    <label>大学からの時間</label>
                    <input type="text" placeholder="例）車で30分 / 1時間45分" />
                </div>
                <div class="field">
                    <label>URL</label>
                    <input type="url" placeholder="https://example.com" />
                </div>
                <div class="field">
                    <label>種類（ドライブ）</label>
                    <div class="chips">
                        <input id="mpnew-drive-type-shopping" type="radio" name="mpnew-drive-type" class="toggle" checked>
                        <label for="mpnew-drive-type-shopping" class="chip">ショッピング</label>
                        <input id="mpnew-drive-type-scenery" type="radio" name="mpnew-drive-type" class="toggle">
                        <label for="mpnew-drive-type-scenery" class="chip">景色</label>
                        <input id="mpnew-drive-type-break" type="radio" name="mpnew-drive-type" class="toggle">
                        <label for="mpnew-drive-type-break" class="chip">息抜き</label>
                    </div>
                    <div class="hint">※ ドライブは「ショッピング / 景色 / 息抜き」から1つ選択</div>
                </div>
                <div class="field">
                    <label>詳細</label>
                    <textarea placeholder="例）見どころ・設備・注意事項など"></textarea>
                </div>
                <div class="btn-row">
                    <button type="button" class="btn primary">掲載する</button>
                    <button type="reset" class="btn">消去</button>
                </div>
            </form>

            <!-- カラオケ新規作成フォーム -->
            <form id="mpnew-form-karaoke" class="view">
                <div class="field">
                    <label>店名</label>
                    <input type="text" placeholder="例）JOYJOY 南草津店" />
                </div>
                <div class="field">
                    <label>住所</label>
                    <input type="text" placeholder="例）滋賀県草津市…" />
                </div>
                <div class="field">
                    <label>持ち込み</label>
                    <input type="text" placeholder="例）自由（夜はアルコール飲み放題）" />
                </div>
                <div class="field">
                    <label>機種</label>
                    <input type="text" placeholder="例）JOYSOUND / DAM" />
                </div>
                <div class="field">
                    <label>営業時間</label>
                    <input type="text" placeholder="例）24時間 / 平日10:00–翌5:00" />
                </div>
                <div class="field">
                    <label>設備</label>
                    <input type="text" placeholder="例）店内Wi‑Fi、HDMI貸出、ダーツ等" />
                </div>
                <div class="field">
                    <label>URL</label>
                    <input type="url" placeholder="https://example.com" />
                </div>
                <div class="field">
                    <label>詳細</label>
                    <textarea placeholder="例）予約可否、注意事項など"></textarea>
                </div>
                <div class="btn-row">
                    <button type="button" class="btn primary">掲載する</button>
                    <button type="reset" class="btn">消去</button>
                </div>
            </form>

            <!-- 居酒屋新規作成フォーム -->
            <form id="mpnew-form-izakaya" class="view">
                <div class="field">
                    <label>店名</label>
                    <input type="text" placeholder="例）○○酒場" />
                </div>
                <div class="field">
                    <label>距離/時間</label>
                    <input type="text" placeholder="例）大学から徒歩8分" />
                </div>
                <div class="field">
                    <label>予算</label>
                    <input type="text" placeholder="例）¥2,500〜¥3,500" />
                </div>
                <div class="field">
                    <label>特徴</label>
                    <input type="text" placeholder="例）飲み放題あり / 個室 / 喫煙可" />
                </div>
                <div class="field">
                    <label>URL</label>
                    <input type="url" placeholder="https://example.com" />
                </div>
                <div class="field">
                    <label>詳細</label>
                    <textarea placeholder="例）席数、予約、注意事項など"></textarea>
                </div>
                <div class="btn-row">
                    <button type="button" class="btn primary">掲載する</button>
                    <button type="reset" class="btn">消去</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 一覧 / 編集 / 削除 -->
    <div class="card">
        <div class="title">あなたの掲載一覧（カテゴリ別）</div>

        <!-- カテゴリ切替タブ -->
        <input id="mp-cat-drive" class="tab" type="radio" name="mpcat" checked>
        <input id="mp-cat-karaoke" class="tab" type="radio" name="mpcat">
        <input id="mp-cat-izakaya" class="tab" type="radio" name="mpcat">
        
        <div class="tabs" style="margin:10px 0 12px;">
            <label for="mp-cat-drive" data-color="violet">ドライブ</label>
            <label for="mp-cat-karaoke" data-color="rose">カラオケ</label>
            <label for="mp-cat-izakaya" data-color="amber">居酒屋</label>
        </div>

        <div class="mp-lists">
            <!-- ドライブ一覧 -->
            <div id="mp-list-drive" class="grid cards view">
                <article class="card" style="border-style:dashed;">
                    <div class="title">（例）三井アウトレットパーク 滋賀竜王</div>
                    <div class="meta">車で30分 / <a href="https://mitsui-shopping-park.com/mop/shiga/" target="_blank" rel="noopener">公式サイト</a></div>
                    <div class="btn-row" style="margin-top:8px;">
                        <label for="mp-edit-drive-1" class="btn">編集</label>
                        <label for="mp-del-drive-1" class="btn ghost">削除</label>
                    </div>
                    <input id="mp-edit-drive-1" class="toggle" type="checkbox">
                    <div class="edit-panel">
                        <form>
                            <div class="field">
                                <label>名称</label>
                                <input type="text" value="三井アウトレットパーク 滋賀竜王" />
                            </div>
                            <div class="field">
                                <label>住所</label>
                                <input type="text" value="滋賀県蒲生郡竜王町大字薬師字砂山1178-694" />
                            </div>
                            <div class="field">
                                <label>大学からの時間</label>
                                <input type="text" value="車で30分" />
                            </div>
                            <div class="field">
                                <label>URL</label>
                                <input type="url" value="https://mitsui-shopping-park.com/mop/shiga/" />
                            </div>
                            <div class="field">
                                <label>詳細</label>
                                <textarea>近畿最大級・約230ブランド。無料駐車場、館内Wi‑Fi・EV充電あり。</textarea>
                            </div>
                            <div class="btn-row">
                                <label for="mp-edit-drive-1" class="btn primary">保存</label>
                                <label for="mp-edit-drive-1" class="btn">閉じる</label>
                            </div>
                        </form>
                    </div>
                    <input id="mp-del-drive-1" class="toggle" type="checkbox">
                    <div id="mp-md-del-drive-1" class="modal">
                        <div class="modal-card">
                            <div class="title">削除しますか？</div>
                            <p class="meta">この掲載を削除すると元に戻せません。</p>
                            <div class="btn-row" style="margin-top:10px;">
                                <label for="mp-del-drive-1" class="btn primary">削除する</label>
                                <label for="mp-del-drive-1" class="btn">キャンセル</label>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <!-- カラオケ一覧 -->
            <div id="mp-list-karaoke" class="grid cards view">
                <article class="card" style="border-style:dashed;">
                    <div class="title">（例）JOYJOY 南草津店</div>
                    <div class="meta">持ち込み：自由 / 機種：JOYSOUND・DAM / 営業時間：平日10:00–翌5:00</div>
                    <div class="btn-row" style="margin-top:8px;">
                        <label for="mp-edit-karaoke-1" class="btn">編集</label>
                        <label for="mp-del-karaoke-1" class="btn ghost">削除</label>
                    </div>
                    <input id="mp-edit-karaoke-1" class="toggle" type="checkbox">
                    <div class="edit-panel">
                        <form>
                            <div class="field">
                                <label>店名</label>
                                <input type="text" value="JOYJOY 南草津店" />
                            </div>
                            <div class="field">
                                <label>住所</label>
                                <input type="text" value="滋賀県草津市矢倉2丁目7-18" />
                            </div>
                            <div class="field">
                                <label>持ち込み</label>
                                <input type="text" value="自由（夜はアルコール飲み放題）" />
                            </div>
                            <div class="field">
                                <label>機種</label>
                                <input type="text" value="JOYSOUND / DAM" />
                            </div>
                            <div class="field">
                                <label>営業時間</label>
                                <input type="text" value="平日 10:00–翌5:00、金夜〜日朝は通し・土24h" />
                            </div>
                            <div class="field">
                                <label>設備</label>
                                <input type="text" value="無料Wi‑Fi、ダーツ・ビリヤード併設" />
                            </div>
                            <div class="btn-row">
                                <label for="mp-edit-karaoke-1" class="btn primary">保存</label>
                                <label for="mp-edit-karaoke-1" class="btn">閉じる</label>
                            </div>
                        </form>
                    </div>
                    <input id="mp-del-karaoke-1" class="toggle" type="checkbox">
                    <div id="mp-md-del-karaoke-1" class="modal">
                        <div class="modal-card">
                            <div class="title">削除しますか？</div>
                            <p class="meta">この掲載を削除すると元に戻せません。</p>
                            <div class="btn-row" style="margin-top:10px;">
                                <label for="mp-del-karaoke-1" class="btn primary">削除する</label>
                                <label for="mp-del-karaoke-1" class="btn">キャンセル</label>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <!-- 居酒屋一覧 -->
            <div id="mp-list-izakaya" class="grid cards view">
                <article class="card" style="border-style:dashed;">
                    <div class="title">（例）○○酒場</div>
                    <div class="meta">大学から徒歩8分 / 予算 ¥2,500〜¥3,500 / 飲み放題あり</div>
                    <div class="btn-row" style="margin-top:8px;">
                        <label for="mp-edit-izakaya-1" class="btn">編集</label>
                        <label for="mp-del-izakaya-1" class="btn ghost">削除</label>
                    </div>
                    <input id="mp-edit-izakaya-1" class="toggle" type="checkbox">
                    <div class="edit-panel">
                        <form>
                            <div class="field">
                                <label>店名</label>
                                <input type="text" value="○○酒場" />
                            </div>
                            <div class="field">
                                <label>距離/時間</label>
                                <input type="text" value="大学から徒歩8分" />
                            </div>
                            <div class="field">
                                <label>予算</label>
                                <input type="text" value="¥2,500〜¥3,500" />
                            </div>
                            <div class="field">
                                <label>特徴</label>
                                <input type="text" value="飲み放題あり" />
                            </div>
                            <div class="field">
                                <label>URL</label>
                                <input type="url" placeholder="https://" />
                            </div>
                            <div class="field">
                                <label>詳細</label>
                                <textarea>席数や予約可否などを記入</textarea>
                            </div>
                            <div class="btn-row">
                                <label for="mp-edit-izakaya-1" class="btn primary">保存</label>
                                <label for="mp-edit-izakaya-1" class="btn">閉じる</label>
                            </div>
                        </form>
                    </div>
                    <input id="mp-del-izakaya-1" class="toggle" type="checkbox">
                    <div id="mp-md-del-izakaya-1" class="modal">
                        <div class="modal-card">
                            <div class="title">削除しますか？</div>
                            <p class="meta">この掲載を削除すると元に戻せません。</p>
                            <div class="btn-row" style="margin-top:10px;">
                                <label for="mp-del-izakaya-1" class="btn primary">削除する</label>
                                <label for="mp-del-izakaya-1" class="btn">キャンセル</label>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</div>

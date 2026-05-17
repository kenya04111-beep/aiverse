<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// 判定処理...
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🐶 Alverse</title>
    <link rel="icon" href="data:,">
    <style>
        /* 🎨 カラーパレットシステム (キャットコア & ダーク対応) */
        :root {
            --bg-color: #fdfaf2;       /* 暖かいミルク色 */
            --header-bg: #5c4033;     /* 深みのあるココアブラウン */
            --header-text: #ffffff;
            --card-bg: #ffffff;
            --text-color: #3d2b1f;    /* 焦げ茶色 */
            --border-color: #ebdcd0;
            --accent-color: #ffdf7a;  /* 温かみのあるイエロー */
            --shadow: 0 8px 20px rgba(92, 64, 51, 0.06);
            --modal-bg: rgba(92, 64, 51, 0.5);
        }
        [data-theme="dark"] {
            --bg-color: #1a1512;       /* 漆黒のローストコーヒー */
            --header-bg: #2b1c15;
            --header-text: #f5ebe6;
            --card-bg: #251e1a;
            --text-color: #f5ebe6;
            --border-color: #3d2b21;
            --accent-color: #cca43b;
            --shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            --modal-bg: rgba(0, 0, 0, 0.7);
        }

        /* 📱 基本レイアウト＆リセット */
        * { box-sizing: border-box; }
        body {
            font-family: 'Helvetica Neue', Arial, 'Hiragino Kaku Gothic ProN', Meiryo, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: background-color 0.3s, color 0.3s;
        }

        /* 📍 ヘッダーデザイン */
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 24px;
            background: var(--header-bg);
            color: var(--header-text);
            box-shadow: 0 3px 15px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .logo {
            font-size: 1.45rem;
            font-weight: 800;
            cursor: pointer;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

/* --- 🔍 検索コンテナ：シンプル＆ワイド --- */
.search-container {
    flex-grow: 1;
    display: flex;
    justify-content: center;
    max-width: 320px; /* タブレット標準幅 */
    margin: 0 20px;
    transition: max-width 0.3s ease;
}

/* ⌨️ 検索入力欄：アイコンなしのミニマルデザイン */
#search-bar {
    width: 100%;
    background: rgba(255, 255, 255, 0.1);
    border: 1.5px solid rgba(255, 255, 255, 0.2);
    border-radius: 24px;
    padding: 10px 20px; /* 左右均等な余白に修正 */
    color: #ffffff;
    font-size: 0.95rem;
    outline: none;
    transition: all 0.3s ease;
}

/* 💡 フォーカス時の発光をより上品に */
#search-bar:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 223, 122, 0.6);
    box-shadow: 0 0 12px rgba(255, 223, 122, 0.2);
}

/* --- 💻 PC版 (1024px以上) --- */
@media (min-width: 1024px) {
    .search-container {
        max-width: 600px; /* 広々と使わせる */
    }
}

/* --- 📱 iPhone版 (768px以下) --- */
@media (max-width: 768px) {
    .search-container {
        max-width: 200px; /* スマホでも十分な幅を確保 */
        margin: 0 10px;
    }

    #search-bar {
        font-size: 0.85rem;
        padding: 8px 15px;
    }
}
/* 🧭 ナビゲーションアイコン */
.nav-icons {
    display: flex;
    align-items: center;
    gap: 8px; /* 間隔を少し詰め、密度を調整 */
}

/* 🔘 ナビゲーションボタン共通設定 */
.nav-btn {
    background: transparent;
    border: none;
    font-size: 1.3rem; /* アイコンを少し大きく */
    color: #ffffff;
    cursor: pointer;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

/* ✨ ホバー時：背景をふわっと浮かせる */
.nav-btn:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-2px); /* 上に少し浮く */
}

/* 👆 クリック時（アクティブ）の反応 */
.nav-btn:active {
    transform: scale(0.92);
}

/* ⚙️ ギアボタン専用：ホバーで回転する遊び心 */
.gear-btn:hover {
    transform: rotate(45deg);
}
        /* ⚙️ ドロップダウンメニューの固定制御 */
        .dropdown { position: relative; }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 48px;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            box-shadow: var(--shadow);
            min-width: 220px;
            z-index: 1000;
            padding: 10px 0;
            animation: fadeIn 0.2s ease-out;
        }
        .dropdown-content.show { display: block; }
        .dropdown-content a {
            color: var(--text-color);
            padding: 12px 20px;
            text-decoration: none;
            display: block;
            cursor: pointer;
            font-size: 0.95rem;
            transition: background 0.2s;
        }
        .dropdown-content a:hover {
            background: var(--accent-color);
            color: #3d2b1f;
        }
        .close-menu-btn {
            border: none;
            background: none;
            color: var(--text-color);
            font-size: 1.25rem;
            cursor: pointer;
            position: absolute;
            right: 12px;
            top: 6px;
            display: none; /* PCでは非表示、レスポンシブで調整可能 */
        }

        /* 📰 記事カード：PC＆iPad版 (横5列×縦2段) */
        main { flex: 1; padding: 30px 24px; }
        #mainGrid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }

/* 🧱 記事カード全体（ホバーアニメーションの準備） */
.post-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border-color, #eaeaea);
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    /* 浮き上がりと影の滑らかな変化を定義 */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}

/* 🖱️ ホバー時の浮き上がり効果 */
.post-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 28px rgba(92, 64, 51, 0.12);
}

/* 🖼️ サムネイル画像 */
.post-card img {
    width: 100%;
    aspect-ratio: 16 / 10;
    object-fit: cover;
    border-bottom: 1px solid var(--border-color, #eaeaea);
    /* ホバー時の微拡大アニメーションの準備 */
    transition: transform 0.4s ease;
}

/* 🌟 追加：ホバー時に画像を少しだけズームさせてリッチ感を演出 */
.post-card:hover img {
    transform: scale(1.03);
}

/* 📝 コンテンツエリア */
.post-content {
    padding: 16px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    background: var(--card-bg, #ffffff);
    z-index: 1; /* 画像ズーム時に背景を守る */
}

/* 🏷️ カテゴリーラベル（絵文字・日本語対応版） */
.post-category {
    display: inline-block;
    font-size: 0.8rem;
    color: #ff9800; /* 先ほどモーダルと統一したAIverseのテーマカラー */
    font-weight: 700;
    margin-bottom: 8px;
    letter-spacing: 0.5px;
    /* text-transform: uppercase; は絵文字と日本語が化けるため削除！ */
}

/* 🖋️ 記事タイトル（2行制限を維持しつつ行間最適化） */
.post-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-color, #333333);
    margin: 0 0 12px 0;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.2s ease;
}

/* 🌟 追加：ホバー時にタイトルもテーマカラーにうっすら変化 */
.post-card:hover .post-title {
    color: #ff9800;
}

/* 📅 日付（常に最下部に固定） */
.post-date {
    font-size: 0.75rem;
    color: #8c827a;
    margin-top: auto; /* 余白を自動計算し、一番下にピタッとくっつける魔法 */
    display: flex;
    align-items: center;
    gap: 4px;
}
        /* 📱 スマホ環境（スクロール＆見やすさ最適化） */
        @media (max-width: 900px) {
            #mainGrid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 600px) {
            header { padding: 12px 16px; }
            .logo { font-size: 1.2rem; }
            .search-container { max-width: 150px; margin: 0 8px; }
            #search-bar { padding: 6px 12px; font-size: 0.85rem; }
            #mainGrid {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }
            .post-card {
                width: 100%;
                flex: none;
            }
            .close-menu-btn { display: block; }
        }

        /* 🔢 ページネーション */
        .pagination { display: flex; justify-content: center; gap: 8px; padding: 30px 0 10px 0; }
        .page-btn {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-color);
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .page-btn.active, .page-btn:hover {
            background: var(--accent-color);
            color: #3d2b1f;
            border-color: #3d2b1f;
            transform: scale(1.05);
        }

        /* 🧱 モーダルウィンドウ共通 */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: var(--modal-bg);
            z-index: 10000;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(4px);
            padding: 16px;
        }
        .modal-content {
            background: var(--card-bg);
            padding: 28px;
            border-radius: 20px;
            width: 100%;
            max-width: 620px;
            max-height: 85vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            animation: modalUp 0.25s ease-out;
        }
        .modal-close {
            position: absolute;
            top: 16px; right: 16px;
            background: none; border: none;
            font-size: 1.6rem; color: var(--text-color);
            cursor: pointer;
            transition: transform 0.2s;
        }
        .modal-close:hover { transform: rotate(90deg); }

        /* 👣 フッターデザイン (AdSense＆完全一直線並び) */
        footer {
            background: var(--header-bg);
            color: var(--header-text);
            text-align: center;
            padding: 24px 20px;
            font-size: 0.85rem;
            margin-top: auto;
            border-top: 1px solid var(--border-color);
        }
        .footer-links {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }
        .footer-links a {
            color: inherit;
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.2s;
        }
        .footer-links a:hover { opacity: 0.8; text-decoration: underline; }
        footer p { margin: 6px 0; opacity: 0.85; line-height: 1.5; }

        /* 😸 猫の知恵袋 BBS専用スタイル */
        .board-post {
            border-bottom: 1px dashed var(--border-color);
            padding: 14px 0;
        }
        .board-post:last-child { border-bottom: none; }
        .board-header {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: #8c827a;
            margin-bottom: 4px;
        }
        .board-title { font-weight: 700; font-size: 1.1rem; color: #a26d46; margin-bottom: 4px; }
        .board-body { font-size: 0.95rem; line-height: 1.5; white-space: pre-wrap; }

/* ============================================================
   🖼️ 1. ギャラリー一覧（デフォルト・スマホ・iPad）
   ============================================================ */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-top: 18px;
}

.gallery-item {
    position: relative;
    aspect-ratio: 1 / 1;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    cursor: zoom-in;
    background: #f0f0f0;
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-item:hover img {
    transform: scale(1.05); /* ホバーで少し拡大 */
}

/* 🗑️ 削除ボタン（一覧時） */
.gallery-delete {
    position: absolute;
    top: 6px;
    right: 6px;
    background: rgba(220, 53, 69, 0.9);
    color: white;
    border: 2px solid white; /* 視認性アップ */
    border-radius: 50%;
    width: 26px;
    height: 26px;
    cursor: pointer;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    z-index: 10;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

/* ============================================================
   💻 2. PC版最適化（1024px以上）
   ============================================================ */
@media (min-width: 1024px) {
    /* モーダル自体の横幅を広げる */
    #gallery-upload-modal .modal-content,
    .gallery-modal-main,
    #gallery-container-parent {
        width: 90% !important;
        max-width: 1200px !important;
        margin: auto;
    }

    /* 5カラムに拡張 */
    .gallery-grid {
        grid-template-columns: repeat(5, 1fr) !important;
        gap: 15px !important;
        padding: 10px !important;
    }
}

/* ============================================================
   🔍 3. 拡大ズーム表示（custom-zoom-overlay）
   ============================================================ */
.custom-zoom-overlay {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100vw; height: 100vh;
    background-color: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(4px);
    z-index: 999999;
    justify-content: center;
    align-items: center;
    cursor: pointer; /* 背景クリックで閉じられる */
}

.zoom-card {
    position: relative;
    background: #fff;
    padding: 10px; /* 画像周りの白いフチ */
    border-radius: 12px;
    max-width: 90vw;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    cursor: default; /* カード内クリックでは閉じない */
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
}

.zoom-card img {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
    border-radius: 4px;
}

/* 🔥 拡大時のシンプルな×ボタン */
.simple-x-btn {
    position: absolute;
    top: -40px; /* カードの外側上部に配置 */
    right: 0px;
    background: transparent !important;
    border: none !important;
    color: #fff !important; /* 背景が暗いので白 */
    font-size: 2.5rem;
    cursor: pointer;
    line-height: 1;
    padding: 10px;
    z-index: 1000001;
    transition: opacity 0.2s;
}

.simple-x-btn:hover {
    opacity: 0.7;
    transform: scale(1.1);
}
        /* 🎸 BGMプレイヤーのミリ調整スタイル */
        .bgm-panel-wrapper { display: flex; flex-direction: column; gap: 16px; }
        #yt-player-box {
            width: 100%;
            height: 180px;
            border-radius: 12px;
            overflow: hidden;
            background: #000;
            display: none;
        }
        .bgm-input-row { display: flex; gap: 8px; margin-top: 6px; }
        .bgm-input-row input {
            flex: 1;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: var(--bg-color);
            color: var(--text-color);
        }
        .bgm-btn {
            padding: 8px 14px;
            border-radius: 8px;
            border: none;
            background: var(--header-bg);
            color: #ffffff;
            cursor: pointer;
            font-weight: bold;
        }

        /* 🥸 秘密機能：AI Quantum Terminal */
        .quantum-console {
            background: #0d0d0c;
            color: #39ff14;
            font-family: 'Courier New', Courier, monospace;
            padding: 20px;
            border-radius: 14px;
            border: 1px solid #39ff14;
            margin-top: 15px;
        }

        /* アニメーション */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes modalUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
   #category-select {
    display: none !important;
}

/* --- ✨ ここから追加：モーダル表示中の震えとバーを物理的に消す --- */
        body.modal-open {
            overflow: hidden !important; /* スクロールを根本から禁止 */
            height: 100vh !important;
            position: fixed; /* 画面を強制固定 */
            width: 100%;
        }

        #photo-modal {
            display: none; /* JSでflexに切り替える */
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            background: rgba(0, 0, 0, 0.9);
            cursor: pointer;
        }

#photo-modal img {
    max-width: 70vw !important;
    max-height: 70vh !important;
    object-fit: contain !important;
    transition: none !important;
    /* pointer-events: none; は、画像だけを透かしたい場合のみ残してください */
    display: block;
    margin: 0 auto;
}

/* 1. 秘密ボタンのレイアウト保持用 */
.secret-container {
    position: absolute;
    top: 15px;
    right: 55px;
    z-index: 10001; /* 他のUIやモーダルよりも常に最前面へ */
    display: flex;
    align-items: center;
    gap: 8px;
}

/* 2. 👽秘密ボタン：デザインと基本アニメーション */
.secret-btn {
    background: none;
    border: none;
    font-size: 1.8rem;
    cursor: pointer;
    filter: drop-shadow(0 0 2px rgba(0, 255, 0, 0.3));
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    padding: 5px;
    line-height: 1;
    user-select: none;
}

/* ホバー時の「見つけた感」を強調 */
.secret-btn:hover {
    transform: scale(1.4) rotate(15deg);
    filter: drop-shadow(0 0 12px #00ff00);
}

/* クリックした瞬間の手応え */
.secret-btn:active {
    transform: scale(0.9) rotate(-10deg);
    filter: brightness(1.5);
}

/* 3. 秘密モード時のテキストエリア（デジタル漢方薬・ダーク） */
.memo-secret-mode {
    background-color: #0d0d0d !important; /* 漆黒 */
    color: #00ff00 !important;           /* ネオン・グリーン */
    border: 2px solid #00ff00 !important;
    box-shadow: inset 0 0 15px rgba(0, 255, 0, 0.2), 0 0 10px rgba(0, 255, 0, 0.1) !important;
    font-family: 'Courier New', Courier, monospace; /* ハッカー・知的ツール風 */
    letter-spacing: 0.05em;
    caret-color: #00ff00; /* カーソルの色も統一 */
}

/* 4. 通常モードのテキストエリア微調整（知的・哲学的な黄色テーマを尊重） */
#memo-textarea {
    transition: background-color 0.5s ease, color 0.5s ease, border-color 0.5s ease, box-shadow 0.5s ease;
}

/* 5. 保存中・同期中のステータスアニメーション */
@keyframes saving-pulse {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(0.98); }
    100% { opacity: 1; transform: scale(1); }
}

.saving {
    animation: saving-pulse 1.2s ease-in-out infinite;
    color: #ffcc00 !important; /* 保存中は知的な黄色で強調 */
    font-weight: bold;
}

/* --- ✨ アニメーション定義 --- */
        /* 下からふわっと浮かび上がる共通の動き */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- 📝 メモ帳・モーダルの設定 --- */
        .modal-content {
            animation: fadeIn 0.3s ease-out; /* memo-openと共通なのでfadeInに統合可能です */
        }

        /* --- 🐾 掲示板（猫の知恵袋）の設定 --- */
        .board-post {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            /* 投稿が読み込まれた時に fadeIn を適用 */
            animation: fadeIn 0.4s ease forwards;
            transition: transform 0.2s ease, background 0.2s ease;
        }

        /* ホバー時に少しだけ浮き上がり、背景を少し明るくする演出 */
        .board-post:hover {
            transform: scale(1.01);
            background: rgba(255, 255, 255, 0.05);
        }
</style>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

</head>
<body>
<script>
function initAppState() {
  window.AppState = window.AppState || {};

  window.AppState.db ??= null;
  window.AppState.posts ??= [];
  window.AppState.board ??= [];
  window.AppState.gallery ??= [];

  window.AppState.currentPage ??= 1;
  window.AppState.perPage ??= 10;

  window.AppState.runtime ??= {
    isGearOpen: false,
    scrollLock: false,
    dbReady: false
  };
}

initAppState();
</script>

<header>
    <!-- ロゴ：クリックでリロード、ホバー時に少し動く -->
    <div class="logo" onclick="location.reload()" title="AIverse ホームへ">
        <span class="logo-icon">🐶</span> Alverse
    </div>

<!-- 検索バー：中央配置で視認性を向上 -->
<div class="search-container">
    <div class="search-wrapper">
        <input type="text" id="search-bar" placeholder="記事を瞬時に検索..." oninput="onSearchChange()">
    </div>
</div>

<!-- ナビゲーション -->
<div class="nav-icons">
    <button class="nav-btn" onclick="openModal('board-modal')" title="猫の知恵袋">😸</button>
    <button class="nav-btn" onclick="openModal('gallery-modal')" title="ギャラリー">🖼️</button>
    <button class="nav-btn" id="dark-mode-btn" title="ダークモード切替 (長押しで管理者ログイン)">🌜</button>

    <div class="dropdown">
        <button class="nav-btn gear-btn" onclick="toggleGearMenu(event)" title="メニュー">⚙️</button>

        <div id="gear-menu" class="dropdown-content">
            <button class="close-menu-btn" onclick="toggleGearMenu(event)">&times;</button>

            <!-- ADMIN（統合版） -->
            <div class="menu-group admin-only-group" id="admin-menu-section" style="display:none;">
                <p class="menu-label">ADMIN</p>

                <a href="#" onclick="openNewPost(); return false;">📝 新規記事投稿</a>
                <a href="#" onclick="exportData('posts'); return false;">💾 記事バックアップ</a>

                <div style="border-top:1px solid rgba(0,0,0,0.1); margin-top:8px; padding-top:8px;">
                    <a href="#" onclick="logoutAdmin(); return false;" style="color:#fa5252; font-weight:bold;">
                        🔓 管理ログアウト
                    </a>
                </div>
            </div>

            <!-- 通常メニュー -->
            <div class="menu-group">
                <a onclick="openModal('bgm-modal')">🎸 BGM</a>
                <a onclick="toggleLanguage()">🌐 自動翻訳切替</a>
                <a onclick="openModal('memo-modal')">📝 クラウドメモ</a>
                <a onclick="openModal('secret-modal')">🥸 秘密機能</a>
            </div>

        </div>
    </div>
</div>
</header>
<main>
    <div id="mainGrid"></div>
    <div id="pagination" class="pagination"></div>
</main>

<footer>
    <div class="footer-links">
        <a href="#" onclick="openModal('privacy-modal')">プライバシーポリシー</a> |
        <a href="#" onclick="openModal('contact-modal')">お問い合わせ</a> |
        <a href="https://www.youtube.com/@kenya896" target="_blank">📺 YouTube: ねこちゃんねる</a>
    </div>
    <p>当サイトはGoogle AdSenseによる広告配信を利用しています。ユーザー様の関心に基づいた適切な広告表示のためにCookie情報を活用することがあります。</p>
    <p>&copy; 2026 Alverse. All rights reserved.</p>
</footer>

<div id="board-modal" class="modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('board-modal')">×</button>
        <h2 style="margin-top:0;">😸 猫の知恵袋</h2>
        <p style="font-size:0.85rem; color:#8c827a; margin-bottom:15px;">
            生活の知恵から、リアルでは吐き出せない日常の愚痴、ちょっとした悩みまで。
        </p>

        <div style="background:var(--bg-color); padding:16px; border-radius:12px; border:1px solid var(--border-color); margin-bottom:20px;">
            <input type="text" id="board-title-input" placeholder="知恵のタイトル（任意）"
                   style="width:100%; padding:10px; border:1px solid var(--border-color); border-radius:6px; margin-bottom:10px; background:#fffef9; font-family:serif;">

            <textarea id="board-body-input" rows="3" placeholder="ここに本文を書き込んでください..."
                      style="width:100%; padding:10px; border:1px solid var(--border-color); border-radius:6px; background:#fffef9; font-family:serif; resize:none;"></textarea>

            <button onclick="submitBoardPost()" class="bgm-btn"
                    style="margin-top:10px; width:100%; padding:12px; background:#f1c40f; color:#fff; border:none; border-radius:8px; cursor:pointer; font-weight:bold;">
                知恵を共有する（送信）
            </button>
        </div>

        <div id="board-posts-container" style="max-height:50vh; overflow-y:auto; padding-right:8px;">
            </div>
    </div>
</div>
<div id="gallery-modal" class="modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('gallery-modal')">×</button>

        <h2 style="margin-top:0;">🖼️ フォトギャラリー</h2>
        <p style="font-size:0.85rem; color:#8c827a;">画像をアップロードして共有できます。画像をクリックすると拡大表示されます。</p>

        <input type="file" id="gallery-upload-file" accept="image/*" style="display:none;" onchange="uploadImage(this)">
<button type="button" onclick="document.getElementById('gallery-upload-file').click()" class="bgm-btn">
  📷 端末から画像をアップロード
</button>
        <div class="gallery-grid" id="gallery-container">
            <?php
            // セッション開始
            if (session_status() === PHP_SESSION_NONE) { session_start(); }

            // 管理者チェック
            $is_admin_check = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

            // 画像取得
            $directory = "uploads/";
            $images = glob($directory . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

            if ($images):
                $images = array_reverse($images);
                foreach ($images as $image_path):
                    $filename = basename($image_path);
            ?>
                <div class="gallery-item">
                    <img src="<?php echo $image_path; ?>" onclick="openGalleryZoom(this.src)">

                    <?php if ($is_admin_check): ?>
                        <button class="gallery-delete" onclick="deleteImage('<?php echo $filename; ?>')">×</button>
                    <?php endif; ?>
                </div>
            <?php
                endforeach;
            endif;
            ?>
        </div>
    </div>
</div>
<div id="gallery-zoom-modal" class="custom-zoom-overlay" onclick="closeGalleryZoom(event)">
    <div class="zoom-card" onclick="event.stopPropagation()">
        <img id="zoomed-image" src="" alt="拡大画像">

        <button class="simple-x-btn" onclick="closeGalleryZoom(event)">×</button>
    </div>
</div>
<div id="gallery-zoom-modal" class="custom-zoom-overlay" onclick="closeGalleryZoom(event)">
    <div class="zoom-card" onclick="event.stopPropagation()">
        <img id="zoomed-image" src="" alt="拡大画像">

        <button class="simple-x-btn" onclick="closeGalleryZoom(event)">×</button>
    </div>
</div>

<div id="memo-modal" class="modal">
    <div class="modal-content" style="position: relative; overflow: hidden;">
        <!-- 右上の閉じるボタン -->
        <button class="modal-close" onclick="closeModal('memo-modal')">×</button>

        <!-- ヘッダー部分：タイトルと👽秘密ボタン -->
<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
    <h2 id="memo-title" style="margin: 0; font-size: 1.5rem;">📝 マイ・スピードメモ</h2>
    <button id="secret-alien-btn" class="secret-btn" onclick="openModal('secret-modal')" title="👽秘密の共有" style="padding: 0; background: none; border: none; font-size: 1.5rem; cursor: pointer; line-height: 1;">👽</button>
</div>
        <!-- 説明文 -->
        <p id="memo-status" style="font-size: 0.85rem; color: #8c827a; margin-bottom: 12px;">

        </p>

        <!-- メモ帳本体 -->
        <textarea id="memo-textarea"
            placeholder="ここに自由にアイデアを書き残してください..."
            style="width: 100%; height: 280px; padding: 15px; border-radius: 12px; border: 2px solid var(--border-color); background: var(--bg-color); color: var(--text-color); font-family: inherit; line-height: 1.6; resize: none; transition: all 0.3s ease; box-sizing: border-box;"></textarea>

        <!-- 下部の操作案内 -->
        <div id="memo-footer" style="margin-top: 10px; text-align: right; font-size: 0.75rem; color: #bbb;">
            <span id="save-indicator"></span>
        </div>
    </div>
</div>
<div id="bgm-modal" class="modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('bgm-modal')">×</button>
        <h2 style="margin-top:0;">🎸 BGMステーション</h2>
        <div class="bgm-panel-wrapper">
            <div id="yt-player-box">
                <div id="yt-player-frame"></div>
            </div>

            <div style="background:var(--bg-color); padding:12px; border-radius:10px; border:1px solid var(--border-color);">
                <label style="font-size:0.85rem; font-weight:bold;">🎵 曲を追加 (YouTube URL)</label>
                <div class="bgm-input-row">
                    <input type="text" id="track-url-input" placeholder="YouTube動画URLを貼り付け">
                    <button onclick="addNewTrack()" class="bgm-btn">追加</button>
                </div>
            </div>

            <div style="background:var(--bg-color); padding:12px; border-radius:10px; border:1px solid var(--border-color);">
                <label style="font-size:0.85rem; font-weight:bold;">📁 プレイリストを作成</label>
                <div class="bgm-input-row">
                    <input type="text" id="playlist-name-input" placeholder="リスト名を入力">
                    <button onclick="createNewPlaylist()" class="bgm-btn">作成</button>
                </div>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; gap:10px;">
                <select id="playlist-selector" onchange="onPlaylistSelectorChange()" style="flex:1; padding:8px; border-radius:8px; border:1px solid var(--border-color); background:var(--bg-color); color:var(--text-color);"></select>
                <button onclick="renameActivePlaylist()" class="bgm-btn" style="padding:8px 12px; font-size:0.85rem;">リスト名変更</button>
            </div>

            <div style="border-top:1px solid var(--border-color); padding-top:10px;">
                <h4 style="margin:0 0 8px 0; font-size:0.9rem;">📀 トラック一覧 (クリックで再生)</h4>
                <div id="bgm-tracks-list" style="max-height:150px; overflow-y:auto; display:flex; flex-direction:column; gap:6px;"></div>
            </div>
        </div>
    </div>
</div>

<div id="secret-modal" class="modal">
    <div class="modal-content" style="background:#090909; color:#39ff14; border:1px solid #39ff14;">
        <button class="modal-close" onclick="closeModal('secret-modal')" style="color:#39ff14;">×</button>
        <h2 style="margin-top:0; color:#39ff14; display:flex; align-items:center; gap:8px;">🥸 AI Neko-Core 仮想スキャナー</h2>
        <p style="color:#80ff80; font-size:0.85rem;">ブラウザのヒープメモリ空間（仮想スタック）をディープに診断し、動作不良を引き起こすバグを駆除します。</p>

        <div class="quantum-console">
            <div id="stabilizer-log" style="font-size:0.85rem; line-height:1.6; height:120px; overflow-y:auto; margin-bottom:10px;">＞ スキャンエンジン待機中...</div>
            <div style="background:#222; border-radius:6px; height:16px; overflow:hidden;">
                <div id="stabilizer-progress-bar" style="background:#39ff14; width:0%; height:100%; transition:width 0.1s;"></div>
            </div>
            <button onclick="executeStabilizerScan()" style="margin-top:15px; width:100%; background:#39ff14; color:#000; font-weight:bold; border:none; padding:10px; border-radius:8px; cursor:pointer; font-family:inherit;">🚀 バグスキャンを実行</button>
        </div>

        <div id="secret-wisdom-box" style="margin-top:15px; background:rgba(57,255,20,0.1); border:1px solid rgba(57,255,20,0.3); padding:12px; border-radius:8px; display:none; text-align:center;">
            <div style="font-weight:bold; font-size:0.8rem; text-transform:uppercase; color:#ffdf7a; margin-bottom:4px;">😸 猫の知恵袋 - 格言</div>
            <div id="secret-wisdom-text" style="font-size:0.9rem;"></div>
        </div>
    </div>
</div>

<div id="detail-modal" class="modal">
    <div class="modal-content" style="padding: 24px; max-width: 680px;">
        <button class="modal-close" onclick="closeModal('detail-modal')">×</button>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; gap: 10px;">
            <div id="detail-category-container" style="flex: 1;">
                <select id="detail-category-selector" style="width: 100%; padding: 6px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--card-bg); color: #a26d46; font-size: 0.8rem; font-weight: 700;"></select>
            </div>
            <p id="detail-date" style="font-size:0.85rem; color:#8c827a; margin: 0;"></p>
        </div>
        <h1 id="detail-title" style="margin:0 0 16px 0; font-size: 1.5rem; font-weight: 800; line-height: 1.35; color: var(--text-color);"></h1>
        <hr style="border:0; border-top:1px solid var(--border-color); margin-bottom:20px;">
        <div id="detail-body" style="line-height:1.9; font-size:1.1rem; white-space:pre-wrap; color: var(--text-color);"></div>
    </div>
</div>

<div id="admin-modal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <button class="modal-close" onclick="closeModal('admin-modal')">&times;</button>
        <h2 style="margin-top:0;">🔑 管理者センター</h2>

        <div style="background: #fff4e6; padding: 15px; border-radius: 10px; border: 1px solid #ffd8a8; margin-bottom: 20px;">

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <p style="font-size: 0.8rem; font-weight: bold; color: #d9480f; margin: 0;">CONTENTS (記事投稿・編集)</p>
<button onclick="setNewPostMode()"
        style="padding: 5px 10px; background: #4dabf7; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.7rem;">
    🆕 新規作成モード
</button>
            </div>

            <input type="hidden" id="admin-post-id-input">

            <div style="background: #f8f9fa; padding: 10px; border-radius: 8px; margin-bottom: 15px; border: 1px dashed #ced4da;">
                <p style="font-size: 0.7rem; font-weight: bold; color: #868e96; margin: 0 0 8px 0; text-align: center;">LIVE PREVIEW</p>
                <div id="admin-preview-area" style="display: flex; justify-content: center; transform: scale(0.85); margin: -20px 0;">
                    </div>
            </div>

            <input type="text" id="admin-image-input" placeholder="画像URLを入力 (空欄でデフォルト)"
                   style="width:100%; padding:10px; margin-bottom:10px; border:1px solid #ccc; border-radius:5px; box-sizing:border-box;">

            <input type="text" id="admin-title-input" placeholder="タイトルを入力" 
                   style="width:100%; padding:10px; margin-bottom:10px; border:1px solid #ccc; border-radius:5px; box-sizing:border-box;">
        </div>

<select id="admin-category-input" style="width:100%; padding:10px; margin-bottom:10px; border:1px solid #ccc; border-radius:5px;">
    <option value="news">📢 ニュース</option>
    <option value="ent">🎭 エンタメ</option>
    <option value="game">🎮 ゲーム</option>
    <option value="anime">🎨 アニメ・漫画</option>
    <option value="movie">🎬 映画・ドラマ</option>
    <option value="music">🎵 音楽</option>
    <option value="science">🧪 科学</option>
    <option value="space">🚀 宇宙</option>
    <option value="tech">💻 テクノロジー</option>
    <option value="ai">🤖 AI</option>
    <option value="math">📐 数学</option>
    <option value="phi">📜 哲学・思想</option>
    <option value="hist">🏛️ 歴史</option>
    <option value="politics">⚖️ 政治・社会</option>
    <option value="biz">📈 経済・ビジネス</option>
    <option value="health">🏥 医療・健康</option>
    <option value="psych">🧠 心理学</option>
    <option value="edu">📖 教育・学習</option>
    <option value="mystery">🔍 ミステリー・オカルト</option>
    <option value="urban">⛩️ 都市伝説</option>
    <option value="uma">👾 未確認生物</option>
    <option value="nature">🌿 自然・生物</option>
    <option value="food">🍳 食べ物・料理</option>
    <option value="life">🏠 生活・暮らし</option>
    <option value="culture">✈️ 旅行・文化</option>
    <option value="sports">⚽ スポーツ</option>
    <option value="internet">🌐 インターネット</option>
    <option value="youtube">🎥 YouTube・配信</option>
    <option value="trivia">💡 雑学・トリビア</option>
    <option value="column">📝 コラム</option>
    <option value="special">✨ 特集</option>
</select>
            <textarea id="admin-body-input" placeholder="本文を入力" style="width:100%; height:150px; padding:10px; margin-bottom:10px; border:1px solid #ccc; border-radius:5px; box-sizing:border-box;"></textarea>

            <label style="display:flex; align-items:center; gap:8px; font-size:0.9rem; margin-bottom:15px; cursor:pointer;">
                <input type="checkbox" id="admin-public" checked> 記事を「公開」にする
            </label>

            <div style="display:flex; gap:10px;">
                <button onclick="savePostFromAdmin()" style="flex:2; padding:12px; background:#ff9800; color:white; border:none; border-radius:5px; font-weight:bold; cursor:pointer;">🚀 記事を保存</button>
                <button onclick="clearAdminForm()" style="flex:1; padding:12px; background:#8c827a; color:white; border:none; border-radius:5px; cursor:pointer;">リセット</button>
            </div>
        </div>

        <div style="background: #f1f3f5; padding: 15px; border-radius: 10px; border: 1px solid #dee2e6;">
            <p style="font-size: 0.8rem; font-weight: bold; color: #495057; margin: 0 0 10px 0;">SYSTEM CONFIG (設定・メモ)</p>
            <textarea id="memo-textarea" style="width:100%; height:60px; margin-bottom:10px; font-family:monospace; padding:8px; box-sizing:border-box;" placeholder="設定用メモ..."></textarea>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px;">
                <button onclick="db.memo = document.getElementById('memo-textarea').value; saveToLocalStorage(); alert('設定を保存しました');" style="padding:8px; background:white; border:1px solid #adb5bd; border-radius:5px; cursor:pointer;">💾 設定を保存</button>
                <button onclick="document.getElementById('memo-textarea').value = '';" style="padding:8px; background:white; border:1px solid #adb5bd; border-radius:5px; cursor:pointer;">🗑️ メモ消去</button>
            </div>
        </div>
    </div>
</div>

<div id="privacy-modal" class="modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('privacy-modal')">×</button>
        <h2>プライバシーポリシー</h2>
        <p>当サイト「Alverse」は、Cookieを利用して最適化された広告を配信しますが、これらはブラウザ設定から無効化可能です。個人情報は厳重に保護されます。</p>
    </div>
</div>

<div id="contact-modal" class="modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('contact-modal')">×</button>
        <h2>お問い合わせ</h2>
        <p>当サイトに関するご意見等は、以下までご連絡ください。</p>
        <p>📨 <strong>info@aiverse-web.com</strong></p>
    </div>
</div>

<script src="https://www.youtube.com/iframe_api"></script>

<script>

   // ----------------------------- 🗄️ データ構造 (初期値) -----------------------------
    const INITIAL_ALVERSE_DB = {
        isAdmin: false,
        theme: 'light',
        posts: [
            { id: 1, title: "埃を極限まで吸い寄せるナノデバイス", category: "発明王への道", body: "超電磁マトリクスを空間に形成し、生活空間に散らばる微粒子、チリ、埃をミリ秒単位で一箇所に安全に集めるためのクリーン技術です。アレルギーに苦しむ人たちのために作られました。", image: "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=600&q=80", date: "2026-05-01", public: true },
            { id: 2, title: "くぐるだけで免疫が再起動する量子ドーム", category: "発明王への道", body: "微弱なヘルツ共鳴波を発生させる特殊なゲート。通るだけで、人間の基礎体温と細胞の自己回復能力を心地よく覚醒させる未来型ヘルスケアシステム。ねこの温もりを科学的に応用しています。", image: "https://images.unsplash.com/photo-1507668077129-56e32842fceb?auto=format&fit=crop&w=600&q=80", date: "2026-05-02", public: true },
            { id: 3, title: "太陽光を120%増幅させるレンズパネル", category: "グリーンエネルギー", body: "窓に貼り付けるだけで、外部から入る昼光の明るさを全方位に反射・倍加させ、暗い部屋を一瞬でぽかぽかの快適空間に変える省エネフィルム技術。", image: "https://images.unsplash.com/photo-1473968512647-3e447244af8f?auto=format&fit=crop&w=600&q=80", date: "2026-05-03", public: true }
        ],
        board: [
            { id: 1, title: "Alverse掲示板へようこそ！", body: "自由にお使いいただけるBBSです！なんでも書き込んでいってくださいにゃ。😸", date: "2026-05-07 00:00" }
        ],
        gallery: [],
        memo: "",
        playlists: [
            { name: "作業用ループ", tracks: [{ id: "dQw4w9WgXcQ", title: "Never Gonna Give You Up" }] }
        ],
        activePlaylistIdx: 0
    };

    // クッキー削除に耐えるlocalStorage保存 (現状維持)
    let db = JSON.parse(localStorage.getItem('alverse_database_engine_v3')) || INITIAL_ALVERSE_DB;

    function saveToLocalStorage() {
        try {
            localStorage.setItem('alverse_database_engine_v3', JSON.stringify(db));
        } catch (e) {
            alert("⚠️ ストレージ容量上限を超えました。ギャラリー画像を削除してください。");
        }
    }

    // ----------------------------- 📰 記事・検索・ページネーション制御 -----------------------------
    let currentPage = 1;
    const postsPerPage = 10;
    let computedPosts = [];

    function onSearchChange() {
        currentPage = 1;
        renderArticlesGrid();
    }

    function renderArticlesGrid() {
        const query = document.getElementById('search-bar').value.toLowerCase().trim();
        const grid = document.getElementById('mainGrid');
        grid.innerHTML = '';

        computedPosts = db.posts.filter(p => {
            const matchesQuery = p.title.toLowerCase().includes(query) || p.body.toLowerCase().includes(query) || p.category.toLowerCase().includes(query);
            return matchesQuery && (p.public || db.isAdmin);
        });

        const totalPages = Math.ceil(computedPosts.length / postsPerPage);
        const startIndex = (currentPage - 1) * postsPerPage;
        const endIndex = startIndex + postsPerPage;
        const pageSelection = computedPosts.slice(startIndex, endIndex);

        if (pageSelection.length === 0) {
            grid.innerHTML = `<div style="grid-column:1/-1; text-align:center; padding: 60px 20px; color:#8c827a;">合致する記事が見つかりません。</div>`;
            document.getElementById('pagination').innerHTML = '';
            return;
        }

        pageSelection.forEach(p => {
            const card = document.createElement('div');
            card.className = 'post-card';
            card.onclick = () => showArticleDetail(p.id);

            const fallbackImg = 'https://images.unsplash.com/photo-1518791841217-8f162f1e1131?auto=format&fit=crop&w=600&q=80';
            const imageUrl = p.image || fallbackImg;

            const adminPanelHTML =
    (typeof db !== 'undefined' && db.isAdmin) ? `
                <div style="display:flex; gap:4px; margin-top:8px;" onclick="event.stopPropagation();">
                    <span style="background:${p.public ? '#28a745' : '#6c757d'}; color:white; padding:2px 6px; border-radius:4px; font-size:0.7rem;">${p.public ? '公開中' : '下書き'}</span>
                    <button onclick="editArticle(${p.id})" style="background:#007bff; color:white; border:none; padding:2px 6px; border-radius:4px; font-size:0.7rem;">編集</button>
                    <button onclick="deleteArticle(${p.id})" style="background:#dc3545; color:white; border:none; padding:2px 6px; border-radius:4px; font-size:0.7rem;">削除</button>
                </div>
            ` : '';

// マスターリストから日本語ラベルと絵文字を検索
            const catObj = (typeof AIVERSE_CATEGORIES !== 'undefined') ? AIVERSE_CATEGORIES.find(c => c.id === p.category) : null;
            const currentLabel = catObj ? catObj.label : p.category;

            card.innerHTML = `
                <img src="${imageUrl}" alt="${p.title}" onerror="this.src='${fallbackImg}'">
                <div class="post-content">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap;">
                        <span class="post-category">${currentLabel}</span>
                    </div>
                    <div class="post-title">${p.title}</div>
                    ${adminPanelHTML}
                    <div class="post-date">${p.date}</div>
                </div>
            `;
            grid.appendChild(card);
        });

        buildPaginationControls(totalPages);
    }

    function buildPaginationControls(totalPages) {
        const pagBox = document.getElementById('pagination');
        pagBox.innerHTML = '';
        if (totalPages <= 1) return;

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.innerText = i;
            btn.className = `page-btn ${i === currentPage ? 'active' : ''}`;
            btn.onclick = () => {
                currentPage = i;
                renderArticlesGrid();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            };
            pagBox.appendChild(btn);
        }
    }
    // 記事の詳細表示 (見え方改善＆カテゴリー変更機能)
    let currentDetailArticleId = null; // カテゴリー保存用

// ✅ 修正後（1205行目からの安全な形）
function showArticleDetail(id) {
    currentDetailArticleId = id;

    // db や db.posts が存在しない場合の安全装置
    if (!db || !db.posts) return;

    // db.posts がオブジェクト（連想配列）だった場合でも安全に配列に変換して探す
    const postsArray = Array.isArray(db.posts) ? db.posts : Object.values(db.posts);

    const p = postsArray.find(item => item && item.id === id);
    if (!p) return;
    // 🖼️ 1. 画像の表示処理 (HTMLになければ自動でタイトルの上に追加)
    let imgEl = document.getElementById('detail-image');
    if (!imgEl) {
        imgEl = document.createElement('img');
        imgEl.id = 'detail-image';
        // 💡 画像のスタイル設定（横幅いっぱいに広げ、角を丸くし、高さを制限して綺麗に収めます）
        imgEl.style = "width: 100%; border-radius: 12px; margin-bottom: 15px; max-height: 250px; object-fit: cover; display: none;";
        const titleEl = document.getElementById('detail-title');
        if (titleEl) {
            titleEl.parentNode.insertBefore(imgEl, titleEl);
        }
    }

    // 画像URLがデータベースにあれば表示、なければ非表示に
    if (p.image) {
        imgEl.src = p.image;
        imgEl.style.display = 'block';
    } else {
        imgEl.style.display = 'none';
    }

 // 📂 2. カテゴリーのドロップダウン設定
    const selector = document.getElementById('detail-category-selector');
    if (selector) {
        selector.innerHTML = '';
        const allCategories = [...new Set(db.posts.map(post => post.category))].filter(Boolean);

        allCategories.forEach(cat => {
            const opt = document.createElement('option');
            opt.value = cat;
            opt.innerText = cat;
            if (cat === p.category) opt.selected = true;
            selector.appendChild(opt);
        });
        // 👑 【ここがポイント】管理者なら「変更可能な枠線付き」、一般ユーザーなら「ただの文字」に見せる
        if (db.isAdmin) {
            selector.style.border = "1px solid #555";
            selector.style.background = "#222";
            selector.style.pointerEvents = "auto";
            selector.style.appearance = "auto"; // 矢印を出す
            selector.style.padding = "4px 8px";
            selector.style.color = "var(--accent-color)";
        } else {
            selector.style.border = "none";
            selector.style.background = "transparent";
            selector.style.pointerEvents = "none"; // クリックできないようにする
            selector.style.appearance = "none";     // 矢印（プルダウンの三角マーク）を消す
            selector.style.padding = "0";
            selector.style.color = "var(--accent-color)";
            selector.style.fontWeight = "bold";
        }

// カテゴリー 変更イベント（管理者がドロップダウンを変えた時だけ動作）
selector.onchange = function() {
    if (currentDetailArticleId) {
        const newCategory = this.value;
        const post = db.posts.find(item => item.id === currentDetailArticleId);
        if (post && post.category !== newCategory) {
            if (confirm("この 記事のカテゴリー を変更しますか？")) {
                post.category = newCategory;
                saveToLocalStorage();
                renderArticlesGrid(); // メイン画面を再描画
            } else {
                this.value = post.category; // キャンセル時は元に戻す
            }
        }
    }
};

    // ✍ 3. タイトル、日付、本文の書き込み
    document.getElementById('detail-title').innerText = p.title;
    document.getElementById('detail-body').innerHTML = p.body;
    openModal('detail-modal');
  }
}

// ----------------------------- 🌛 ダークモード＆管理者長押し (整理版) -----------------------------
let pressTimer;

const dmBtn = document.getElementById('dark-mode-btn');
if (dmBtn) {

    dmBtn.addEventListener('pointerdown', function (e) {
        e.preventDefault();

        pressTimer = setTimeout(() => {

            const pass = prompt("🔑 パスワードを入力してください：");

            if (pass === "nekosuke101") {

                // 管理者ON
                db.isAdmin = true;
                saveToLocalStorage();

                // 画面更新（ここは先にやる）
                renderArticlesGrid();

                // UI更新は「遅延させてDOM確定後」
                setTimeout(() => {
                    const logout = document.getElementById('admin-logout-dynamic');
                    if (logout) logout.style.display = 'block';
                }, 0);

                alert("認証成功：管理者コントロール解放！");
                openModal('admin-modal');

            } else if (pass !== null) {
                alert("パスワードが違います。");
            }

        }, 1100);
    });

    dmBtn.addEventListener('pointerup', function () {
        clearTimeout(pressTimer);
    });

    dmBtn.addEventListener('pointerleave', function () {
        clearTimeout(pressTimer);
    });

    dmBtn.addEventListener('click', function () {

        const theme =
            document.body.getAttribute('data-theme') === 'dark'
                ? 'light'
                : 'dark';

        document.body.setAttribute('data-theme', theme);

        db.theme = theme;
        saveToLocalStorage();
    });
}
// --- ⚙️ 設定: Firebase BBSパス ---
const BBS_ENDPOINT = 'https://alverse-project-default-rtdb.firebaseio.com/alverse_pro_v3.json';
// --- 🛡️ セキュリティ & ユーティリティ ---
function escapeHTML(str) {
    if (!str) return '';
    return str.replace(/[&<>'"]/g, m => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        "'": '&#39;',
        '"': '&quot;'
    }[m]));
}

function linkify(text) {
    const urlRegex = /(https?:\/\/[^\s]+)/g;
    return text.replace(urlRegex, url => `<a href="${url}" target="_blank" rel="noopener noreferrer" style="color:#4dabf7; text-decoration:underline;">${url}</a>`);
}

/**
 * 🔄 データの取得（Firebaseから最新の記事を読み込む）
 */
async function loadBoardFromServer() {
    const container = document.getElementById('board-posts-container');
    try {
        const response = await fetch(BBS_ENDPOINT);
        if (!response.ok) throw new Error(`status: ${response.status}`);
        const data = await response.json();

        // データを配列に変換し、最新順に並べ替える
        db.board = data ? Object.keys(data).map(key => ({
            ...data[key],
            fbKey: key
        })).sort((a, b) => (b.timestamp || 0) - (a.timestamp || 0)) : [];

        renderBoard(); // 画面に描画
        console.log("🐾 掲示板データを同期しました");
    } catch (e) {
        console.error("取得失敗:", e);
        if (container) container.innerHTML = `<div style="text-align:center; color:#fa5252; padding:20px;">知恵の読み込みに失敗しました😿</div>`;
    }
}
/**
 * 🎨 掲示板のレンダリング（見た目を作る）
 */
function renderBoard() {
    const container = document.getElementById('board-posts-container');
    if (!container) return;
    container.innerHTML = '';

    if (db.board.length === 0) {
        container.innerHTML = `<div style="text-align:center; color:#8c827a; padding:40px; border:1px dashed #444; border-radius:12px;">まだ知恵がありません🐾</div>`;
        return;
    }

    const fragment = document.createDocumentFragment();
    db.board.forEach((item, index) => {
        const itemDiv = document.createElement('div');
        itemDiv.className = 'board-post';

        const safeTitle = escapeHTML(item.title);
        const safeBody = linkify(escapeHTML(item.body));

        // 管理者用のボタン（isAdminがtrueの時だけ表示）
const adminControls = db.isAdmin ? `
    <div style="display:flex; gap:10px; margin-top:12px; border-top:1px solid rgba(255,255,255,0.1); padding-top:12px;">
        <button onclick="editBoardEntry('${item.fbKey}')" style="background:#228be6; color:white; border:none; border-radius:6px; padding:6px 12px; cursor:pointer; font-size:0.75rem;">📝 編集</button>
        <button onclick="deleteBoardEntry('${item.fbKey}')" style="background:#fa5252; color:white; border:none; border-radius:6px; padding:6px 12px; cursor:pointer; font-size:0.75rem;">🗑️ 削除</button>
    </div>` : '';
        itemDiv.innerHTML = `
            <div style="display:flex; justify-content:space-between; font-size:0.75rem; color:#8c827a; margin-bottom:8px;">
                <span>No.${db.board.length - index} <strong>名無しにゃんこ</strong></span>
                <span>${item.date || ''}</span>
            </div>
            <div style="font-weight:bold; color:#ffcc33; margin-bottom:10px; font-size:1.1rem;">🐾 ${safeTitle}</div>
            <div style="line-height:1.7; font-size:0.95rem; white-space:pre-wrap; color:var(--text-color, #444); word-break:break-word;">${safeBody}</div>
            ${adminControls}
        `;
        fragment.appendChild(itemDiv);
    });
    container.appendChild(fragment);
}

/**
 * 📮 新規投稿（ボタンが押せるように修正）
 */
async function submitBoardPost() {
    const titleIn = document.getElementById('board-title-input');
    const bodyIn = document.getElementById('board-body-input');
    const submitBtn = document.querySelector('.board-submit-btn');

    if (!titleIn || !bodyIn) return;

    const titleVal = titleIn.value.trim();
    const bodyVal = bodyIn.value.trim();

    if (titleVal.length < 1 || bodyVal.length < 1) return alert("内容を入力してくださいにゃ。");

    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerText = "🐾 送信中...";
    }

    const newPost = {
        title: titleVal,
        body: bodyVal,
        date: new Date().toLocaleString('ja-JP'),
        timestamp: Date.now()
    };

try {
        // 🌟 修正：不確定な定数（BBS_ENDPOINT）を捨て、正規のアジアRTDBのURLを直接指定します！
        const res = await fetch('https://alverse-project-default-rtdb.asia-southeast1.firebasedatabase.app/alverse_pro_v3/board.json', {
            method: 'POST',
            body: JSON.stringify(newPost)
        });
        if (!res.ok) throw new Error();
        titleIn.value = '';
        bodyIn.value = '';
        await loadBoardFromServer(); // 投稿後にデータを再取得
        alert("知恵を共有しました！🐾");
    } catch (e) {
        alert("送信に失敗しました😿 サーバーの状態を確認してください。");
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerText = "知恵を共有する (送信)";
        }
    }
}

// ----------------------------- 🖼️ フォトギャラリー (同期・自動更新版) -----------------------------

// 1. サーバーから画像リストを取得（キャッシュ対策済み）
async function loadServerGallery() {
    try {
        // ?t=... を付けてブラウザキャッシュを強制回避
        const response = await fetch(`gallery.json?t=${Date.now()}`);
        if (response.ok) {
            const serverData = await response.json();
            db.gallery = serverData || []; // サーバーの最新リストを反映
            renderGallery();
        }
    } catch (e) {
        console.error("ギャラリーの読み込み失敗:", e);
    }
}

    // 2. ギャラリーの描画
    function renderGallery() {
        const container = document.getElementById('gallery-container');
        if (!container) return;
        container.innerHTML = '';

        if (!db.gallery || db.gallery.length === 0) {
            container.innerHTML = `<p style="grid-column:1/-1; text-align:center; color:#8c827a;">画像はありません。</p>`;
            return;
        }

        db.gallery.forEach((g, index) => {
            const div = document.createElement('div');
            div.className = 'gallery-item';
            div.onclick = () => openGalleryZoom(g.src);
            div.innerHTML = `
                <img src="${g.src}" alt="Photo">
                <button class="gallery-delete" onclick="event.stopPropagation(); removeGalleryImage(${index})">×</button>
            `;
            container.appendChild(div);
        });
    }

// 3. 拡大機能
function openGalleryZoom(src) {
    const zoomImg = document.getElementById('zoomed-image');
    const modal = document.getElementById('gallery-zoom-modal');
    if (zoomImg && modal) {
        zoomImg.src = src;

        // 既存の関数で開く
        openModal('gallery-zoom-modal');

        // ★念のため：スクロールを止める
        document.body.style.overflow = 'hidden';
    }
}

// 拡大専用の閉じる関数（これに差し替え）
function closeGalleryZoom() {
    const modal = document.getElementById('gallery-zoom-modal');
    if (modal) {
        modal.style.display = 'none'; // 強制非表示
        document.body.style.overflow = ''; // スクロール復帰
    }
}

    // 4. アップロード機能 (サーバー保存版)
    async function uploadImage(input) {
        const file = input.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);

        try {
            const response = await fetch('upload.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) throw new Error('アップロード失敗');

            const data = await response.json();

            // サーバーのリストを再取得して最新状態にする
            await loadServerGallery();

        } catch (error) {
            console.error(error);
            alert("保存に失敗しました。PHPと権限設定を確認してください。");
        }
    }

// 5. 削除機能（サーバー同期・ファイル名＆インデックス両方送信版🐾）
async function removeGalleryImage(index) {
    // 💡 安全装置：対象の画像データを特定し、ファイル名を抜き出す
    const targetImage = db.gallery[index];
    if (!targetImage || !targetImage.src) {
        alert("エラー：削除対象の画像データが見つかりません。");
        return;
    }

    // 例: "uploads/image.jpg" から "image.jpg" だけを抽出（フルパスでも動くように考慮）
    const filename = targetImage.src.split('/').pop();

    if (confirm(`サーバーからこの画像（${filename}）を完全に削除しますか？`)) {
        try {
            // 🚀 PHP側が「index」を欲しがっても「filename」を欲しがってもいいように両方送る！
            const response = await fetch('delete_gallery.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    index: index,
                    filename: filename,
                    file: filename // 念のため別名でも仕込んでおくお守り
                })
            });

            const result = await response.json();

            if (result.success) {
                // サーバーで消せたら、最新のリストを再取得して表示を更新
                await loadServerGallery();
                console.log("📸 サーバー上の画像ギャラリーデータを更新しました🐾");
            } else {
                alert('削除に失敗しました: ' + result.message);
            }
        } catch (e) {
            console.error("通信エラー:", e);
            alert('サーバーとの通信に失敗しました。');
        }
    }
}
    // 最後に：ページ読み込み時に実行
    loadServerGallery();

    // ----------------------------- 📝 自動保存メモ帳 (現状維持) -----------------------------
    function saveMemo() {
        db.memo = document.getElementById('memo-textarea').value;
        saveToLocalStorage();
    }
    let player;
    let isPlayerLoaded = false;
    let activeTrackIdx = 0;
   // サーバーから読み込み（🌟Firebase初期化タイミング完全同調版）
    async function old_loadBgmFromServer() {
        try {
// 互換性の高いルートでFirebaseから直接データを1撃で引っ張る
const snapshot = await get(ref(db, "alverse_pro_v3/playlist")); // 👈 sなしのplaylistにする！
             if (snapshot.exists()) {
                 const data = snapshot.val();
                // [0, 1] の配列・オブジェクト構造を安全に展開
                if (Array.isArray(data)) {
                    db.playlists = data.filter(item => item !== null);
                } else if (typeof data === 'object') {
                    db.playlists = Object.values(data);
                } else {
                    db.playlists = [];
                }

                initBgmPanel();
                console.log("☁️ FirebaseからBGMデータを完全同期しました🐾");
                return;
            }
        } catch (e) {
            console.error("Firebase BGM読み込みエラー:", e);
        }

        if (!db.playlists || db.playlists.length === 0) {
            db.playlists = [{ name: "マイリスト", tracks: [] }];
            initBgmPanel();
        }
    }
// 🌟 1. パネル初期化の窓口化
    window.initBgmPanel = function() {
        const selector = document.getElementById('playlist-selector');
        if (!selector) return;
        selector.innerHTML = '';
        if (!db.playlists) db.playlists = [];
        db.playlists.forEach((pl, index) => {
            const opt = document.createElement('option');
            opt.value = index; opt.innerText = pl.name;
            if (index === db.activePlaylistIdx) opt.selected = true;
            selector.appendChild(opt);
        });
        window.renderBgmTracks(); // 窓口化した関数を呼び出す
    }
// 🎵 追加ボタンの未定義エラーを直す関数
    window.addNewTrack = async function() {
        const input = document.querySelector('input[placeholder*="YouTube動画URL"]');
        if (!input || !input.value.trim()) return;

        const url = input.value.trim();
        let videoId = "";
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        const match = url.match(regExp);
        if (match && match[2].length === 11) { videoId = match[2]; } else { alert("URLが不正です🐾"); return; }

        const currentPlIdx = db.activePlaylistIdx !== undefined ? db.activePlaylistIdx : 0;
        if (!db.playlists) db.playlists = [];
        if (!db.playlists[currentPlIdx]) db.playlists[currentPlIdx] = { name: "極秘リスト", tracks: [] };
        if (!db.playlists[currentPlIdx].tracks) db.playlists[currentPlIdx].tracks = [];

        db.playlists[currentPlIdx].tracks.push({ id: videoId, title: "新曲 (" + videoId + ")" });
        input.value = "";

        window.renderBgmTracks(); // 画面を再描画

// 🔥 Firebase（sなしのplaylist部屋）に正しい親子構造で上書き保存！
// 🚀 開通したモジュール世界の窓口へ、安全にデータを届ける！
        if (typeof window.saveBgmToFirebase === 'function') {
            await window.saveBgmToFirebase(db.playlists);
        } else {
            console.error("Firebase保存窓口が見つかりませんにゃ");
        }
    };

    // 🎵 2. 曲名を変更する関数（大復活！）
    window.editTrackName = async function(index) {
        const currentPlIdx = db.activePlaylistIdx !== undefined ? db.activePlaylistIdx : 0;
        const pl = db.playlists[currentPlIdx];
        if (!pl || !pl.tracks || !pl.tracks[index]) return;

        const newTitle = prompt("新しい曲名を入力してくださいにゃ：", pl.tracks[index].title);
        if (newTitle === null || !newTitle.trim()) return;

        pl.tracks[index].title = newTitle.trim();
        window.renderBgmTracks(); // 画面を再描画

        if (typeof window.saveBgmToFirebase === 'function') {
            await window.saveBgmToFirebase(db.playlists);
        }
    };

    // 🎵 3. 曲を削除する関数（大復活！）
    window.deleteTrack = async function(index) {
        const currentPlIdx = db.activePlaylistIdx !== undefined ? db.activePlaylistIdx : 0;
        const pl = db.playlists[currentPlIdx];
        if (!pl || !pl.tracks || !pl.tracks[index]) return;

        if (!confirm("この曲を削除してもよろしいですか？🐾")) return;

        pl.tracks.splice(index, 1);
        window.renderBgmTracks(); // 画面を再描画

        if (typeof window.saveBgmToFirebase === 'function') {
            await window.saveBgmToFirebase(db.playlists);
        }
    };
    // 🌟 2. トラック描画の窓口化
    window.renderBgmTracks = function() {
        const container = document.getElementById('bgm-tracks-list');
        if (!container) return;
        container.innerHTML = '';
        const pl = db.playlists[db.activePlaylistIdx];
        if (!pl || !pl.tracks || pl.tracks.length === 0) {
            container.innerHTML = `<p style="font-size:0.85rem; color:#8c827a;">曲がありません。</p>`;
            return;
        }
        pl.tracks.forEach((track, index) => {
            const row = document.createElement('div');
            row.style = "display:flex; justify-content:space-between; align-items:center; background:var(--bg-color); padding:8px 12px; border-radius:8px; border:1px solid var(--border-color); margin-bottom:4px;";
            row.innerHTML = `
                <span onclick="triggerTrackPlay('${track.id}', ${index})" style="cursor:pointer; font-weight:600; font-size:0.85rem; flex:1; color:#a26d46;">🎵 ${track.title}</span>
                <div style="display:flex; gap:4px;">
                    <button onclick="editTrackName(${index})" class="bgm-btn" style="padding:2px 6px; font-size:0.7rem;">名変</button>
                    <button onclick="deleteTrack(${index})" class="bgm-btn" style="padding:2px 6px; font-size:0.7rem; background:#dc3545;">消</button>
                </div>
            `;
            container.appendChild(row);
        });
    }

    // 🌟 3. 再生コア関数の窓口化（部屋のすれ違いを力ずくで突破！）
    window.triggerTrackPlay = function(videoId, index = 0) {
        const playerBox = document.getElementById('yt-player-box');
        if (playerBox) playerBox.style.display = 'block';

        activeTrackIdx = index;

        // window.player（グローバル）とローカルの player の両方に対応できる安全設計
        const targetPlayer = window.player || (typeof player !== 'undefined' ? player : null);

        if (targetPlayer && typeof targetPlayer.loadVideoById === 'function') {
            targetPlayer.loadVideoById(videoId);
            console.log("🎵 YouTube再生命令を強制バインドしました🐾 ID:", videoId);
        } else {
            // 万が一プレイヤーオブジェクトの紐付けがまだの場合、安全にグローバル空間に直接再生を命令するフォールバック
            console.warn("⚠️ プレイヤーを模索中... グローバルウィンドウへ再生を転送します。");
            if (window.player && typeof window.player.loadVideoById === 'function') {
                window.player.loadVideoById(videoId);
            } else {
                alert("YouTubeプレイヤーがまだ準備できていないか、読み込み中ですにゃん。もう一度クリックしてみてね！");
            }
        }
    }

    // 🌟 4. 次の曲へ遷移する関数の窓口化
    window.playNextTrack = function() {
        const pl = db.playlists[db.activePlaylistIdx];
        if (!pl || pl.tracks.length === 0) return;
        activeTrackIdx = (activeTrackIdx + 1) % pl.tracks.length;
        window.triggerTrackPlay(pl.tracks[activeTrackIdx].id, activeTrackIdx);
    }
// 🌟 5. YouTube APIから100%確実に見つけさせるためのプレイヤー生成窓口
    window.onYouTubeIframeAPIReady = function() {
        console.log("🎬 onYouTubeIframeAPIReady が正常にトリガーされました🐾");

        // もしすでにプレイヤーが存在している場合は、二重生成を防ぐ
        if (window.player) return;

        // ⚠️【安全ガード】画面内に埋め込み用の枠がない場合は、フリーズを防ぐために処理をスキップ
        if (!document.getElementById('yt-player-frame')) {
            console.log("⚠️ yt-player-frame が見つからないため、プレイヤーの生成をスキップします🐾");
            return;
        }

        window.player = new YT.Player('yt-player-frame', {
            height: '100%',
            width: '100%',
            videoId: '', // 初期状態は空
            playerVars: {
                'autoplay': 1,       // クリックで即再生できるように1
                'controls': 1,       // コントロールバーを表示
                'rel': 0,            // 関連動画は同じチャンネル内のみ
                'showinfo': 0,
                'ecver': 2
            },
            events: {
                'onReady': function(event) {
                    console.log("🚀 YouTubeプレイヤーの準備が完了しましたにゃん🐾");
                },
                'onStateChange': function(event) {
                    // 曲が最後まで再生し終わったら（ENDED = 0）、自動で次の曲へ
                    if (event.data === 0) {
                        if (typeof window.playNextTrack === 'function') {
                            window.playNextTrack();
                        }
                    }
                }
            }
        });
    };

    // ⚠️ YouTubeのAPIスクリプトのロードが先に終わってしまっていた場合のセーフティネット
    if (typeof YT !== 'undefined' && YT.Player) {
        console.log("💡 すでにYouTube APIのロードが完了しているのを発見！手動で初期化します🐾");
        window.onYouTubeIframeAPIReady();
    }
// ----------------------------- 🥸 秘密機能 (現状維持) -----------------------------
    function executeStabilizerScan() {
        const logBox = document.getElementById('stabilizer-log');
        const bar = document.getElementById('stabilizer-progress-bar');
        const wisdomBox = document.getElementById('secret-wisdom-box');
        const wisdomText = document.getElementById('secret-wisdom-text');

        wisdomBox.style.display = 'none'; logBox.innerHTML = ''; bar.style.width = '0%';

        const steps = [
            { t: 100, msg: "＞ [SYSTEM] AI Neko-Core Quantum スキャナーをロード。" },
            { t: 500, msg: "＞ [SYSTEM] ブラウザスタック領域の走査を開始..." },
            { t: 900, msg: "＞ [RUN] メモリリーク検出テスト中..." },
            { t: 1200, msg: "＞ [SCAN] scan_address 0x7FFA... [OK]" },
            { t: 1800, msg: "＞ [SCAN] scan_address 0x4D2A... [CLEAN]" },
            { t: 2400, msg: "＞ [SUCCESS] ウイルス検知プロセス完了。バグゼロ。" },
            { t: 3000, msg: "＞ [STABLE] 同期率：100% (環境安定を確認)" }
        ];

        steps.forEach(step => {
            setTimeout(() => {
                logBox.innerHTML += `<div>${step.msg}</div>`; logBox.scrollTop = logBox.scrollHeight;
                if (step.t === 3000) { bar.style.width = '100%'; displayNekoWisdom(); }
                else { bar.style.width = `${Math.round((step.t / 3000) * 100)}%`; }
            }, step.t);
        });

        function displayNekoWisdom() {
            const list = [
                "「猫が眠る日は、宇宙もまた安定している。心配せず休むにゃ。」",
                "「悩んだら暖かい場所で丸くなり、体温を上げるだけで知恵が湧くにゃ。」",
                "「ネットの海を泳ぎ疲れたら、ねこちゃんねるを見てリラックスするにゃ。」"
            ];
            wisdomText.innerText = list[Math.floor(Math.random() * list.length)];
            setTimeout(() => { wisdomBox.style.display = 'block'; }, 300);
        }
    }

    // ----------------------------- 管理者制御 (現状維持) -----------------------------
function savePostFromAdmin() {
    const id = document.getElementById('admin-post-id-input')?.value;
    const titleVal = document.getElementById('admin-title-input')?.value.trim();
    const catVal = document.getElementById('admin-category-input')?.value.trim() || '一般';
    const bodyVal = document.getElementById('admin-body-input')?.value.trim();
    const isPublic = document.getElementById('admin-public')?.checked;

    if (!titleVal || !bodyVal) return alert("タイトルと本文は入力必須です。");

    if (id) {
        const post = db.posts.find(p => p.id === parseInt(id));
        if (post) {
            post.title = titleVal; post.category = catVal; post.body = bodyVal; post.public = isPublic;
        }
    } else {
        db.posts.unshift({
            id: Date.now(),
            title: titleVal,
            category: catVal,
            body: bodyVal,
            public: isPublic,
            date: new Date().toISOString().split('T')[0]
        });
    }

    saveToLocalStorage();
    renderArticlesGrid();
    alert("保存が完了しました。メイン画面に戻ります。");
    closeModal('admin-modal');
    clearAdminForm();
}

// 管理者メニューの表示状態を更新する関数（HTML完全連動版）
function updateAdminUI() {
    // 💡 ケニアさんが統合してくれたHTMLのグループID（admin-menu-section）を直接探します
    const adminMenuSection = document.getElementById('admin-menu-section');

    if (db.isAdmin || localStorage.getItem('aiverse_admin') === 'true') {
        if (adminMenuSection) {
            // style="display:none;" を上書き解除して一発表示！
            adminMenuSection.style.display = 'block';
        }
        console.log("管理者メニュー（ADMIN枠）を有効化しました 😸");
    } else {
        if (adminMenuSection) {
            adminMenuSection.style.display = 'none';
        }
    }
}
// ページ読み込み完了時に実行して、ログイン状態を復元する
window.addEventListener('load', updateAdminUI);
  function editArticle(id) {
    const p = db.posts.find(item => item.id === id);
    if (!p) return;
    if(document.getElementById('admin-post-id-input')) document.getElementById('admin-post-id-input').value = p.id;
    if(document.getElementById('admin-title-input')) document.getElementById('admin-title-input').value = p.title;
    if(document.getElementById('admin-category-input')) document.getElementById('admin-category-input').value = p.category;
    if(document.getElementById('admin-body-input')) document.getElementById('admin-body-input').value = p.body;
    if(document.getElementById('admin-public')) document.getElementById('admin-public').checked = p.public;
    openModal('admin-modal');
}
  function deleteArticle(id) {
    if (confirm("この記事を削除しますか？")) {
        db.posts = db.posts.filter(p => p.id !== id);
        saveToLocalStorage();
        renderArticlesGrid();
    }
}
  function clearAdminForm() {
    // 存在する入力欄だけを安全に空にする
    const ids = [
        'admin-title-input',
        'admin-body-input',
        'admin-category-input',
        'memo-textarea'
    ];

    ids.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });

    const publicCheck = document.getElementById('admin-public');
    if (publicCheck) publicCheck.checked = true;

    console.log("フォームを安全にリセットしました");
}

function setNewPostMode() {
    clearAdminForm();

    const idInput = document.getElementById('admin-post-id-input');
    if (idInput) idInput.value = '';
}

document.addEventListener('click', function(e) {
    const menu = document.getElementById('gear-menu');
    const gearBtn = document.querySelector('.gear-btn');

    // 1. そもそも要素が画面にないなら何もしない（安全装置）
    if (!menu || !gearBtn) return;

    // 2. モーダルの内側をクリックした時はメニューを閉じない
    if (e.target.closest('.modal') || e.target.closest('.modal-content')) return;

    // 3. メニューが開いている時、メニューと歯車ボタン「以外」をクリックしたら閉じる
    // 「?.」を仕込むことで、絶対にstyleがnullエラーを起こさなくなります
    const isMenuVisible = menu.classList.contains('show') || menu.style?.display === 'block';

    if (isMenuVisible && !menu.contains(e.target) && !gearBtn.contains(e.target)) {
        menu.classList.remove('show');
        if (menu.style) menu.style.display = 'none'; // 👈 ここも安全に閉じる
    }
});
  // ----------------------------- 言語アシスタント -----------------------------
  function toggleLanguage() {
    alert("Alverseは標準ブラウザ翻訳に対応する「多言語アシスト仕様」で組まれています。日本語・英語以外のデバイスからも美しく表示されます。");
}

// ----------------------------- モーダル開閉 (完全版) -----------------------------
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'flex';
        // 🚀 震え対策：スクロールを封印
        document.body.classList.add('modal-open');

        if (id === 'board-modal') renderBoard();
        if (id === 'gallery-modal') renderGallery();
        if (id === 'bgm-modal') initBgmPanel();
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'none';
        // 🚀 固定を解除
        document.body.classList.remove('modal-open');
        // ✨ 拡大画像(photo-modal)を閉じたらギャラリーへ戻る
        if (id === 'photo-modal') {
            const gallery = document.getElementById('gallery-modal');
            if (gallery) {
                gallery.style.display = 'flex';
                document.body.classList.add('modal-open');
            }
        }
    }
}

// 📝 修正：IDを 'admin-modal' に合わせる
function openNewPost() {
    // HTML側の <div id="admin-modal"> を開くように指定
    openModal('admin-modal');
}
// 文字列を安全に変換する関数
function escapeHTML(str) {
    if (!str) return "";
    return str.replace(/[&<>'"]/g, m => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        "'": '&#39;',
        '"': '&quot;'
    }[m]));
}
// =========================================================
// 😸 猫の知恵袋 (Firebase RTDB 完全永続化＆管理者編集・削除版🐾)
// =========================================================

// 🌟 1. サーバー(Firebase)から知恵袋データを取得して描画する関数
async function loadBoardFromServer() {
    const container = document.getElementById('board-posts-container');
    if (!container) return;

    try {
        const url = `https://alverse-project-default-rtdb.asia-southeast1.firebasedatabase.app/alverse_pro_v3/articles.json`;
        const res = await fetch(url);
        if (!res.ok) throw new Error("データの取得に失敗しました");
        const data = await res.json();

        container.innerHTML = '';

        if (!data) {
            container.innerHTML = `<p style="text-align:center; color:#8c827a; padding: 20px;">まだ知恵がありません。最初の知恵を共有してみましょう🐾</p>`;
            return;
        }

        // オブジェクトを配列に変換
        const posts = Object.keys(data).map(key => ({ fbKey: key, ...data[key] }));
        
        // 新しい投稿が上（最新順）にくるようにソート（逆順）
        posts.reverse();

        // ⚙️ 歯車メニューの管理者セクションが表示されているかでAdmin判定
        const adminSection = document.getElementById('admin-menu-section');
        const isAdmin = (adminSection && adminSection.style.display !== 'none');

        posts.forEach((post, i) => {
            const div = document.createElement('div');
            div.className = 'bbs-item';
            div.style = "background: #fff; border: 1px solid var(--border-color); border-radius: 12px; padding: 15px; margin-bottom: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.01); position: relative;";
            
            // 管理者モードの時だけ編集・削除ボタンを右上に配置
            let adminControls = '';
            if (isAdmin) {
                adminControls = `
                    <div class="admin-bbs-controls" style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                        <button onclick="editBoardEntry('${post.fbKey}', \`${(post.title || '').replace(/`/g, '\\`封')}\`, \`${(post.body || '').replace(/`/g, '\\`封')}\`)" style="background:#f0a500; color:#fff; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; font-size:12px; margin-right:5px;">✏️</button>
                        <button onclick="deleteBoardEntry('${post.fbKey}')" style="background:#fa5252; color:#fff; border:none; padding:4px 8px; border-radius:4px; cursor:pointer; font-size:12px;">❌</button>
                    </div>
                `;
            }

            const postTitle = post.title ? `<div style="font-size: 15px; font-weight: bold; color: #5c5246; margin-bottom: 5px;">${post.title}</div>` : '';
            const dateStr = post.date ? `<span style="font-weight:normal; font-size:11px; color:#b5ab9e; margin-left:8px;">${post.date}</span>` : '';

            div.innerHTML = `
                <div style="font-weight: bold; color: #8c827a; font-size: 13px; margin-bottom: 5px;">
                    No.${posts.length - i} 名無しにゃんこ ${dateStr}
                </div>
                ${postTitle}
                <div style="font-size: 14px; color: #6c6256; white-space: pre-wrap; line-height: 1.5;">${post.body || ''}</div>
                ${adminControls}
            `;
            container.appendChild(div);
        });

    } catch (e) {
        console.error("掲示板の読み込みエラー:", e);
        container.innerHTML = `<p style="text-align:center; color:#fa5252; padding:20px;">知恵袋の同期に失敗しました😿</p>`;
    }
}

// 🌟 2. 新しく知恵を共有（FirebaseへPOST保存）
async function submitBoardPost() {
    const titleInput = document.getElementById('board-title-input');
    const bodyInput = document.getElementById('board-body-input');

    if (!bodyInput || !bodyInput.value.trim()) {
        alert("本文を入力してくださいにゃ🐾");
        return;
    }

    const title = titleInput ? titleInput.value.trim() : '';
    const body = bodyInput.value.trim();

    const now = new Date();
    const dateString = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')} ${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}`;

    const newPost = {
        title: title,
        body: body,
        date: dateString,
        timestamp: Date.now()
    };

    try {
        const url = `https://alverse-project-default-rtdb.asia-southeast1.firebasedatabase.app/alverse_pro_v3/articles.json`;
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(newPost)
        });

        if (!res.ok) throw new Error("投稿の送信に失敗しました");

        if (titleInput) titleInput.value = '';
        bodyInput.value = '';

        // 送信直後に再読み込みして描画
        await loadBoardFromServer();
        console.log("🚀 Firebaseに新しい知恵を永続化しました🐾");

    } catch (e) {
        console.error("投稿エラー:", e);
        alert("投稿に失敗しました😿");
    }
}

// 🌟 3. 管理者機能：投稿の削除（元の関数をそのまま拡張🐾）
async function deleteBoardEntry(fbKey) {
    if (!confirm("この知恵を消去してもよろしいですか？🐾")) return;

    try {
        const deleteUrl = `https://alverse-project-default-rtdb.asia-southeast1.firebasedatabase.app/alverse_pro_v3/articles/${fbKey}.json`;
        const res = await fetch(deleteUrl, { method: 'DELETE' });
        if (!res.ok) throw new Error("削除リクエストに失敗しました");

        alert("消去完了しました 🐱");
        await loadBoardFromServer(); // 画面を更新
    } catch (e) {
        console.error("削除エラー:", e);
        alert("消去に失敗しました😿：" + e.message);
    }
}

// 🌟 4. 管理者機能：投稿の編集
async function editBoardEntry(fbKey, currentTitle, currentBody) {
    const newTitle = prompt("【管理者編集】タイトルを変更しますか？", currentTitle);
    if (newTitle === null) return;

    const newBody = prompt("【管理者編集】本文を変更しますか？", currentBody);
    if (newBody === null) return;

    try {
        const url = `https://alverse-project-default-rtdb.asia-southeast1.firebasedatabase.app/alverse_pro_v3/articles/${fbKey}.json`;
        const res = await fetch(url, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                title: newTitle.trim(),
                body: newBody.trim()
            })
        });

        if (!res.ok) throw new Error("編集リクエストに失敗しました");

        alert("知恵の編集が完了しました 🐱");
        await loadBoardFromServer();
    } catch (e) {
        console.error("編集エラー:", e);
        alert("編集に失敗しました😿：" + e.message);
    }
}

// 🌟 5. ページ読み込み時に自動でデータを同期
document.addEventListener('DOMContentLoaded', () => {
    loadBoardFromServer();
});
// =========================================================
// 💾 バックアップ ＆ 🌐 サーバー共有システム
// =========================================================

async function exportData(type = 'posts') {

    try {

        const currentData =
            (typeof db !== 'undefined' && db)
                ? db
                : {};

        let exportObj = {};
        let filename = "";

        const dateStr =
            new Date()
            .toISOString()
            .replace(/[:.]/g, '-');

        // -------------------------------------------------
        // 📦 データ振り分け
        // -------------------------------------------------

        switch (type) {

            case 'posts':

                exportObj = {
                    posts: currentData.posts || [],
                    exportedAt: new Date().toISOString(),
                    version: "AIverse-Backup-v2"
                };

                filename =
                    `aiverse_articles_${dateStr}.json`;

                break;

            case 'config':

                for (const key in currentData) {

                    if (
                        currentData.hasOwnProperty(key) &&
                        key !== 'posts'
                    ) {
                        exportObj[key] = currentData[key];
                    }
                }

                exportObj.exportedAt =
                    new Date().toISOString();

                exportObj.version =
                    "AIverse-Config-v2";

                filename =
                    `aiverse_config_${dateStr}.json`;

                break;

            default:

                throw new Error("未知のエクスポート形式");
        }

        // -------------------------------------------------
        // 💾 JSON生成
        // -------------------------------------------------

        const json =
            JSON.stringify(exportObj, null, 2);

        const blob =
            new Blob(
                [json],
                { type: 'application/json' }
            );

        const url =
            URL.createObjectURL(blob);

        const a =
            document.createElement('a');

        a.href = url;
        a.download = filename;

        document.body.appendChild(a);

        a.click();

        document.body.removeChild(a);

        URL.revokeObjectURL(url);

        // -------------------------------------------------
        // 🌐 サーバー同期
        // -------------------------------------------------

        if (typeof syncWithServer === 'function') {

            const syncType =
                (type === 'posts')
                    ? 'articles'
                    : 'config';

            await syncWithServer(
                syncType,
                exportObj
            );

            showToast(
                '✅ サーバー同期完了'
            );
        } else {

            console.warn(
                'syncWithServer が存在しません'
            );
        }

    } catch (e) {

        console.error(
            'Export Error:',
            e
        );

        alert(
            '保存失敗: ' + e.message
        );
    }
}

// =========================================================
// ⚙️ 歯車ドロップダウン制御（インラインStyle強制上書き版🐾）
// =========================================================

// 1. 純粋な開閉処理（HTMLの onclick から呼ばれる）
window.toggleGearMenu = function(e) {
    console.log("🚀 toggleGearMenu が発動しました！");

    if (e && typeof e.stopPropagation === 'function') {
        e.stopPropagation();
    } else if (window.event) {
        window.event.cancelBubble = true;
    }

    const menu = document.getElementById('gear-menu') || document.querySelector('.settings-dropdown');
    if (!menu) return;

    // 現在の style.display または クラス名 から開閉状態を判定
    const isHidden = menu.style.display === 'none' || 
                     (!menu.classList.contains('show') && !menu.classList.contains('open') && menu.style.display !== 'block');

    if (isHidden) {
        // 🔼 開く：クラスを付与し、インラインstyleを block に強制書き換え
        menu.classList.add('show', 'open');
        menu.style.display = 'block';
        console.log("🔼 メニューを【表示（block）】にしました");
    } else {
        // 🔽 閉じ：クラスを削除し、インラインstyleを none に強制書き換え
        menu.classList.remove('show', 'open');
        menu.style.display = 'none';
        console.log("🔽 メニューを【非表示（none）】にしました");
    }
};

window.toggleAdminMenu = window.toggleGearMenu;

// 2. 外側クリックで閉じる処理
document.addEventListener('click', function(e) {
    const menu = document.getElementById('gear-menu') || document.querySelector('.settings-dropdown');
    if (!menu) return;

    // メニューが開いているか？（display が block かどうかで判定）
    if (menu.style.display !== 'block') return;

    if (menu.contains(e.target)) return;
    if (e.target.closest('.gear-btn') || e.target.closest('.fa-cog') || e.target.closest('#admin-gear-btn')) return;
    if (e.target.closest('.modal') || e.target.closest('.modal-content')) return;

    // 完全な外側なら display を none にして完全に閉じる
    menu.classList.remove('show', 'open');
    menu.style.display = 'none';
    console.log("🌌 外側クリックでメニューを閉じました🐾");
});
// ---------------------------------------------------------
// 🌌 モーダル制御
// ---------------------------------------------------------

function openModal(id) {

    const modal =
        document.getElementById(id);

    if (!modal) {
        console.warn(
            'Modal not found:',
            id
        );
        return;
    }

    modal.style.display = 'flex';

    document.documentElement
        .classList.add('modal-open');

    document.body
        .classList.add('modal-open');
}

function closeModal(id) {

    const modal =
        document.getElementById(id);

    if (!modal) return;

    modal.style.display = 'none';

    document.documentElement
        .classList.remove('modal-open');

    document.body
        .classList.remove('modal-open');
}

// =========================================================
// 🔐 管理者モード
// =========================================================

function toggleSecretMode() {

     const pass =
         prompt(
             "管理者パスワードを入力してください🐾"
         );

     if (pass === null) {
         return;
     }

     // -------------------------------------------------
     // 🔑 認証
     // -------------------------------------------------

     if (pass === "nekosuke101") {

         if (typeof db !== 'undefined') {

             db.isAdmin = true;

             // 永続化
             localStorage.setItem(
                 'aiverse_admin',
                 'true'
             );
         }

         injectAdminMenu();

         showToast(
             "🐱 管理者モード ON"
         );

     } else {

         showToast(
             "❌ パスワードが違います"
         );
     }
}

// =========================================================
// 🚪 ログアウト（データベース内フラグ完全初期化版）
// =========================================================
function logoutAdmin() {
     const ok = confirm("管理者モードを終了しますか？🐾");
     if (!ok) return;

     // 1. JavaScriptメモリ上のフラグを落とす
     if (typeof db !== 'undefined') {
         db.isAdmin = false;
     }

     // 2. 【本丸】localStorage内の巨大データ（alverse_database_engine_v3）を安全に書き換える
     try {
         const dbKey = 'alverse_database_engine_v3';
         const rawData = localStorage.getItem(dbKey);

         if (rawData) {
             // データを一度パースしてオブジェクトに戻す
             const dataObj = JSON.parse(rawData);

             // オブジェクトの中の管理者フラグを「確実にfalse」にする
             dataObj.isAdmin = false;

             // 綺麗にしたデータをもう一度文字列に戻してローカルストレージに上書き保存
             localStorage.setItem(dbKey, JSON.stringify(dataObj));
             console.log("✅ ローカルストレージ内のADMIN権限を完全に消去しました");
         }

         // 以前の古いフラグも念のため綺麗に掃除
         localStorage.removeItem('aiverse_admin');

     } catch (e) {
         console.warn("localStorageの書き換えでブロックが発生しましたが、処理を続行します:", e);
     }

     // 3. 画面のDOM書き換え（showToast）を避けて、安全にポップアップ通知
     alert("🚪 ログアウトしました");

     // 4. まっさらな状態でトップページへ強制遷移（UIの完全初期化）
     window.location.replace(window.location.href);
}
// =========================================================
// 🍞 トースト通知
// =========================================================

function showToast(message = "") {

    let toast =
        document.getElementById(
            'aiverse-toast'
        );

    if (!toast) {

        toast =
            document.createElement('div');

        toast.id =
            'aiverse-toast';

        toast.style.cssText = `
            position:fixed;
            bottom:20px;
            left:50%;
            transform:translateX(-50%);
            background:#111;
            color:white;
            padding:12px 18px;
            border-radius:12px;
            z-index:999999;
            font-size:0.9rem;
            box-shadow:0 8px 24px rgba(0,0,0,0.35);
            opacity:0;
            transition:0.25s;
            pointer-events:none;
        `;

        document.body.appendChild(toast);
    }

    toast.textContent = message;

    toast.style.opacity = '1';

    clearTimeout(
        window.__toastTimer
    );

    window.__toastTimer =
        setTimeout(() => {

            toast.style.opacity = '0';

        }, 2200);
}

// =========================================================
// 🌐 記事データ読み込み基盤
// =========================================================
async function loadAllArticles() {
    console.log("loadAllArticles OK");
}
// =========================================================
// 🚀 自動復元（整理版）
// =========================================================

window.addEventListener('load', () => {

    // 記事読み込み
    if (
        typeof db !== 'undefined' &&
        typeof loadAllArticles === 'function'
    ) {
        loadAllArticles();
    }

    // 管理者ログイン状態の復元
    const isAdminSaved =
        localStorage.getItem('aiverse_admin') === 'true';

    if (isAdminSaved && typeof db !== 'undefined') {

        db.isAdmin = true;

        // DOM描画後に管理者メニュー注入
        setTimeout(() => {

            if (typeof injectAdminMenu === 'function') {
                injectAdminMenu();
            }

        }, 100);
    }
});
// --- ここから追加：カテゴリーマスターリスト ---
const AIVERSE_CATEGORIES = [
    { id: "news", label: "📢 ニュース" },
    { id: "ent", label: "🎭 エンタメ" },
    { id: "game", label: "🎮 ゲーム" },
    { id: "anime", label: "🎨 アニメ・漫画" },
    { id: "movie", label: "🎬 映画・ドラマ" },
    { id: "music", label: "🎵 音楽" },
    { id: "science", label: "🧪 科学" },
    { id: "space", label: "🚀 宇宙" },
    { id: "tech", label: "💻 テクノロジー" },
    { id: "ai", label: "🤖 AI" },
    { id: "math", label: "📐 数学" },
    { id: "phi", label: "📜 哲学・思想" },
    { id: "hist", label: "🏛️ 歴史" },
    { id: "politics", label: "⚖️ 政治・社会" },
    { id: "biz", label: "📈 経済・ビジネス" },
    { id: "health", label: "🏥 医療・健康" },
    { id: "psych", label: "🧠 心理学" },
    { id: "edu", label: "📖 教育・学習" },
    { id: "mystery", label: "🔍 ミステリー・オカルト" },
    { id: "urban", label: "⛩️ 都市伝説" },
    { id: "uma", label: "👾 未確認生物" },
    { id: "nature", label: "🌿 自然・生物" },
    { id: "food", label: "🍳 食べ物・料理" },
    { id: "life", label: "🏠 生活・暮らし" },
    { id: "culture", label: "✈️ 旅行・文化" },
    { id: "sports", label: "⚽ スポーツ" },
    { id: "internet", label: "🌐 インターネット" },
    { id: "youtube", label: "🎥 YouTube・配信" },
    { id: "trivia", label: "💡 雑学・トリビア" },
    { id: "column", label: "📝 コラム" },
    { id: "special", label: "✨ 特集" }
];

function initCategorySelect() {
    const select = document.getElementById('admin-category-input');
    if (select) {
        select.innerHTML = AIVERSE_CATEGORIES.map(cat =>
            `<option value="${cat.id}">${cat.label}</option>`
        ).join('');
    }
}

// =========================================================
// 💾 自動保存 ＆ プレビュー連動システム（セキュリティエラー完全回避版）
// =========================================================
function setupDraftSystem() {
      const titleInp = document.getElementById('admin-title-input');
      const bodyInp = document.getElementById('admin-body-input');
      const categoryInp = document.getElementById('admin-category-input');

      // 安全弁：どれか1つでも要素がなければ処理を行わない
      if (!titleInp || !bodyInp || !categoryInp) return;

      // 入力・変更があったら実行
      const handleInput = () => {
          updateAdminPreview(); // プレビュー更新

          // ✨【安全装置】保存時にlocalStorageがブロックされてもクラッシュさせない
          try {
              if (typeof saveDraft === 'function') {
                  saveDraft(); // 下書き保存
              }
          } catch (e) {
              console.warn("localStorageへの書き込みがブロックされました(下書きは保存されません):", e);
          }
      };

      titleInp.addEventListener('input', handleInput);
      bodyInp.addEventListener('input', handleInput);
      categoryInp.addEventListener('change', handleInput);

      // ✨【ここを完全防御】ページ読み込み時のフライングエラーを100%遮断する
      try {
          if (typeof loadDraft === 'function') {
              loadDraft(); // 下書き復元
          }
      } catch (e) {
          console.warn("localStorageからの初期読み込みがブロックされました:", e);
      }
}
// =========================================================
// 👁️ プレビュー描画（安全ガード追加版）
// =========================================================
function updateAdminPreview() {
    const titleInp = document.getElementById('admin-title-input');
    const bodyInp = document.getElementById('admin-body-input');
    const categoryInp = document.getElementById('admin-category-input');
    const previewArea = document.getElementById('admin-preview-area');

    // 💡 安全弁：フォームやプレビューエリアが画面に存在しない（一般ユーザー環境）なら即終了
    if (!titleInp || !bodyInp || !categoryInp || !previewArea) return;

    const title = titleInp.value || "タイトル未入力";
    const body = bodyInp.value || "本文を執筆中...";
    const categoryId = categoryInp.value;

    // ✨ 画像URLを取得。空ならデフォルト（宇宙の画像）を表示
    const imageInp = document.getElementById('admin-image-input');
    const customImg = imageInp ? imageInp.value : "";
    const imageUrl = customImg || "https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=300&auto=format&fit=crop";

    // カテゴリーマスターからラベルを取得
    const categoryObj = AIVERSE_CATEGORIES.find(c => c.id === categoryId) || { label: "📄 その他" };
    const categoryLabel = categoryObj.label;

    previewArea.innerHTML = `
        <div class="card" style="width: 300px; margin: 0; pointer-events: none; opacity: 0.9;">
            <div class="card-image" style="background-image: url('${imageUrl}'); background-size: cover; background-position: center; height: 150px; background-color: #333;"></div>
            <div class="card-content">
                <div class="card-category" style="color: #ff9800;">${categoryLabel}</div>
                <h3 class="card-title" style="margin: 5px 0; color: #fff; font-size: 1.1rem;">${escapeHTML(title)}</h3>
                <p class="card-description" style="font-size: 0.8rem; color: #ccc;">${escapeHTML(body).substring(0, 40)}...</p>
                <div class="card-footer" style="margin-top: 10px; font-size: 0.7rem; color: #888;">
                    <span>LIVE PREVIEW</span> • <span>${new Date().toLocaleDateString()}</span>
                </div>
            </div>
        </div>
    `;
}
// 💾 下書き保存ロジック
function saveDraft() {
    const draft = {
        title: document.getElementById('admin-title-input').value,
        body: document.getElementById('admin-body-input').value,
        category: document.getElementById('admin-category-input').value,
        image: document.getElementById('admin-image-input').value, // ✨ 画像URLも保存
        updated: new Date().getTime()
    };
    localStorage.setItem('aiverse_post_draft', JSON.stringify(draft));
}
// 📥 下書き復元ロジック（セキュリティエラー完全回避版）
function loadDraft() {
    try {
        // 🌟 この try の中で localStorage を触ることで、アクセス拒否されてもクラッシュしなくなります
        const saved = localStorage.getItem('aiverse_post_draft');
        if (!saved) return;

        const data = JSON.parse(saved);
        // 2時間以内の下書きなら復元
        if (new Date().getTime() - data.updated < 7200000) {
            document.getElementById('admin-title-input').value = data.title;
            document.getElementById('admin-body-input').value = data.body;
            document.getElementById('admin-category-input').value = data.category;
            document.getElementById('admin-image-input').value = data.image || ""; // ✨ 画像URLを復元
            updateAdminPreview(); // 復元した内容でプレビューを表示
        }
    } catch (e) {
        // ブラウザにブロックされた場合は、静かに警告を出すだけで処理をスルー
        console.warn("localStorageからの下書き復元がブロックされました（処理は安全に続行されます）:", e);
    }
}
// ---------------------------------------------------------
// 🚀 起動処理 (window.onload)
// 📌 頭とお尻を完全に一本化して、ここで一気にシステムを起動させます！
// ---------------------------------------------------------
window.onload = () => {
     // 1. 基本システムの初期化
     initCategorySelect();
     renderArticlesGrid();

     // 2. モーダル背景クリックの設定
　　const allModalIds = ['detail-modal', 'photo-modal', 'board-modal', 'gallery-modal', 'bgm-modal', 'memo-modal'];
    allModalIds.forEach(id => {
         const modal = document.getElementById(id);
         if (modal) {
             modal.addEventListener('click', (event) => {
                 if (event.target === modal) closeModal(id);
             });
         }
     });

　 // 3. 震え対策CSSの適用
     const fixStyle = document.createElement('style');
     fixStyle.textContent = `
         html.modal-open, body.modal-open {
             overflow: hidden !important;
             height: 100vh !important;
             width: 100vw !important;
             position: fixed !important;
             top: 0; left: 0;
         }
         #photo-modal img {
             max-width: 65vw !important;
             max-height: 65vh !important;
             pointer-events: none;
         }
     `;
     document.head.appendChild(fixStyle);

     // 4. 全システムの同時起動（世界共有設定）
     updateAdminUI();
     loadBoardFromServer(); // 🐾 猫の知恵袋を共有
     loadServerGallery();   // 🖼️ フォトギャラリーを共有
     loadBgmFromServer();    // 🎸 管理者設定のBGMを共有
     setupDraftSystem();    // 📝 執筆支援
}; // 📌 ここで window.onload が完璧に美しく閉じます！
// =========================================================
// 🔥 ここから先は隔離された「Firebase v10」の最強モジュール世界！
// =========================================================
</script>

<script type="module">
     // 1. Firebaseの読み込み（最上部に set も完全配備！）
     import { initializeApp, getApp, getApps } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
     import { getDatabase, ref, push, onChildAdded, serverTimestamp, get, set } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js";

     // 2. Firebaseの設定
     const firebaseConfig = {
         apiKey: "AIzaSyDbw7xkeplmYAE80JcrTIf1qkRpZsDTwXM",
         authDomain: "alverse-project.firebaseapp.com",
         databaseURL: "https://alverse-project-default-rtdb.firebaseio.com",
         projectId: "alverse-project",
         storageBucket: "alverse-project.firebasestorage.app",
         messagingSenderId: "870564638397",
         appId: "1:870564638397:web:d372f90b2b150e095791d4"
     };

     // 3. 重複起動防止を施した初期化
     const app = !getApps().length ? initializeApp(firebaseConfig) : getApp();
     const dbInstance = getDatabase(app);
     const dbRef = ref(dbInstance, "alverse_pro_v3/posts");

     // 🚀 本物のFirebase宇宙へ、データを絶対セーブする窓口
     window.saveBgmToFirebase = async function(playlistsData) {
         try {
             if (!dbInstance) {
                 console.error("Firebaseのデータベースインスタンスがありません🐾");
                 return;
             }
             // 配列の空要素（null）を綺麗に掃除してから保存する安全弁付き
             const cleanData = Array.isArray(playlistsData) ? playlistsData.filter(item => item !== null) : playlistsData;

             // 🔥 正しいインスタンスとパスで一撃上書き！
             await set(ref(dbInstance, "alverse_pro_v3/playlist"), cleanData);
             console.log("🔥 本物のFirebase(v10)へのデータ保存に完全成功しました！🐾");
         } catch (e) {
             console.error("Firebaseへのセーブ中にエラーが発生しましたにゃ:", e);
         }
     };

     // ☁️ サーバーからデータを引っ張ってくる窓口
     window.loadBgmFromServer = async () => {
         try {
             const snapshot = await get(ref(dbInstance, "alverse_pro_v3/playlist"));
             if (snapshot.exists()) {
                 const data = snapshot.val();

                 if (Array.isArray(data)) {
                     db.playlists = data.filter(item => item !== null);
                 } else if (typeof data === 'object') {
                     db.playlists = Object.values(data);
                 } else {
                     db.playlists = [];
                 }

                 initBgmPanel();
                 console.log("☁️ FirebaseからBGMデータを完全同期しました🐾");
                 return;
             }
         } catch (e) {
             console.error("Firebase BGM読み込みエラー:", e);
         }

         if (!db.playlists || db.playlists.length === 0) {
             db.playlists = [{ name: "マイリスト", tracks: [] }];
             initBgmPanel();
         }
     };

     // 4. 送信関数：ねこの知恵袋（BBS）
     window.submitBoardPost = () => {
         const titleInput = document.getElementById('board-title-input');
         const bodyInput = document.getElementById('board-body-input');

         if (!bodyInput) return;

         const title = titleInput.value.trim() || "無題の知恵";
         const body = bodyInput.value.trim();

         if (body === "") {
             alert("本文を入力してくださいにゃ！");
             return;
         }

         push(dbRef, {
             title: title,
             text: body,
             user: "Kenya",
             timestamp: serverTimestamp()
         }).then(() => {
             if (titleInput) titleInput.value = "";
             bodyInput.value = "";
             console.log("知恵を共有しました！");
         }).catch((error) => {
             console.error("送信エラー:", error);
             alert("送信に失敗しました。通信状況を確認してくださいにゃ。");
         });
     };

     // 5. リアルタイム受信：モーダル内のコンテナに流し込む
     onChildAdded(dbRef, (data) => {
         const post = data.val();
         const container = document.getElementById('board-posts-container');

         if (container) {
             const article = document.createElement('article');
             article.className = 'board-post';
             const safeTitle = (post.title || "無題").replace(/</g, "&lt;").replace(/>/g, "&gt;");
             const safeText = (post.text || "").replace(/</g, "&lt;").replace(/>/g, "&gt;");
             const date = post.timestamp ? new Date(post.timestamp).toLocaleString('ja-JP') : "今さっき";

             article.innerHTML = `
                 <div class="board-header" style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #eee; padding-bottom:5px;">
                     <span class="board-title" style="font-weight:bold; color:#3e2723;">📌 ${safeTitle}</span>
                     <span style="font-size:0.7rem; color:#aaa;">${date}</span>
                 </div>
                 <div class="board-body" style="padding: 12px 0; color: #444; line-height:1.6; white-space: pre-wrap;">${safeText}</div>
             `;

             container.prepend(article);
         }
     });
</script>
</body>
</html>

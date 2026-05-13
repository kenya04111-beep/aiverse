<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>DOG CORE - 軽量トグル版</title>
    <style>
        /* ベースレイアウト（背景色はAIverseに合わせて調整してください） */
        body { 
            display: flex; justify-content: center; align-items: center; 
            height: 100vh; background: #1a1a1a; margin: 0; 
        }

        /* --- DOG CORE 基盤設定 --- */
        .dog-svg {
            width: 150px; height: 150px;
            cursor: pointer;
            fill: none;
            stroke-width: 8;
            stroke-linecap: round;
            stroke-linejoin: round;
            /* 初期状態（青） */
            stroke: #00e6ff; 
            /* 色の変化を滑らかにする */
            transition: stroke 0.3s ease; 
        }

        /* --- アクティブ状態（オレンジ） --- */
        .dog-svg.is-happy {
            stroke: #ff9900;
        }

        /* --- パーツごとの微調整 --- */
        .fill-white { 
            fill: #ffffff; 
            stroke: none; 
        }

        /* --- 口の切り替えエフェクト --- */
        .dog-mouth { 
            stroke-width: 4.5; 
            opacity: 1; 
            transition: opacity 0.2s ease; 
        }
        .dog-mouth-happy { 
            stroke-width: 7; 
            opacity: 0; /* 初期は非表示 */
            transition: opacity 0.2s ease; 
        }

        /* オレンジ状態の時に口の表示を入れ替える */
        .dog-svg.is-happy .dog-mouth { opacity: 0; }
        .dog-svg.is-happy .dog-mouth-happy { opacity: 1; }

    </style>
</head>
<body>

    <svg id="dog-model" class="dog-svg" viewBox="0 0 128 128" onclick="toggleDog()">
        <path d="M40 30 L88 30 L118 60 L100 60 L108 95 L64 115 L20 95 L28 60 L10 60 Z" />
        <line x1="40" y1="30" x2="28" y2="60" />
        <line x1="88" y1="30" x2="100" y2="60" />
        
        <circle cx="48" cy="72" r="10.5" stroke-width="6.5" />
        <circle cx="80" cy="72" r="10.5" stroke-width="6.5" />
        <circle class="fill-white" cx="48" cy="72" r="3.5" />
        <circle class="fill-white" cx="80" cy="72" r="3.5" />
        
        <path class="fill-white" d="M60 82 L68 82 L64 90 Z" />
        
        <path class="dog-mouth" d="M64 90 L64 98 Q64 104 55 104 M64 90 Q64 104 73 104" />
        <path class="dog-mouth-happy" d="M45 95 Q64 110 83 95" />
        
        <line x1="28" y1="90" x2="43" y2="90" stroke-width="6" />
        <line x1="31" y1="98" x2="41" y2="98" stroke-width="6" />
        <line x1="100" y1="90" x2="85" y2="90" stroke-width="6" />
        <line x1="97" y1="98" x2="87" y2="98" stroke-width="6" />
    </svg>

    <script>
        // クリックされるたびに 'is-happy' クラスを付け外しするシンプルな処理
        function toggleDog() {
            document.getElementById('dog-model').classList.toggle('is-happy');
        }
    </script>

</body>
</html>

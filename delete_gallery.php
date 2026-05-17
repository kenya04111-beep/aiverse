<?php
// エラー出力を有効にしておく（ログ確認用）
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

// 応答用データの初期化
$response = ['success' => false, 'message' => '不明なエラー'];

try {
    // 🚀 JSから送られてきた JSON データを取得
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);

    // 送られてきたデータを解析
    $index = isset($input['index']) ? (int)$input['index'] : null;
    $filename = isset($input['filename']) ? $input['filename'] : (isset($input['file']) ? $input['file'] : '');

    // ギャラリーの管理データ（JSON）を読み込み
    $jsonFile = 'gallery.json';
    if (!file_exists($jsonFile)) {
        throw new Exception('gallery.json が見つかりません。');
    }

    $galleryData = json_decode(file_get_contents($jsonFile), true);
    if (!is_array($galleryData)) {
        $galleryData = [];
    }

    // 💡 削除対象の特定ロジック（インデックスまたはファイル名で検索）
    $targetIndex = null;
    
    if ($index !== null && isset($galleryData[$index])) {
        $targetIndex = $index;
    } else if (!empty($filename)) {
        // インデックスが合わない場合はファイル名で検索
        foreach ($galleryData as $i => $item) {
            if (isset($item['src']) && basename($item['src']) === basename($filename)) {
                $targetIndex = $i;
                break;
            }
        }
    }

    if ($targetIndex === null) {
        throw new Exception('削除対象の画像データが gallery.json 内に見つかりません。');
    }

    // 実際の画像ファイルパスを取得（例: uploads/xxxxx.jpg）
    $fileSrc = $galleryData[$targetIndex]['src'];
    
    // 🛑 サーバー上の実際のファイルを削除
    if (!empty($fileSrc) && file_exists($fileSrc)) {
        if (!unlink($fileSrc)) {
            throw new Exception('サーバー上の実ファイル（' . $fileSrc . '）の削除に失敗しました。権限を確認してください。');
        }
    }

    // 📋 配列からデータを削除して詰める
    array_splice($galleryData, $targetIndex, 1);

    // gallery.json に保存
    if (file_put_contents($jsonFile, json_encode($galleryData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) === false) {
        throw new Exception('gallery.json の更新に失敗しました。');
    }

    // すべて成功
    $response['success'] = true;
    $response['message'] = '削除に成功しました🐾';

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

// 最後に必ずJSONとして返却
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;

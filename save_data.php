<?php
header('Content-Type: application/json');

// 送られてきたJSONデータを取得
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || !isset($data['type'])) {
    echo json_encode(['success' => false, 'message' => 'データが不正です']);
    exit;
}

// 保存先のファイル名を決定（gallery.json, board.json, articles.json など）
$filename = 'data/' . $data['type'] . '.json';

// 既存のデータを読み込む
$currentData = [];
if (file_exists($filename)) {
    $currentData = json_decode(file_get_contents($filename), true);
}

// 新しいデータを先頭に追加
array_unshift($currentData, $data['content']);

// ファイルに保存
if (file_put_contents($filename, json_encode($currentData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => '書き込み失敗']);
}

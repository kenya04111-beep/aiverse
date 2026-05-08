<?php
header('Content-Type: application/json');

// 1. データの受け取り
$data = json_decode(file_get_contents('php://input'), true);
$filename = isset($data['filename']) ? trim(urldecode($data['filename'])) : '';
$index = isset($data['index']) ? (int)$data['index'] : -1;

if (!$filename) {
    echo json_encode(['success' => false, 'message' => 'ファイル名が空です']);
    exit;
}

$upload_dir = "/var/www/html/uploads/";
$json_file = "/var/www/html/gallery.json";
$filepath = $upload_dir . $filename;

$success = false;

// 2. 実体ファイルの削除
if (file_exists($filepath)) {
    unlink($filepath);
    $success = true; // ファイルが消せたら（または既になくても）一旦成功とみなす
}

// 3. gallery.json (名簿) からも削除
if (file_exists($json_file)) {
    $json_data = json_decode(file_get_contents($json_file), true);
    if (is_array($json_data)) {
        // 名前が一致する要素を削除（インデックス指定だとズレる可能性があるので名前で探す）
        $new_json_data = array_values(array_filter($json_data, function($item) use ($filename) {
            return basename($item['src']) !== $filename;
        }));
        
        // JSONファイルを更新
        file_put_contents($json_file, json_encode($new_json_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $success = true;
    }
}

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => '削除対象が見つかりませんでした']);
}

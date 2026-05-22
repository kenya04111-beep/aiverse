<?php
header('Content-Type: application/json');
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || !isset($data['type'])) {
    echo json_encode(['success' => false, 'message' => 'データが不正です']);
    exit;
}

// dataフォルダを指定する
$filename = 'data/' . $data['type'] . '.json';
$currentData = file_exists($filename) ? json_decode(file_get_contents($filename), true) : [];

// アクションによる分岐
if ($data['action'] === 'edit_bbs') {
    foreach ($currentData as &$item) {
        if ($item['id'] == $data['id']) {
            $item['title'] = $data['title'];
            $item['body'] = $data['body'];
        }
    }
} elseif ($data['action'] === 'delete') {
    $currentData = array_values(array_filter($currentData, fn($item) => $item['id'] != $data['id']));
} else {
    // 新規投稿
    array_unshift($currentData, $data['content']);
}

if (file_put_contents($filename, json_encode($currentData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => '書き込み失敗']);
}

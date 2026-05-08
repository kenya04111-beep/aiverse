<?php
$type = $_GET['type'] ?? '';

// typeに応じて読み書きするファイルを切り替える
if ($type === 'bbs') {
    $file = 'bbs.json';
} elseif ($type === 'bgm') {
    $file = 'bgm.json';
} else {
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    if ($input) {
        file_put_contents($file, $input);
        echo json_encode(['status' => 'success']);
    }
} else {
    header('Content-Type: application/json');
    if (file_exists($file)) {
        echo file_get_contents($file);
    } else {
        echo "[]";
    }
}
?>

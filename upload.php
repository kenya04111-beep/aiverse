<?php
$jsonFile = 'gallery.json';
$uploadDir = 'uploads/';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $fileName = time() . '_' . basename($_FILES['image']['name']);
    $targetFilePath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
        $newUrl = '/uploads/' . $fileName;

        // gallery.jsonを更新する
        $currentData = json_decode(file_get_contents($jsonFile), true) ?: [];
        array_unshift($currentData, ['id' => time(), 'src' => $newUrl]);
        file_put_contents($jsonFile, json_encode($currentData));

        echo json_encode(['url' => $newUrl]);
    }
}
?>

<?php

// 設定読み込み
require_once __DIR__ . '/config.php';

// データクラス読み込み
require_once __DIR__ . '/data.php';

// インスタンス
$data = new PrecureMusicData();

// POST値取得
$class = $_POST['class'];
$condt = $_POST['condt'];
$argmt = $_POST['argmt'];

if ($class === 'song') {
    // 歌曲
    $list = $data->getSongSearchList($condt, $argmt);
} elseif ($class === 'bgm') {
    // 劇伴
    $list = $data->getBGMSearchList($condt, $argmt);
} else {
    http_response_code(500);
    exit();
}
       
// リスト出力
header('Content-Type: application/json');
echo json_encode($list);
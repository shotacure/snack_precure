<?php

// 設定読み込み
require_once __DIR__ . '/config.php';

// データクラス読み込み
require_once __DIR__ . '/data.php';

// アクセス権限チェック
// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

if (!in_array($_SESSION['screen_name'], AUTHORIZED_USER)) {
    http_response_code(401);
    exit();
}

// 楽曲履歴
$data = new PrecureMusicData();
$data->setPlayHistory($_SESSION['dj']['current']['name'], $_SESSION['music']);

// 更新フラグ
$_SESSION['updated'] = '1';

// データ出力
header('Content-Type: application/json');
echo json_encode($_SESSION);

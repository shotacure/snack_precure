<?php

// 設定読み込み
require_once __DIR__ . '/config.php';

// アクセス権限チェック
// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

if (!in_array($_SESSION['screen_name'], AUTHORIZED_USER)) {
    http_response_code(401);
    exit();
}

// データクラス読み込み
require_once __DIR__ . '/data.php';

// セッション初期化
$_SESSION['music']['data']['class'] = '';
$_SESSION['music']['data']['id'] = '';
$_SESSION['music']['data']['series'] = '';
$_SESSION['music']['series'] = '';
$_SESSION['music']['disc'] = '';
$_SESSION['music']['title'] = '';
$_SESSION['music']['mno'] = '';
$_SESSION['music']['artist'] = '';
$_SESSION['updated'] = '1';

// データ出力
header('Content-Type: application/json');
echo json_encode($_SESSION);

<?php

// 設定読み込み
require_once __DIR__ . '/config.php';

// データクラス読み込み
require_once __DIR__ . '/data.php';

// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

// セッションを空にする
$_SESSION['music'] = null;

// データ出力
header('Content-Type: application/json');
echo json_encode($_SESSION['music']);

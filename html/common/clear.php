<?php

// 設定読み込み
require_once __DIR__ . '/config.php';

// データクラス読み込み
require_once __DIR__ . '/data.php';

// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

// セッション初期化
$_SESSION['music']['series'] = '';
$_SESSION['music']['disc'] = '';
$_SESSION['music']['title'] = '';
$_SESSION['music']['mno'] = '';
$_SESSION['music']['artist'] = '';
$_SESSION['updated'] = '1';

// データ出力
header('Content-Type: application/json');
echo json_encode($_SESSION);

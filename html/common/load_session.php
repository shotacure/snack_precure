<?php

// 設定読み込み
require_once __DIR__ . '/config.php';

// データクラス読み込み
require_once __DIR__ . '/data.php';

// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

// セッション初期化
if ($_SESSION['music'] == null) {
    // セッション初期化
    $_SESSION['music']['series'] = '';
    $_SESSION['music']['disc'] = '';
    $_SESSION['music']['title'] = '';
    $_SESSION['music']['mno'] = '';
    $_SESSION['music']['artist'] = '';
    $_SESSION['dj']['current']['name'] = '';
    $_SESSION['dj']['current']['html'] = '';
    $_SESSION['dj']['next']['name'] = '';
    $_SESSION['dj']['next']['time'] = '';
    $_SESSION['dj']['next']['html'] = '';
    $_SESSION['dj']['corner']['id'] = '';
    $_SESSION['dj']['corner']['html'] = '';
    $_SESSION['dj']['hashtag'] = '';
    $_SESSION['updated'] = '0';
}

// データ出力
header('Content-Type: application/json');
echo json_encode($_SESSION);
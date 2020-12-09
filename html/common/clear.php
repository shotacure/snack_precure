<?php

// 設定読み込み
require_once __DIR__ . '/config.php';

// データクラス読み込み
require_once __DIR__ . '/data.php';

// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

// セッション初期化
$_SESSION['music']['series']['id'] = '';
$_SESSION['music']['series']['year'] = '';
$_SESSION['music']['series']['name'] = '';
$_SESSION['music']['disc']['title'] = '';
$_SESSION['music']['song']['title'] = '';
$_SESSION['music']['song']['artist'] = '';
$_SESSION['music']['song']['mno'] = '';
$_SESSION['music']['song']['menu'] = '';
$_SESSION['music']['song']['composer'] = '';
$_SESSION['music']['song']['arranger'] = '';   

// データ出力
header('Content-Type: application/json');
echo json_encode($_SESSION);

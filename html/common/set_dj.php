<?php

// 設定読み込み
require_once __DIR__ . '/config.php';

// データクラス読み込み
require_once __DIR__ . '/data.php';

// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

// インスタンス
$data = new PrecureMusicData();

// DJ情報
$_SESSION['dj']['current']['name'] = $_POST['dj_current_name'];
$_SESSION['dj']['next']['name'] = $_POST['dj_next_name'];
$_SESSION['dj']['next']['time'] = $_POST['dj_next_time'];
$_SESSION['dj']['corner'] = $_POST['dj_corner'];

// データ出力
header('Content-Type: application/json');
echo json_encode($_SESSION);

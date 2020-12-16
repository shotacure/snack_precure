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

// インスタンス
$data = new PrecureMusicData();

// 現在DJ
$current_name = $_POST['dj_current_name'];
if (!empty($current_name)) {
    $current_html = 'DJ ' . $current_name;
} else {
    $current_html = '';
}

// 次DJ
$next_name = $_POST['dj_next_name'];
$next_time = $_POST['dj_next_time'];
if (!empty($next_name)) {
    if (!empty($next_time)) {
        $next_html = 'Next(' . $next_time . '～) ' . $next_name;
    } else {
        $next_html = 'Next ' . $next_name;
    }
} else {
    $next_html = '';
}

// コーナー
$corner_id = $_POST['dj_corner'];
if (!empty($corner_id)) {
    $corner_html = '<img id="corner-img" src="./common/img/' . $corner_id . '@0.5x.png">';
} else {
    $corner_html = '';
}

// ハッシュタグ
if (!empty($_POST['dj_current_name'])) {
    $hashtag = '#すなっくプリキュア';
} else {
    $hashtag = '';
}

// セッション格納
$_SESSION['dj']['current']['name'] = $current_name;
$_SESSION['dj']['current']['html'] = $current_html;
$_SESSION['dj']['next']['name'] = $next_name;
$_SESSION['dj']['next']['time'] = $next_time;
$_SESSION['dj']['next']['html'] = $next_html;
$_SESSION['dj']['corner']['id'] = $corner_id;
$_SESSION['dj']['corner']['html'] = $corner_html;
$_SESSION['dj']['hashtag'] = $hashtag;

// データ出力
header('Content-Type: application/json');
echo json_encode($_SESSION);

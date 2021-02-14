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

// 臨時20210214ヒープリカウンター
if($_SESSION['music']['data']['class'] == 'song'
    && $_SESSION['music']['data']['id'] >= 491
    && $_SESSION['music']['data']['id'] <= 507) {
    $heaprecount = $data->getHeaPreCount();
    $_SESSION['music']['righttop_special'] = '本日かかった『ヒープリ』ソング<br>' . $heaprecount . '/17曲(' . round(($heaprecount * 100/ 17), 0, PHP_ROUND_HALF_DOWN)  . '%)';
}

// 更新フラグ
$_SESSION['updated'] = '1';

// データ出力
header('Content-Type: application/json');
echo json_encode($_SESSION);

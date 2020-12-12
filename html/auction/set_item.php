<?php

// データクラス読み込み
require_once __DIR__ . '/data.php';

// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

// インスタンス
$data = new AuctionData();

// POST値取得
$id = $_POST['id'];

// アイテム
$item = $data->getItemData($id);

// 取得データをセッションに入れる
$_SESSION['music']['series'] = $item['series'];
$_SESSION['music']['disc'] = $item['disc'];
$_SESSION['music']['lefttop_special'] = getLeftTopSpecial($item);

$_SESSION['music']['title'] = $item['title'];
$_SESSION['music']['mno'] = $item['mno'];
$_SESSION['music']['artist'] = $item['artist'];
$_SESSION['music']['css'] = $item['css'];

$_SESSION['dj']['corner']['html'] = getCornerHTML($item);

$_SESSION['music']['rightbottom_special'] = getRightBottomSpecial($item);

// データ出力
header('Content-Type: application/json');
echo json_encode($_SESSION);

/**
 * 左上のスペシャルを取得する
 */
function getLeftTopSpecial($arg) {
    return '';
}

/**
 * CornerHTMLを取得する
 */
function getCornerHTML($arg) {
    // プリキュア5対応
    if (!empty($_SESSION['dj']['corner']['id'])) {
        return '<img id="corner-img" src="./common/img/' . $_SESSION['dj']['corner']['id'] . '@0.5x.png">';
    } else {
        return '';
    }
}

/**
 * 右下のスペシャルを取得する
 */
function getRightBottomSpecial($arg) {
    return '';
}

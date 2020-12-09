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

// POST値取得
$class = $_POST['class'];
$id = $_POST['id'];

if ($class === 'song' && !empty($id)) {
    // 歌曲
    $song = $data->getSongData($id);

    // 取得データをセッションに入れる
    $_SESSION['song']['series']['id'] = $song['series_id'];
    $_SESSION['song']['series']['year'] = mb_substr($song['series_id'], 0, 4);
    $_SESSION['song']['series']['name'] = $song['series_title'];
    $_SESSION['song']['album'] = $song['disc_title'];
    $_SESSION['song']['title'] = $song['song_title'];
    $_SESSION['song']['artist'] = $song['singer_name'];

    // 劇伴系は空にする
    $_SESSION['song']['mno'] = '';
    $_SESSION['song']['menu'] = '';
    $_SESSION['song']['composer'] = '';
    $_SESSION['song']['arranger'] = '';    
} elseif ($class === 'bgm' && !empty($id)) {
    // 劇伴
    $bgm = $data->getBGMData($id);

    // 取得データをセッションに入れる
    $_SESSION['song']['series']['id'] = $bgm['series_id'];
    $_SESSION['song']['series']['year'] = mb_substr($bgm['series_id'], 0, 4);
    $_SESSION['song']['series']['name'] = $bgm['series_title'];
    $_SESSION['song']['album'] = $bgm['disc_title'];
    $_SESSION['song']['title'] = '♪' . $bgm['track_title'];
    $_SESSION['song']['mno'] = $bgm['m_no_detail'];
    $_SESSION['song']['menu'] = $bgm['menu'];
    $_SESSION['song']['composer'] = $bgm['composer_name'];
    $_SESSION['song']['arranger'] = $bgm['arranger_name'];

    // 歌曲系は空にする
    $_SESSION['song']['artist'] = '';    
} else {
    // ブランク時は空にする
    $_SESSION['song']['series']['id'] = '';
    $_SESSION['song']['series']['year'] = '';
    $_SESSION['song']['series']['name'] = '';
    $_SESSION['song']['album'] = '';
    $_SESSION['song']['title'] = '';
    $_SESSION['song']['artist'] = '';
    $_SESSION['song']['mno'] = '';
    $_SESSION['song']['menu'] = '';
    $_SESSION['song']['composer'] = '';
    $_SESSION['song']['arranger'] = '';
}

// フォント変更
switch ($_SESSION['song']['series']['id']) {
    case '20150201':
    case '20151031':
        $_SESSION['song']['css'] = 'goprincess';
        break;
    case '20160207':
    case '20161029':
        $_SESSION['song']['css'] = 'maho';
        break;
    case '20170205':
    case '20171028':
        $_SESSION['song']['css'] = 'alamode';
        break;
    case '20190203':
    case '20191019':
        $_SESSION['song']['css'] = 'startwinkle';
        break;
    default:
        $_SESSION['song']['css'] = null;
}

// データ出力
header('Content-Type: application/json');
echo json_encode($_SESSION['song']);

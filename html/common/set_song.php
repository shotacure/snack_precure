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
    $_SESSION['music']['series']['id'] = $song['series_id'];
    $_SESSION['music']['series']['year'] = mb_substr($song['series_id'], 0, 4);
    $_SESSION['music']['series']['name'] = $song['series_title'];
    $_SESSION['music']['disc']['title'] = $song['disc_title'];
    $_SESSION['music']['song']['title'] = $song['song_title'];
    $_SESSION['music']['song']['artist'] = $song['singer_name'];

    // 劇伴系は空にする
    $_SESSION['music']['song']['mno'] = '';
    $_SESSION['music']['song']['menu'] = '';
    $_SESSION['music']['song']['composer'] = '';
    $_SESSION['music']['song']['arranger'] = '';    
} elseif ($class === 'bgm' && !empty($id)) {
    // 劇伴
    $bgm = $data->getBGMData($id);

    // 取得データをセッションに入れる
    $_SESSION['music']['series']['id'] = $bgm['series_id'];
    $_SESSION['music']['series']['year'] = mb_substr($bgm['series_id'], 0, 4);
    $_SESSION['music']['series']['name'] = $bgm['series_title'];
    $_SESSION['music']['disc']['title'] = $bgm['disc_title'];
    $_SESSION['music']['song']['title'] = '♪' . $bgm['track_title'];
    $_SESSION['music']['song']['mno'] = $bgm['m_no_detail'];
    $_SESSION['music']['song']['menu'] = $bgm['menu'];
    $_SESSION['music']['song']['composer'] = $bgm['composer_name'];
    $_SESSION['music']['song']['arranger'] = $bgm['arranger_name'];

    // 歌曲系は空にする
    $_SESSION['music']['song']['artist'] = '';    
}

// フォント変更
switch ($_SESSION['music']['series']['id']) {
    case '20150201':
    case '20151031':
        $_SESSION['music']['song']['css'] = 'goprincess';
        break;
    case '20160207':
    case '20161029':
        $_SESSION['music']['song']['css'] = 'maho';
        break;
    case '20170205':
    case '20171028':
        $_SESSION['music']['song']['css'] = 'alamode';
        break;
    case '20190203':
    case '20191019':
        $_SESSION['music']['song']['css'] = 'startwinkle';
        break;
    default:
        $_SESSION['music']['song']['css'] = '';
}

// データ出力
header('Content-Type: application/json');
echo json_encode($_SESSION['music']);

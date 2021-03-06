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

// POST値取得
$class = $_POST['class'];
$id = $_POST['id'];

if ($class === 'song' && !empty($id)) {
    // 歌曲
    $song = $data->getSongData($id);

    // 取得データをセッションに入れる
    $_SESSION['music']['data']['class'] = $class;
    $_SESSION['music']['data']['id'] = $id;
    $_SESSION['music']['data']['series'] = $song['series_id'];

    $_SESSION['music']['series'] = mb_substr($song['series_id'], 0, 4) . ' 『' . $song['series_title'] . '』';
    $_SESSION['music']['disc'] = '「' . $song['disc_title'] . '」';
    $_SESSION['music']['lefttop_special'] = getLeftTopSpecial($song);

    $_SESSION['music']['title'] = $song['song_title'];
    $_SESSION['music']['artist'] = $song['singer_name'];

    $_SESSION['dj']['corner']['html'] = getCornerHTML($song);

    $_SESSION['music']['righttop_special'] = getRightTopSpecial($song);
    $_SESSION['music']['rightbottom_special'] = getRightBottomSpecial($song);

    // 劇伴系は空にする
    $_SESSION['music']['mno'] = '';

    // シリーズ固有のデザイン
    $_SESSION['music']['css'] = getCSS($song['series_id']);
} elseif ($class === 'bgm' && !empty($id)) {
    // 劇伴
    $bgm = $data->getBGMData($id);

    // 取得データをセッションに入れる
    $_SESSION['music']['data']['class'] = $class;
    $_SESSION['music']['data']['id'] = $id;
    $_SESSION['music']['data']['series'] = $bgm['series_id'];

    $_SESSION['music']['series'] = mb_substr($bgm['series_id'], 0, 4) . ' 『' . $bgm['series_title'] . '』';
    $_SESSION['music']['disc'] = '「' . $bgm['disc_title'] . '」';
    $_SESSION['music']['lefttop_special'] = getLeftTopSpecial($bgm);

    $_SESSION['music']['title'] = '♪' . $bgm['track_title'];

    if (!empty($bgm['m_no_detail'])) {
        if (!empty($bgm['menu'])) {
            $mno = '(' . $bgm['m_no_detail'] . ' [' . $bgm['menu'] .'])';
        } else {
            $mno = '(' . $bgm['m_no_detail'] . ')';
        }
    } else {
        $mno = '';
    }
    $_SESSION['music']['mno'] = $mno;

    if ($bgm['composer_name'] === $bgm['arranger_name']) {
        $artist = '音楽: ' .  $bgm['composer_name'];
    } else {
        $artist = '音楽: ' .  $bgm['arranger_name'] . ' (作曲: ' .  $bgm['composer_name'] . ')';
    }
    $_SESSION['music']['artist'] = $artist;

    $_SESSION['dj']['corner']['html'] = getCornerHTML($bgm);

    $_SESSION['music']['righttop_special'] = getRightTopSpecial($bgm);
    $_SESSION['music']['rightbottom_special'] = getRightBottomSpecial($bgm);

    // シリーズ固有のデザイン
    $_SESSION['music']['css'] = getCSS($bgm['series_id']);
}

// データ出力
header('Content-Type: application/json');
echo json_encode($_SESSION);

/**
 * シリーズ固有のデザインを取得する
 */
function getCSS($series_id) {
    switch ($series_id) {
        case '20150201':
        case '20151031':
            return 'goprincess';
        case '20160207':
        case '20161029':
            return 'maho';
        case '20170205':
        case '20171028':
            return 'alamode';
        case '20190203':
        case '20191019':
            return 'startwinkle';
        default:
            return '';
    }
}

/**
 * 左上のスペシャルを取得する
 */
function getLeftTopSpecial($arg) {
    if(in_array($arg['series_id'], [
        '20070204',
        '20071110',
        '20080203',
        '20080205',
        '20081108',
    ])) {
        return '「Yes!プリキュア5&GoGo! メモリアルアルバム」4月7日(水) 発売！';
    } elseif(in_array($arg['series_id'], [
        '20200202',
        '20201031',
    ])) {
        return '「ヒーリングっど♥プリキュア 感謝祭 オンライン」2月21日(日) 配信！';
    } elseif(in_array($arg['series_id'], [
        '20210228',
    ])) {
        return '『トロピカル〜ジュ！プリキュア』主題歌シングル4月7日(水) 発売！';
    } else {
        return '';
    }
}

/**
 * CornerHTMLを取得する
 */
function getCornerHTML($arg) {
    // if (!empty($_SESSION['dj']['corner']['id'])) {
    //     return '<img id="corner-img" src="./common/img/' . $_SESSION['dj']['corner']['id'] . '@0.5x.png">';
    // } else {
    //     return '';
    // }
}

/**
 * 右上のスペシャルを取得する
 */
function getRightTopSpecial($arg) {
    if(in_array($arg['series_id'], [
        '20060205',
        '20061209',
    ])) {
        return '『ふたりはプリキュアSplash☆Star』<br>祝・15周年！';
    } elseif(in_array($arg['series_id'], [
        '20110206',
        '20110319',
        '20110731',
        '20111029',
    ])) {
        return '『スイートプリキュア♪』祝・10周年！';
    } elseif(in_array($arg['series_id'], [
        '20160207',
        '20160319',
        '20161029',
    ])) {
        return '『魔法つかいプリキュア！』祝・5周年！';
    } else {
        return '';
    }
}

/**
 * 右下のスペシャルを取得する
 */
function getRightBottomSpecial($arg) {
}

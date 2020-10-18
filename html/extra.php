<?php

// 設定読み込み
require_once __DIR__ . '/common/config.php';

// データクラス読み込み
require_once __DIR__ . '/common/data.php';

// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

// エクストラ楽曲
$extra_song = [
    'de2005OP' => [
        'series_year' => '2005',
        'series_title' => 'PRETTY CURE',
        'disc_title' => '',
        'song_title' => 'NUR WIR BEIDE',
        'singer_name' => 'Sina Schymanski',
    ],
    'de2005ED' => [
        'series_year' => '2005',
        'series_title' => 'PRETTY CURE',
        'disc_title' => '',
        'song_title' => 'TRÄUME, DIE ICH HAB\'',
        'singer_name' => 'Petra Scheeser',
    ],
    '2007OPjazz' => [
        'series_year' => '2009',
        'series_title' => 'キラッと☆ジャズ ~KIRA JAZZ~',
        'disc_title' => '',
        'song_title' => 'プリキュア5、スマイル go go!',
        'singer_name' => '8ch',
    ],
    '2008OPjazz' => [
        'series_year' => '2008',
        'series_title' => 'アニジャズ コンボ',
        'disc_title' => '',
        'song_title' => 'プリキュア5、フル・スロットル GoGo!',
        'singer_name' => '野口 茜、若林美佐、野村綾乃',
    ],
    'kagome120' => [
        'series_year' => '2018',
        'series_title' => 'カゴメ創業120周年記念ソング',
        'disc_title' => '',
        'song_title' => '「進めカゴメ」（カゴメ創業120周年記念ソング）',
        'singer_name' => '北川理恵',
    ],
    'nomore2020' => [
        'series_year' => '2020',
        'series_title' => 'NO MORE映画泥棒',
        'disc_title' => '',
        'song_title' => '「NO MORE映画泥棒」劇場用CM(2020)',
        'singer_name' => '「映画館に行こう！」実行委員会',
    ],
];

$extra_bgm = [
    'toei_title' => [
        'series_year' => '1996',
        'series_title' => '金田一少年の事件簿(劇場版)',
        'disc_title' => '',
        'm_no_detail' => '',
        'menu' => '',
        'track_title' => '東映動画タイトル',
        'composer_name' => '和田 薫',
        'arranger_name' => '和田 薫', 
    ],
    'heapre_ep29' => [
        'series_year' => '2020',
        'series_title' => 'ヒーリングっど♥プリキュア',
        'disc_title' => '',
        'm_no_detail' => '',
        'menu' => '',
        'track_title' => '#29吹奏楽練習(ことえ&有斗)',
        'composer_name' => '寺田志保',
        'arranger_name' => '寺田志保', 
    ],
];

if ($_POST['mode'] === 'send') {
    // 送出モード
    if ($_POST['musictype'] === 'Vocal' && !empty($_POST["song_id"])) {
        // 歌曲指定時

        // 歌曲データ取得
        $songdata = $extra_song[$_POST["song_id"]];

        // 取得データをセッションに入れる
        $_SESSION['song']['musictype'] = $_POST['musictype'];
        $_SESSION['song']['id'] = $_POST["song_id"];
        $_SESSION['song']['series']['id'] = '';
        $_SESSION['song']['series']['year'] = $songdata['series_year'];
        $_SESSION['song']['series']['name'] = $songdata['series_title'];
        $_SESSION['song']['album'] = $songdata['disc_title'];
        $_SESSION['song']['title'] = $songdata['song_title'];
        $_SESSION['song']['artist'] = $songdata['singer_name'];

        // 劇伴系は空にする
        $_SESSION['song']['mno'] = '';
        $_SESSION['song']['menu'] = '';
        $_SESSION['song']['composer'] = '';
        $_SESSION['song']['arranger'] = '';

    } elseif ($_POST['musictype'] === 'BGM' && !empty($_POST["bgm_id"])) {
        // 劇伴指定時

        // 劇伴データ取得
        $bgmdata = $extra_bgm[$_POST["bgm_id"]];

        // 取得データをセッションに入れる
        $_SESSION['song']['musictype'] = $_POST['musictype'];
        $_SESSION['song']['id'] = $_POST["bgm_id"];
        $_SESSION['song']['series']['id'] = '';
        $_SESSION['song']['series']['year'] = $bgmdata['series_year'];
        $_SESSION['song']['series']['name'] = $bgmdata['series_title'];
        $_SESSION['song']['album'] = $bgmdata['disc_title'];
        $_SESSION['song']['title'] = '♪' . $bgmdata['track_title'];
        $_SESSION['song']['mno'] = $bgmdata['m_no_detail'];
        $_SESSION['song']['menu'] = $bgmdata['menu'];
        $_SESSION['song']['composer'] = $bgmdata['composer_name'];
        $_SESSION['song']['arranger'] = $bgmdata['arranger_name'];

        // 歌曲系は空にする
        $_SESSION['song']['artist'] = '';

    } else {
            // ブランク時は空にする
        $_SESSION['song']['musictype'] = '';
        $_SESSION['song']['id'] = '';
        $_SESSION['song']['series']['year'] = '';
        $_SESSION['song']['series']['name'] = '';
        $_SESSION['song']['album'] = '';
        $_SESSION['song']['title'] = '';
        $_SESSION['song']['artist'] = '';
        $_SESSION['song']['mno'] = '';
        $_SESSION['song']['composer'] = '';
        $_SESSION['song']['arranger'] = '';
    }

    // フォント変更
    $_SESSION['song']['css'] = null;

    // DJ情報
    $_SESSION['dj']['current']['name'] = $_POST['dj_current_name'];
    $_SESSION['dj']['next']['name'] = $_POST['dj_next_name'];
    $_SESSION['dj']['next']['time'] = $_POST['dj_next_time'];
    $_SESSION['dj']['corner'] = $_POST['dj_corner'];

    // 更新フラグ
    $_SESSION['updated'] = '1';

}
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>すなっくプリキュア タイトルジェネレータ</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel="stylesheet" href="./common/css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="./common/scripts/index.js"></script>
    </head>
    <body>
        <form method="post">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title pointer"><i class="glyphicon glyphicon-music"></i> 歌曲</h4>
                    </div>
                    <div class="panel-body">
                        <div class="panel-collapse collapse in">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-md-2">歌曲選択</label>
                                    <div class="col-md-8">
                                        <select id="song_id" name="song_id" class="form-control">
                                            <option value=""></option>
                                            <?php foreach($extra_song as $song_id => $row) : ?>
                                            <option value="<?= $song_id ?>"<?= ($song_id === $_SESSION['song']['id'] ? ' selected' : '') ?>><?= $row['series_year'] ?>: <?= $row['song_title'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title pointer"><i class="glyphicon glyphicon-music"></i> 劇伴</h4>
                    </div>
                    <div class="panel-body">
                        <div class="panel-collapse collapse in">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-md-2">劇伴選択</label>
                                    <div class="col-md-8">
                                        <select id="bgm_id" name="bgm_id" class="form-control">
                                            <option value=""></option>
                                            <?php foreach($extra_bgm as $bgm_id => $row) : ?>
                                            <option value="<?= $bgm_id ?>"<?= ($bgm_id === $_SESSION['song']['id'] ? ' selected' : '') ?>><?= $row['series_year'] ?>:<?= (!preg_match('/^_temp_\d{6}$/', $row['m_no_detail']) ? $row['m_no_detail'] : '') ?> <?= $row['track_title'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title pointer"><i class="glyphicon glyphicon-user"></i> DJ</h4>
                    </div>
                    <div class="panel-body">
                        <div class="panel-collapse collapse in">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-md-2">現在DJ</label>
                                    <div class="col-md-3">
                                        <select name="dj_current_name" class="form-control">
                                            <option value=""></option>
                                            <? foreach(DJ_LIST as $dj_current_name) : ?>
                                            <option value="<?= $dj_current_name ?>"<?= $dj_current_name === $_SESSION['dj']['current']['name'] ? ' selected' : '' ?>><?= $dj_current_name ?></option>
                                            <? endforeach; ?>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-2">コーナー</label>
                                    <div class="col-md-4">
                                        <select name="dj_corner" class="form-control">
                                            <option value=""></option>
                                            <? foreach(CORNER_LIST as $dj_corner_id => $dj_corner_name) : ?>
                                            <option value="<?= $dj_corner_id ?>"<?= $dj_corner_id === $_SESSION['dj']['corner'] ? ' selected' : '' ?>><?= $dj_corner_name ?></option>
                                            <? endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">次DJ</label>
                                    <div class="col-md-3">
                                        <select name="dj_next_name" class="form-control">
                                            <option value=""></option>
                                            <? foreach(DJ_LIST as $dj_next_name) : ?>
                                            <option value="<?= $dj_next_name ?>"<?= $dj_next_name === $_SESSION['dj']['next']['name'] ? ' selected' : '' ?>><?= $dj_next_name ?></option>
                                            <? endforeach; ?>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-2">次DJ時間</label>
                                    <div class="col-md-3">
                                        <input type="text" name="dj_next_time" class="form-control" value="<?= $_SESSION['dj']['next']['time'] ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" id="send" class="btn btn btn-danger btn-lg"><i class="glyphicon glyphicon-send"></i> 送出</button>
                <button type="button" id="music_clear" class="btn btn-warning btn-lg"><i class="glyphicon glyphicon-remove-sign"></i> 楽曲クリア</button>
            </div>
            <input type="hidden" id="mode" name="mode" value="<?= $_POST['mode'] ?>">
            <input type="hidden" id="musictype" name="musictype" value="<?= $_SESSION['song']['musictype'] ?>">
            <input type="hidden" id="searchcondition" name="searchcondition" value="<?= $_SESSION['search']['condition'] ?>">
        </form>
    </body>
</html>
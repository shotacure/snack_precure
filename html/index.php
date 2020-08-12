<?php

// 設定読み込み
require __DIR__ . '/common/config.php';

// データクラス読み込み
require __DIR__ . '/common/data.php';

// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

// インスタンス
$data = new PrecureMusicData();

if ($_POST['mode'] === 'send') {
    // 送出モード
    if ($_POST['musictype'] === 'Vocal' && !empty($_POST["song_id"])) {
        // 歌曲指定時

        // 歌曲データ取得
        $songdata = $data->getSongData($_POST["song_id"]);

        // 取得データをセッションに入れる
        $_SESSION['song']['musictype'] = $_POST['musictype'];
        $_SESSION['song']['id'] = $songdata['song_id'];
        $_SESSION['song']['series']['year'] = mb_substr($songdata['series_id'], 0, 4);
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
        $bgmdata = $data->getBGMData($_POST["bgm_id"]);

        // 取得データをセッションに入れる
        $_SESSION['song']['musictype'] = $_POST['musictype'];
        $_SESSION['song']['id'] = $bgmdata['disc_id'] . '_' . $bgmdata['track_no'];
        $_SESSION['song']['series']['year'] = mb_substr($bgmdata['series_id'], 0, 4);
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

    // DJ情報
    $_SESSION['dj']['current']['name'] = $data->getEscapeString($_POST['dj_current_name']);
    $_SESSION['dj']['next']['name'] = $data->getEscapeString($_POST['dj_next_name']);
    $_SESSION['dj']['next']['time'] = $data->getEscapeString($_POST['dj_next_time']);

    // 更新フラグ
    $_SESSION['updated'] = '1';

} else if ($_POST['mode'] === 'search') {
    // 検索条件設定時
    $_SESSION['search']['song_disc_id'] = '';
    $_SESSION['search']['song_series_id'] = '';
    $_SESSION['search']['song_title'] = '';
    $_SESSION['search']['song_singer_name'] = '';

    $_SESSION['search']['bgm_disc_id'] = '';
    $_SESSION['search']['bgm_series_id'] = '';
    $_SESSION['search']['bgm_title'] = '';

    $_SESSION['search']['condition'] = $data->getEscapeString($_POST['searchcondition']);

    if ($_POST[$_SESSION['search']['condition']] !== '') {
        $_SESSION['search'][$_SESSION['search']['condition']] = $data->getEscapeString($_POST[$_SESSION['search']['condition']]);
    }
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
                                    <label class="control-label col-md-2">シリーズ</label>
                                    <div class="col-md-8">
                                        <select id="song_series_id" name="song_series_id" class="form-control">
                                            <option value=""></option>
                                            <?php foreach($data->getSongSeriesList() as $series_id => $series_title) : ?>
                                            <option value="<?= $series_id ?>"<?= ($series_id == $_SESSION['search']['song_series_id'] ? ' selected' : '') ?>><?= mb_substr($series_id, 0, 4) ?>: <?= $series_title ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">ディスク</label>
                                    <div class="col-md-8">
                                        <select id="song_disc_id" name="song_disc_id" class="form-control">
                                            <option value=""></option>
                                            <?php foreach($data->getoSongDiscList() as $disc_id => $row) : ?>
                                            <option value="<?= $disc_id ?>"<?= ($disc_id == $_SESSION['search']['song_disc_id'] ? ' selected' : '') ?>><?= (!empty($row['series_id']) ? mb_substr($row['series_id'], 0, 4) : '0000') ?>: <?= $row['disc_title'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">曲名</label>
                                    <div class="col-md-8">
                                        <input type="text" id="song_title" name="song_title" class="form-control" value="<?= $_SESSION['search']['song_title'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">歌手</label>
                                    <div class="col-md-8">
                                        <input type="text" id="song_singer_name" name="song_singer_name" class="form-control" value="<?= $_SESSION['search']['song_singer_name'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4 offset-md-4">
                                        <button type="button" id="song_clear" class="btn btn-primary btn-block"><i class="glyphicon glyphicon-remove-sign"></i> 歌曲条件クリア</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">歌曲選択</label>
                                    <div class="col-md-8">
                                        <select id="song_id" name="song_id" class="form-control">
                                            <option value=""></option>
                                            <?php foreach($data->getSongSearchList($_SESSION['search']) as $song_id => $row) : ?>
                                            <option value="<?= $song_id ?>"<?= ($song_id === $_SESSION['song']['id'] ? ' selected' : '') ?>><?= mb_substr($row['series_id'], 0, 4) ?>: <?= $row['song_title'] ?></option>
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
                                    <label class="control-label col-md-2">シリーズ</label>
                                    <div class="col-md-8">
                                        <select id="bgm_series_id" name="bgm_series_id" class="form-control">
                                            <option value=""></option>
                                            <?php foreach($data->getBGMSeriesList() as $series_id => $series_title) : ?>
                                            <option value="<?= $series_id ?>"<?= ($series_id == $_SESSION['search']['bgm_series_id'] ? ' selected' : '') ?>><?= mb_substr($series_id, 0, 4) ?>: <?= $series_title ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">ディスク</label>
                                    <div class="col-md-8">
                                        <select id="bgm_disc_id" name="bgm_disc_id" class="form-control">
                                            <option value=""></option>
                                            <?php foreach($data->getBGMDiscList() as $disc_id => $row) : ?>
                                            <option value="<?= $disc_id ?>"<?= ($disc_id == $_SESSION['search']['bgm_disc_id'] ? ' selected' : '') ?>><?= (!empty($row['series_id']) ? mb_substr($row['series_id'], 0, 4) . '' : '0000') ?>: <?= $row['disc_title'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">曲名</label>
                                    <div class="col-md-8">
                                        <input type="text" id="bgm_title" name="bgm_title" class="form-control" value="<?= $_SESSION['search']['bgm_title'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4 offset-md-4">
                                        <button type="button" id="bgm_clear" class="btn btn-primary btn-block"><i class="glyphicon glyphicon-remove-sign"></i> 劇伴条件クリア</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">劇伴選択</label>
                                    <div class="col-md-8">
                                        <select id="bgm_id" name="bgm_id" class="form-control">
                                            <option value=""></option>
                                            <?php foreach($data->getBGMSearchList($_SESSION['search']) as $bgm_id => $row) : ?>
                                            <option value="<?= $bgm_id ?>"<?= ($bgm_id === $_SESSION['song']['id'] ? ' selected' : '') ?>><?= mb_substr($row['series_id'], 0, 4) ?>:<?= (!preg_match('/^_temp_\d{6}$/', $row['m_no_detail']) ? $row['m_no_detail'] : '') ?> <?= $row['track_title'] ?></option>
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
                                    <div class="col-md-4">
                                        <select name="dj_current_name" class="form-control">
                                            <option value=""></option>
                                            <? foreach(DJ_LIST as $dj_current_name) : ?>
                                            <option value="<?= $dj_current_name ?>"<?= $dj_current_name === $_SESSION['dj']['current']['name'] ? ' selected' : '' ?>><?= $dj_current_name ?></option>
                                            <? endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">次DJ</label>
                                    <div class="col-md-4">
                                        <select name="dj_next_name" class="form-control">
                                            <option value=""></option>
                                            <? foreach(DJ_LIST as $dj_next_name) : ?>
                                            <option value="<?= $dj_next_name ?>"<?= $dj_next_name === $_SESSION['dj']['next']['name'] ? ' selected' : '' ?>><?= $dj_next_name ?></option>
                                            <? endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">次DJ時間</label>
                                    <div class="col-md-4">
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
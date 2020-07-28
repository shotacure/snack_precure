<?php

// 設定読み込み
require dirname(__FILE__) . '/common/config.php';

// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

if ($_POST['send'] === 'send') {
    // パラメータをセッションに入れる
    if ($_SESSION['song']['series']['year'] !== $_POST['song_series_year']) {
        $_SESSION['song']['series']['year'] = $_POST['song_series_year'];
        $_SESSION['updated'] = '1';
    }
    if ($_SESSION['song']['series']['name'] !== $_POST['song_series_name']) {
        $_SESSION['song']['series']['name'] = $_POST['song_series_name'];
        $_SESSION['updated'] = '1';
    }
    if ($_SESSION['song']['album'] !== $_POST['song_album']) {
        $_SESSION['song']['album'] = $_POST['song_album'];
        $_SESSION['updated'] = '1';
    }
    if ($_SESSION['song']['title'] !== $_POST['song_title']) {
        $_SESSION['song']['title'] = $_POST['song_title'];
        $_SESSION['updated'] = '1';
    }
    if ($_SESSION['song']['artist'] !== $_POST['song_artist']) {
        $_SESSION['song']['artist'] = $_POST['song_artist'];
        $_SESSION['updated'] = '1';
    }
    if ($_SESSION['dj']['current']['name'] !== $_POST['dj_current_name']) {
        $_SESSION['dj']['current']['name'] = $_POST['dj_current_name'];
        $_SESSION['updated'] = '1';
    }
    if ($_SESSION['dj']['next']['name'] !== $_POST['dj_next_name']) {
        $_SESSION['dj']['next']['name'] = $_POST['dj_next_name'];
        $_SESSION['updated'] = '1';
    }
    if ($_SESSION['dj']['next']['time'] !== $_POST['dj_next_time']) {
        $_SESSION['dj']['next']['time'] = $_POST['dj_next_time'];
        $_SESSION['updated'] = '1';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>すなっくプリキュア タイトルジェネレータ</title>
        <link rel="stylesheet" href="./common/css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body>
    <form method="post">
    <div></div>
    <div></div>
    <div><input type="text" name="song_series_year" placeholder="シリーズ年" value="<?= $_SESSION['song']['series']['year'] ?>"></div>
    <div><input type="text" name="song_series_name" placeholder="シリーズ名" value="<?= $_SESSION['song']['series']['name'] ?>"></div>
    <div><input type="text" name="song_album" placeholder="アルバム" value="<?= $_SESSION['song']['album'] ?>"></div>
    <div><input type="text" name="song_title" placeholder="曲名" value="<?= $_SESSION['song']['title'] ?>"></div>
    <div><input type="text" name="song_artist" placeholder="アーティスト" value="<?= $_SESSION['song']['artist'] ?>"></div>
    <div><input type="text" name="dj_current_name" placeholder="現在DJ" value="<?= $_SESSION['dj']['current']['name'] ?>"></div>
    <div><input type="text" name="dj_next_name" placeholder="次DJ" value="<?= $_SESSION['dj']['next']['name'] ?>"></div>
    <div><input type="text" name="dj_next_time" placeholder="次DJ時間" value="<?= $_SESSION['dj']['next']['time'] ?>"></div>
    <button type="submit" name="send" value="send">送出</button>
    </form>
    </body>
</html>
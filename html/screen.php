<?php

// 設定読み込み
require_once __DIR__ . '/common/config.php';

// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
    <meta charset="utf-8">
        <title>すなっくプリキュア タイトルジェネレータ</title>
        <link rel="stylesheet" href="./common/css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="./common/scripts/screen.js"></script>
        <script type="text/javascript" src="//webfont.fontplus.jp/accessor/script/fontplus.js?Zxms2-UqCwA%3D&box=Go~A~tQ3zN8%3D&delay=1&pm=1&aa=1&ab=2" charset="utf-8"></script>
    </head>
    <body class="screen-body">
        <div class="screen-outer">
            <div class="screen-inner">
                <div id="left-top">
                    <div id="series" class="screen-title">
                        <?= $_SESSION['music']['series'] ?>
                    </div>
                    <div id="disc" class="screen-title">
                        <?= $_SESSION['music']['disc'] ?>
                    </div>
                    <div id="lefttop_special" class="screen-title">
                        <?= $_SESSION['music']['lefttop_special'] ?>
                    </div>
                </div>
                <div id="left-bottom">
                    <div id="title" class="screen-title <?= $_SESSION['music']['css'] ?>">
                        <?= $_SESSION['music']['title'] ?>
                    </div>
                    <div id="mno" class="screen-title <?= $_SESSION['music']['css'] ?>">
                        <?= $_SESSION['music']['mno'] ?>
                    </div>
                    <div id="artist" class="screen-title <?= $_SESSION['music']['css'] ?>">
                        <?= $_SESSION['music']['artist'] ?>
                    </div>
                </div>
                <div id="right-top">
                    <div id="current" class="screen-title">
                        <?= $_SESSION['dj']['current']['html'] ?>
                    </div>
                    <div id="next" class="screen-title">
                        <?= $_SESSION['dj']['next']['html'] ?>
                    </div>
                    <div id="corner">
                        <?= $_SESSION['dj']['corner']['html'] ?>
                    </div>
                    <div id="hashtag" class="screen-title">
                        <?= $_SESSION['dj']['hashtag'] ?>
                    </div>
                </div>
                <div id="right-bottom">
                    <div id="rightbottom_special" class="screen-title">
                        <?= $_SESSION['music']['rightbottom_special'] ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
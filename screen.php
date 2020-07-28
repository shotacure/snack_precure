<?php

// 設定読み込み
require dirname(__FILE__) . '/common/config.php';

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
        <script type="text/javascript" src="//webfont.fontplus.jp/accessor/script/fontplus.js?Zxms2-UqCwA%3D&box=Go~A~tQ3zN8%3D&aa=1&ab=2" charset="utf-8"></script>
    </head>
    <body class="screen-body">
        <div class="screen-outer">
            <div class="screen-inner">
                <div id="left-top">
                    <?php if (!empty($_SESSION['song']['series']['name'])) :?> 
                    <div id="song-series" class="screen-title">
                        <?= $_SESSION['song']['series']['year'] ?>『<?= $_SESSION['song']['series']['name'] ?>』
                    </div>
                    <?php endif; ?> 
                    <?php if (!empty($_SESSION['song']['album'])) :?> 
                    <div id="song-album" class="screen-title">
                        「<?= $_SESSION['song']['album'] ?>」
                    </div>
                    <?php endif; ?> 
                </div>
                <div id="left-bottom">
                    <?php if (!empty($_SESSION['song']['title'])) :?> 
                    <div id="song-title" class="screen-title">
                        <?= $_SESSION['song']['title'] ?>
                    </div>
                    <?php endif; ?> 
                    <?php if (!empty($_SESSION['song']['artist'])) :?> 
                    <div id="song-artist" class="screen-title">
                        <?= $_SESSION['song']['artist'] ?>
                    </div>
                    <?php endif; ?> 
                </div>
                <div id="right-top">
                    <?php if (!empty($_SESSION['dj']['current']['name'])) :?> 
                    <div id="dj-current" class="screen-title">
                        DJ <?= $_SESSION['dj']['current']['name'] ?>
                    </div>
                    <?php endif; ?> 
                    <?php if (!empty($_SESSION['dj']['next']['name'])) :?> 
                    <div id="dj-next" class="screen-title">
                        Next<?= !empty($_SESSION['dj']['next']['time']) ? '(' . $_SESSION['dj']['next']['time'] . '～)' : '' ?> <?= $_SESSION['dj']['next']['name'] ?>
                    </div>
                    <?php endif; ?> 
                </div>
            </div>
        </div>
    </body>
</html>
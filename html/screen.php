<?php

// 設定読み込み
require __DIR__ . '/common/config.php';

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
        <script type="text/javascript" src="//webfont.fontplus.jp/accessor/script/fontplus.js?Zxms2-UqCwA%3D&box=Go~A~tQ3zN8%3D&aa=1&ab=2" charset="utf-8"></script>
    </head>
    <body class="screen-body">
        <div class="screen-outer">
            <div class="screen-inner">
                <div id="left-top">
                    <div id="song-series" class="screen-title">
                        <?php if (!empty($_SESSION['song']['series']['name'])) : ?> 
                        <?= $_SESSION['song']['series']['year'] ?>『<?= $_SESSION['song']['series']['name'] ?>』
                        <?php endif; ?> 
                    </div>
                    <div id="song-album" class="screen-title">
                        <?php if (!empty($_SESSION['song']['album'])) : ?> 
                        「<?= $_SESSION['song']['album'] ?>」
                        <?php endif; ?> 
                    </div>
                </div>
                <div id="left-bottom">
                <div id="song-title<?= $_SESSION['song']['series']['year'] === '2019' ? '-startwinkle' : '' ?>" class="screen-title">
                        <?php if (!empty($_SESSION['song']['title'])) : ?> 
                        <?= $_SESSION['song']['title'] ?>
                        <?php endif; ?> 
                    </div>
                    <div id="song-mno<?= $_SESSION['song']['series']['year'] === '2019' ? '-startwinkle' : '' ?>" class="screen-title">
                        <?php if (!empty($_SESSION['song']['mno'])) : ?>
                            <?php if (!empty($_SESSION['song']['menu'])) : ?>
                            (<?= $_SESSION['song']['mno'] ?> [<?= $_SESSION['song']['menu'] ?>])
                            <?php else : ?>
                                (<?= $_SESSION['song']['mno'] ?>)
                            <?php endif; ?> 
                        <?php endif; ?> 
                    </div>
                    <div id="song-artist<?= $_SESSION['song']['series']['year'] === '2019' ? '-startwinkle' : '' ?>" class="screen-title">
                        <?php if (!empty($_SESSION['song']['artist'])) : ?> 
                        <?= $_SESSION['song']['artist'] ?>
                        <?php elseif (!empty($_SESSION['song']['composer'])) : ?> 
                            <?php if ($_SESSION['song']['composer'] === $_SESSION['song']['arranger']) : ?>
                            音楽: <?= $_SESSION['song']['composer'] ?>
                            <?php else : ?>
                            音楽: <?= $_SESSION['song']['arranger'] ?> (作曲: <?= $_SESSION['song']['composer'] ?>)
                            <?php endif; ?> 
                        <?php endif; ?> 
                    </div>
                </div>
                <div id="right-top">
                    <div id="dj-current" class="screen-title">
                        <?php if (!empty($_SESSION['dj']['current']['name'])) : ?> 
                        DJ <?= $_SESSION['dj']['current']['name'] ?>
                        <?php endif; ?> 
                    </div>
                    <div id="dj-next" class="screen-title">
                        <?php if (!empty($_SESSION['dj']['next']['name'])) : ?> 
                        Next<?= !empty($_SESSION['dj']['next']['time']) ? '(' . $_SESSION['dj']['next']['time'] . '～)' : '' ?> <?= $_SESSION['dj']['next']['name'] ?>
                        <?php endif; ?> 
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
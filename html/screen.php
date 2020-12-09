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
                    <div id="song-series" class="screen-title">
                        <?php if (!empty($_SESSION['music']['series']['name'])) : ?> 
                        <?= $_SESSION['music']['series']['year'] ?> 『<?= $_SESSION['music']['series']['name'] ?>』
                        <?php endif; ?> 
                    </div>
                    <div id="song-album" class="screen-title">
                        <?php if (!empty($_SESSION['music']['disc']['title'])) : ?> 
                        「<?= $_SESSION['music']['disc']['title'] ?>」
                        <?php endif; ?> 
                    </div>
                    <div id="song-special" class="screen-title">
                        <?php if (strpos($_SESSION['music']['song']['arranger'], '寺田志保') !== false) : ?> 
                        「ヒーリングっど♥プリキュア オリジナル・サウンドトラック2<br>プリキュア・サウンド・オアシス!!」 12月23日(水) 発売！
                        <?php endif; ?> 
                    </div>
                </div>
                <div id="left-bottom">
                <div id="song-title" class="screen-title <?= $_SESSION['music']['song']['css'] ?>">
                        <?php if (!empty($_SESSION['music']['song']['title'])) : ?> 
                        <?= $_SESSION['music']['song']['title'] ?>
                        <?php endif; ?> 
                    </div>
                    <div id="song-mno" class="screen-title <?= $_SESSION['music']['song']['css'] ?>">
                        <?php if (!empty($_SESSION['music']['song']['mno'])) : ?>
                            <?php if (!empty($_SESSION['music']['song']['menu'])) : ?>
                            (<?= $_SESSION['music']['song']['mno'] ?> [<?= $_SESSION['music']['song']['menu'] ?>])
                            <?php else : ?>
                                (<?= $_SESSION['music']['song']['mno'] ?>)
                            <?php endif; ?> 
                        <?php endif; ?> 
                    </div>
                    <div id="song-artist" class="screen-title <?= $_SESSION['music']['song']['css'] ?>">
                        <?php if (!empty($_SESSION['music']['song']['artist'])) : ?> 
                        <?= $_SESSION['music']['song']['artist'] ?>
                        <?php elseif (!empty($_SESSION['music']['song']['composer'])) : ?> 
                            <?php if ($_SESSION['music']['song']['composer'] === $_SESSION['music']['song']['arranger']) : ?>
                            音楽: <?= $_SESSION['music']['song']['composer'] ?>
                            <?php else : ?>
                            音楽: <?= $_SESSION['music']['song']['arranger'] ?> (作曲: <?= $_SESSION['music']['song']['composer'] ?>)
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
                    <div id="dj-corner">
                        <?php if (!empty($_SESSION['dj']['corner']) && ($_SESSION['dj']['corner'] != 'snack201018_riekitagawa' || strpos($_SESSION['song']['artist'], '北川理恵') !== false))  : ?> 
                        <img id="corner-img" src="./common/img/<?= $_SESSION['dj']['corner'] ?>@0.5x.png">
                        <?php endif; ?> 
                    </div>
                    <div id="hashtag" class="screen-title">
                        <?php if (!empty($_SESSION['dj']['current']['name'])) : ?> 
                        #すなっくプリキュア
                        <?php endif; ?> 
                    </div>
                </div>
                <div id="right-bottom">
                    <div id="artist-special" class="screen-title">
                        <?php if (strpos($_SESSION['music']['song']['artist'], '北川理恵') !== false) : ?>
                        「MY toybox～Rie Kitagawa<br>プリキュアソングコレクション～」<br>好評発売中！
                        <?php endif; ?> 
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
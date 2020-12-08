<?php

// 設定読み込み
require_once __DIR__ . '/config.php';

// データクラス読み込み
require_once __DIR__ . '/data.php';

// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

// セッションを空にする
$_SESSION['song']['id'] = '';
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
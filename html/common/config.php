<?php

// 接続設定
require_once __DIR__ . '/conn.php';

// セッション寿命(秒)
define('SESSION_LIFETIME', 1800);

// DJリスト
define('DJ_LIST', array('プリキュアおじさん', '100-200', '祥太'));

// コーナーリスト
define('CORNER_LIST', array(
    'snack200816_summer' => '夏っぽい曲増量中',
    'snack200816_request' => 'リクエスト強化中',
    'snack200816_charasong' => 'キャラソンスペシャル',
));
<?php

// 接続設定
require_once __DIR__ . '/conn.php';

// セッション寿命(秒)
define('SESSION_LIFETIME', 3600 * 7);

// DJリスト
define('DJ_LIST', array('プリキュアおじさん', '100-200', '祥太'));

// コーナーリスト
define('CORNER_LIST', array(
    'snack201018_movie' => '映画特集',
    'snack201018_riekitagawa' => '北川理恵Special',
    'snack200816_request' => 'リクエスト強化中',
));
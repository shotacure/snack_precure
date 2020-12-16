<?php

// 接続設定
require_once __DIR__ . '/conn.php';

// セッション寿命(秒)
define('SESSION_LIFETIME', 3600 * 7);

// DJリスト
define('DJ_LIST', [
    'プリキュアおじさん',
    '100-200',
    '祥太',
]);

// ログイン許可ユーザー
define('AUTHORIZED_USER', [
    'snack_precure',
    'precureojisan',
    '100_200',
    'daretoku_hana',
    'shota_',
]);

// コーナーリスト
define('CORNER_LIST', [
    'snack201213_precure5' => '夢と希望のプリキュア5特集',
    'snack200816_request' => 'リクエスト強化中',
    'snack201213_auction' => 'お店を応援！オークション',
]);
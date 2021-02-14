<?php

// 接続設定
require_once __DIR__ . '/conn.php';

// セッション寿命(秒)
const SESSION_LIFETIME = 3600 * 7;

// DJリスト
const DJ_LIST = [
    'プリキュアおじさん',
    '100-200',
    '祥太',
];

// ログイン許可ユーザー
const AUTHORIZED_USER = [
    'snack_precure',
    'precureojisan',
    '100_200',
    'daretoku_hana',
    'shota_',
];

// コーナーリスト
const CORNER_LIST = [
];
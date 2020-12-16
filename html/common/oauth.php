<?php

// 設定読み込み
require_once __DIR__ . '/config.php';

// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

// OAuth
require_once __DIR__ . '/../vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

if ($_GET) {
    // 
    $twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_GET['oauth_token'], $_SESSION['oauth_secret']);

    $params = [
        'oauth_verifier' => $_GET["oauth_verifier"],
    ];
    $access_token = $twitter->oauth('oauth/access_token', $params);

    if (in_array($access_token['screen_name'], AUTHORIZED_USER)) {
        $_SESSION['screen_name'] = $access_token['screen_name'];
        header('Location: ' . (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . '/title/');
    } else {
        http_response_code(401);
    }

} else {
    $twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $params = [
        'oauth_callback' => OAUTH_CALLBACK,
        'x_auth_access_type' => 'read'
    ];

    $request_token = $twitter->oauth('oauth/request_token', $params);
    $_SESSION['oauth_secret'] = $request_token['oauth_token_secret'];

    header('Location: https://api.twitter.com/oauth/authorize?oauth_token=' . $request_token['oauth_token']);
}
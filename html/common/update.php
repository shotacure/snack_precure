<?

// 設定読み込み
require dirname(__FILE__) . '/config.php';

// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

if (!isset($_SESSION['updated'])) {
    $_SESSION['updated'] = '0';
}

// セッションをjson出力
header('Content-type:application/json; charset=utf8');
echo json_encode($_SESSION);

// 更新フラグをOFFにする
$_SESSION['updated'] = '0';

// 終了
exit();
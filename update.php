<?

// 設定読み込み
require dirname(__FILE__) . '/common/config.php';

// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

// セッションをjson出力
header('Content-type:application/json; charset=utf8');
echo json_encode($_SESSION);

// 更新フラグをOFFにする
$_SESSION['song']['series']['year_updated'] = '0';
$_SESSION['song']['series']['name_updated'] = '0';
$_SESSION['song']['album_updated'] = '0';
$_SESSION['song']['title_updated'] = '0';
$_SESSION['song']['artist_updated'] = '0';
$_SESSION['dj']['current']['name_updated'] = '0';
$_SESSION['dj']['next']['name_updated'] = '0';
$_SESSION['dj']['next']['time_updated'] = '0';

// 終了
exit();
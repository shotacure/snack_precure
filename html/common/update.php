<?

// 設定読み込み
require_once __DIR__ . '/config.php';

// アクセス権限チェック
// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

if (!in_array($_SESSION['screen_name'], AUTHORIZED_USER)) {
    http_response_code(401);
    exit();
}

if ($_SESSION['updated'] === '1') {
    // セッションをjson出力
    header('Content-Type: application/json');
    echo json_encode($_SESSION);

    // 更新フラグをOFFにする
    $_SESSION['updated'] = '0';
}
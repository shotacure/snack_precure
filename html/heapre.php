<?php

// 設定読み込み
require_once __DIR__ . '/common/config.php';

// アクセス権限チェック
// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

if (!in_array($_SESSION['screen_name'], AUTHORIZED_USER)) {
    header('Location: ' . OAUTH_CALLBACK);
    exit();
}

// データクラス読み込み
require_once __DIR__ . '/common/data.php';

// インスタンス
$data = new PrecureMusicData();
$heapre_list = $data->getHeaPreList();
?>
<html>
<body>
<table border="1">
<tr>
<th>id</th>
<th>song_title</th>
<th>played</th>
</tr>
<?php foreach ($heapre_list as $key => $value) : ?>
<tr>
<td><?= $key ?></td>
<td style="<?= $value['played'] == 0 ? 'font-weight: bold; color: red;' : '' ?>"><?= $value['song_title'] ?></td>
<td><?= $value['played'] ?></td>
</tr>
<?php endforeach; ?>
</table>
</html>
<?php

// 設定読み込み
require dirname(__FILE__) . '/common/config.php';

// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

if ($_POST['send'] === 'send') {

    // DB接続
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($mysqli->connect_errno) {
        http_response_code(500);
        exit();
    }

    // 楽曲指定時
    if (!empty($_POST["song_id"])) {

        // 入力値のサニタイズ
        $song_id = $mysqli->real_escape_string($_POST["song_id"]);

        // クエリの実行
        $stmt = $mysqli->prepare("SELECT songs.song_id
                ,songs.series_id
                ,series.series_title
                ,discs.disc_title
                ,songs.song_title
                ,songs.singer_name
            FROM songs
            LEFT JOIN series
                ON songs.series_id = series.series_id
            LEFT JOIN tracks
                ON songs.song_id = tracks.song_id
            LEFT JOIN discs
                ON tracks.disc_id = discs.disc_id
            WHERE songs.song_id = ? AND
                tracks.song_type IS NULL AND
                (tracks.song_size = 'Full' OR
                tracks.song_size IS NULL)
            GROUP BY songs.song_id;");

        $stmt->bind_param(
            's',
            $song_id,
        );

        // ステートメントを実行
        $stmt->execute();

        // 結果をバインド
        $stmt->bind_result($row['song_id'], $row['series_id'], $row['series_title'], $row['disc_title'], $row['song_title'], $row['singer_name']);
        while ($stmt->fetch()) {
            $song_id = $row['song_id'];
            $series_id = $row['series_id'];
            $series_title = $row['series_title'];
            $disc_title = $row['disc_title'];
            $song_title = $row['song_title'];
            $singer_name = $row['singer_name'];
        }

        // 取得できない場合はエラー
        $stmt->store_result();
        if ($stmt->num_rows === 0) {
            http_response_code(500);
            $mysqli->close();
            exit();
        }

        // ステートメントを閉じる
        $stmt->close();

        // 文字列置換
        $disc_title = str_replace('~', '～', $disc_title);
        $disc_title = str_replace(' [CD+DVD盤]', '', $disc_title);
        $song_title = str_replace('~', '～', $song_title);

        // パラメータをセッションに入れる
        $_SESSION['song']['id'] = $song_id;
        $_SESSION['song']['series']['year'] = mb_substr($series_id, 0, 4);
        $_SESSION['song']['series']['name'] = $series_title;
        $_SESSION['song']['album'] = $disc_title;
        $_SESSION['song']['title'] = $song_title;
        $_SESSION['song']['artist'] = $singer_name;

    } else {
        // ブランク時は空にする
        $_SESSION['song']['id'] = '';
        $_SESSION['song']['series']['year'] = '';
        $_SESSION['song']['series']['name'] = '';
        $_SESSION['song']['album'] = '';
        $_SESSION['song']['title'] = '';
        $_SESSION['song']['artist'] = '';
    }

    $_SESSION['dj']['current']['name'] = $mysqli->real_escape_string($_POST['dj_current_name']);
    $_SESSION['dj']['next']['name'] = $mysqli->real_escape_string($_POST['dj_next_name']);
    $_SESSION['dj']['next']['time'] = $mysqli->real_escape_string($_POST['dj_next_time']);

    $_SESSION['updated'] = '1';

    // データベースの切断
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>すなっくプリキュア タイトルジェネレータ</title>
        <link rel="stylesheet" href="./common/css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body>
    <form method="post">
    <div>楽曲</div>
    <div>
        <select name="song_id">
            <option value=""></option>
<?php

// DB接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
    http_response_code(500);
    exit();
}

// クエリの実行
$stmt = $mysqli->prepare("SELECT songs.song_id
    ,songs.song_title
    ,songs.series_id
    FROM songs
    ORDER BY songs.song_id ASC;");

// ステートメントを実行
$stmt->execute();

// 結果をバインド
$stmt->bind_result($row['song_id'], $row['song_title'], $row['series_id']);
while ($stmt->fetch()) {
    echo '            <option value="' . $row['song_id']. '"' . ($row['song_id'] === $_SESSION['song']['id'] ? ' selected' : '') . '>' . mb_substr($row['series_id'], 0, 4) . ': ' . $row['song_title'] . '</option>' . "\n";
}

// 取得できない場合はエラー
$stmt->store_result();
if ($stmt->num_rows === 0) {
    http_response_code(500);
    $mysqli->close();
    exit();
}

// ステートメントを閉じる
$stmt->close();

// データベースの切断
$mysqli->close();
?>
        </select>
    </div>
    <div>現在DJ</div>
    <div>
        <select name="dj_current_name">
            <option value=""></option>
<? foreach(DJ_LIST as $dj_current_name) : ?>
            <option value="<?= $dj_current_name ?>"<?= $dj_current_name === $_SESSION['dj']['current']['name'] ? ' selected' : '' ?>><?= $dj_current_name ?></option>
<? endforeach; ?>
        </select>
    </div>
    <div>次DJ</div>
    <div>
        <select name="dj_next_name">
            <option value=""></option>
<? foreach(DJ_LIST as $dj_next_name) : ?>
            <option value="<?= $dj_next_name ?>"<?= $dj_next_name === $_SESSION['dj']['next']['name'] ? ' selected' : '' ?>><?= $dj_next_name ?></option>
<? endforeach; ?>
        </select>
    </div>
    <div><input type="text" name="dj_next_time" placeholder="次DJ時間" value="<?= $_SESSION['dj']['next']['time'] ?>"></div>
    <button type="submit" name="send" value="send">送出</button>
    </form>
    </body>
</html>
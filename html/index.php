<?php

// 設定読み込み
require dirname(__FILE__) . '/common/config.php';

// セッションスタート
session_set_cookie_params(SESSION_LIFETIME);
session_start();

// DB接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
    http_response_code(500);
    exit();
}

if ($_POST['send'] === 'send') {
    if ($_POST['musictype'] === 'Vocal') {
        // ボーカル曲指定時

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
        $_SESSION['song']['musictype'] = $_POST['musictype'];
        $_SESSION['song']['id'] = $song_id;
        $_SESSION['song']['series']['year'] = mb_substr($series_id, 0, 4);
        $_SESSION['song']['series']['name'] = $series_title;
        $_SESSION['song']['album'] = $disc_title;
        $_SESSION['song']['title'] = $song_title;
        $_SESSION['song']['artist'] = $singer_name;

        // 劇伴系は空にする
        $_SESSION['song']['mno'] = '';
        $_SESSION['song']['composer'] = '';
        $_SESSION['song']['arranger'] = '';
    } elseif ($_POST['musictype'] === 'BGM') {
        // 劇伴指定時 TODO:パラメータ

        // 入力値のサニタイズ
        $song_id = $mysqli->real_escape_string($_POST["song_id"]);

        // クエリの実行 TODO:SQL
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

        // 結果をバインド TODO: フィールド
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

        // 文字列置換 TODO: フィールド
        $disc_title = str_replace('~', '～', $disc_title);
        $disc_title = str_replace(' [CD+DVD盤]', '', $disc_title);
        $song_title = str_replace('~', '～', $song_title);

        // パラメータをセッションに入れる TODO: フィールド
        $_SESSION['song']['musictype'] = $_POST['musictype'];
        $_SESSION['song']['series']['year'] = mb_substr($series_id, 0, 4);
        $_SESSION['song']['series']['name'] = $series_title;
        $_SESSION['song']['album'] = $disc_title;
        $_SESSION['song']['title'] = $song_title;
        $_SESSION['song']['mno'] = '';
        $_SESSION['song']['composer'] = '';
        $_SESSION['song']['arranger'] = '';

        // ボーカル曲系は空にする
        $_SESSION['song']['id'] = '';
        $_SESSION['song']['artist'] = '';
    } else {
            // ブランク時は空にする
        $_SESSION['song']['musictype'] = '';
        $_SESSION['song']['id'] = '';
        $_SESSION['song']['series']['year'] = '';
        $_SESSION['song']['series']['name'] = '';
        $_SESSION['song']['album'] = '';
        $_SESSION['song']['title'] = '';
        $_SESSION['song']['artist'] = '';
        $_SESSION['song']['mno'] = '';
        $_SESSION['song']['composer'] = '';
        $_SESSION['song']['arranger'] = '';
    }

    $_SESSION['dj']['current']['name'] = $mysqli->real_escape_string($_POST['dj_current_name']);
    $_SESSION['dj']['next']['name'] = $mysqli->real_escape_string($_POST['dj_next_name']);
    $_SESSION['dj']['next']['time'] = $mysqli->real_escape_string($_POST['dj_next_time']);

    $_SESSION['updated'] = '1';
} else if ($_POST['send'] === 'search') {
    // 検索条件設定時
    $_SESSION['search']['song_disc_id'] = '';
    $_SESSION['search']['song_series_id'] = '';
    $_SESSION['search']['song_title'] = '';
    $_SESSION['search']['song_singer_name'] = '';

    $_SESSION['search']['condition'] = $mysqli->real_escape_string($_POST['search']);

    if ($_POST['search'] !== '') {
        $_SESSION['search'][$_SESSION['search']['condition']] = $mysqli->real_escape_string($_POST[$_SESSION['search']['condition']]);
    }
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
    <div>シリーズ</div>
    <div>
        <input type="radio" name="search" value="song_series_id" <?= ($_SESSION['search']['condition'] === 'song_series_id' ? ' checked="checked"' : '') ?>>
        <select name="song_series_id">
            <option value=""></option>
<?php
// クエリの実行 TODO: tracks にVocalでsizeがFullまたはnull、typeがnullなものを持つシリーズ
$stmt = $mysqli->prepare("SELECT series.series_id
        ,series.series_title
    FROM series
    INNER JOIN songs
        ON series.series_id = songs.series_id
    GROUP BY series.series_id
    ORDER BY series.series_id ASC;");

// ステートメントを実行
$stmt->execute();

// 結果をバインド
$stmt->bind_result($row['series_id'], $row['series_title']);
while ($stmt->fetch()) {
    echo '            <option value="' . $row['series_id']. '"' . ($row['series_id'] == $_SESSION['search']['song_series_id'] ? ' selected' : '') . '>' . mb_substr($row['series_id'], 0, 4) . ': ' . $row['series_title'] . '</option>' . "\n";
}

// ステートメントを閉じる
$stmt->close();
?>
        </select>
    </div>
    <div>ディスク</div>
    <div>
        <input type="radio" name="search" value="song_disc_id" <?= ($_SESSION['search']['condition'] === 'song_disc_id' ? ' checked="checked"' : '') ?>>
        <select name="song_disc_id">
            <option value=""></option>
<?php
// クエリの実行
$stmt = $mysqli->prepare("SELECT discs.disc_id
        ,discs.disc_title
        ,discs.series_id
    FROM discs
    INNER JOIN tracks
        ON discs.disc_id = tracks.disc_id
    WHERE tracks.track_class = 'Vocal'
        AND tracks.song_type IS NULL
        AND (tracks.song_size IS NULL
            OR tracks.song_size = 'Full')
        AND discs.disc_title NOT LIKE '%通常盤%'
    GROUP BY discs.disc_id
    ORDER BY discs.disc_id ASC;");

// ステートメントを実行
$stmt->execute();

// 結果をバインド
$stmt->bind_result($row['disc_id'], $row['disc_title'], $row['series_id']);
while ($stmt->fetch()) {
    echo '            <option value="' . $row['disc_id']. '"' . ($row['disc_id'] == $_SESSION['search']['song_disc_id'] ? ' selected' : '') . '>' . (!empty($row['series_id']) ? mb_substr($row['series_id'], 0, 4) . ': ' : '0000: ') . $row['disc_title'] . '</option>' . "\n";
}

// ステートメントを閉じる
$stmt->close();
?>
        </select>
    </div>
    <div>
        <input type="radio" name="search" value="song_title" <?= ($_SESSION['search']['condition'] === 'song_title' ? ' checked="checked"' : '') ?>>
        <input type="text" name="song_title" placeholder="曲名" value="<?= $_SESSION['search']['song_title'] ?>">
    </div>
    <div>
        <input type="radio" name="search" value="song_singer_name" <?= ($_SESSION['search']['condition'] === 'song_singer_name' ? ' checked="checked"' : '') ?>>
        <input type="text" name="song_singer_name" placeholder="歌手" value="<?= $_SESSION['search']['song_singer_name'] ?>">
    </div>
    <div><input type="radio" name="search" value="" <?= (empty($_SESSION['search']['condition']) ? ' checked="checked"' : '') ?>>条件クリア</div>
    <button type="submit" name="send" value="search">歌曲検索</button>
    <div>
        <input type="radio" name="musictype" value="Vocal" <?= ($_SESSION['song']['musictype'] === 'Vocal' ? ' checked="checked"' : '') ?>>
        歌曲
    </div>
    <div>
        <select name="song_id">
            <option value=""></option>
<?php

// クエリの実行
if (empty($_SESSION['search']['condition'])) {
    // 検索条件なし
    $stmt = $mysqli->prepare("SELECT songs.song_id
        ,songs.song_title
        ,songs.series_id
    FROM songs
    ORDER BY songs.song_id ASC;");
} else if ($_SESSION['search']['condition'] === 'song_series_id') {
    // シリーズ指定
    $stmt = $mysqli->prepare("SELECT songs.song_id
        ,songs.song_title
        ,songs.series_id
    FROM songs
    WHERE songs.series_id = ?
    ORDER BY songs.song_id ASC;");

    $stmt->bind_param(
        's',
        $_SESSION['search']['song_series_id'],
    );
} else if ($_SESSION['search']['condition'] === 'song_disc_id') {
    // ディスク指定
    $stmt = $mysqli->prepare("SELECT tracks.song_id
        ,songs.song_title
        ,songs.series_id
    FROM songs
    INNER JOIN tracks
        ON tracks.song_id = songs.song_id
    WHERE tracks.disc_id = ?
        AND tracks.song_type IS NULL
        AND (tracks.song_size IS NULL
            OR tracks.song_size = 'Full')
    ORDER BY tracks.track_no ASC;");

    $stmt->bind_param(
        's',
        $_SESSION['search']['song_disc_id'],
    );
} else if ($_SESSION['search']['condition'] === 'song_title') {
    // 曲名指定
    $stmt = $mysqli->prepare("SELECT songs.song_id
        ,songs.song_title
        ,songs.series_id
    FROM songs
    WHERE songs.song_title LIKE ?
    ORDER BY songs.song_id ASC;");

    $param = '%' . $_SESSION['search']['song_title'] . '%';
    $stmt->bind_param(
        's',
        $param,
    );
} else if ($_SESSION['search']['condition'] === 'song_singer_name') {
    // 歌手指定
    $stmt = $mysqli->prepare("SELECT songs.song_id
        ,songs.song_title
        ,songs.series_id
    FROM songs
    WHERE songs.singer_name LIKE ?
    ORDER BY songs.song_id ASC;");

    $param = '%' . $_SESSION['search']['song_singer_name'] . '%';
    $stmt->bind_param(
        's',
        $param,
    );
}

// ステートメントを実行
$stmt->execute();

// 結果をバインド
$stmt->bind_result($row['song_id'], $row['song_title'], $row['series_id']);
while ($stmt->fetch()) {
    echo '            <option value="' . $row['song_id']. '"' . ($row['song_id'] == $_SESSION['song']['id'] ? ' selected' : '') . '>' . mb_substr($row['series_id'], 0, 4) . ': ' . $row['song_title'] . '</option>' . "\n";
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
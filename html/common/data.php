<?php

// 設定読み込み
require_once __DIR__ . '/config.php';

class PrecureMusicData
{
    // メンバ
    private $mysqli;

    // コンストラクタ
    function __construct() {
        // データベース接続
        $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if ($this->mysqli->connect_errno) {
            http_response_code(500);
            exit();
        }
    }

    // デストラクタ
    function __destruct() {
        // データベース切断
        $this->mysqli->close();
    }

    // エスケープ
    function getEscapeString($str) {
        return $this->mysqli->real_escape_string($str);
    }

    /**
     * 歌曲データ取得
     */
    public function getSongData($song_id) {
        // 入力値のサニタイズ
        $song_id = $this->mysqli->real_escape_string($song_id);

        // クエリ
        $stmt = $this->mysqli->prepare("SELECT songs.song_id
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
            $result = $row;
        }

        // 取得できない場合はエラー
        $stmt->store_result();
        if ($stmt->num_rows === 0) {
            http_response_code(500);
            $this->mysqli->close();
            exit();
        }

        // ステートメントを閉じる
        $stmt->close();

        // 文字列置換
        $result['disc_title'] = str_replace('~', '～', $result['disc_title']);
        $result['disc_title'] = str_replace(' [CD+DVD盤]', '', $result['disc_title']);
        $result['song_title'] = str_replace('~', '～', $result['song_title']);
        $result['song_title'] = str_replace('!', '！', $result['song_title']);

        // 返却
        return $result;
    }

    /**
     * 劇伴データ取得
     */
    public function getBGMData($bgm_id) {
        // 入力値のサニタイズ
        $bgm_id = $this->mysqli->real_escape_string($bgm_id);

        // 入力値の分割
        $bgm_id = explode('_', $bgm_id);

        // クエリ
        $stmt = $this->mysqli->prepare("SELECT musics.disc_id,
                musics.track_no,
                musics.series_id,
                series.series_title,
                discs.disc_title,
                tracks.track_title,
                musics.m_no_detail,
                musics.menu,
                musics.composer_name,
                musics.arranger_name
            FROM musics
            INNER JOIN series
                ON musics.series_id = series.series_id
            INNER JOIN discs
                ON musics.disc_id = discs.disc_id
            INNER JOIN tracks
                ON musics.disc_id = tracks.disc_id AND musics.track_no = tracks.track_no
            WHERE musics.disc_id = ?
                AND musics.track_no = ?");

        $stmt->bind_param(
            'ss',
            $bgm_id[0],
            $bgm_id[1],
        );

        // ステートメントを実行
        $stmt->execute();

        // 結果をバインド TODO: フィールド
        $stmt->bind_result($row['disc_id'], $row['track_no'], $row['series_id'], $row['series_title'], $row['disc_title'], $row['track_title'], $row['m_no_detail'], $row['menu'], $row['composer_name'], $row['arranger_name']);
        while ($stmt->fetch()) {
            $result = $row;
        }

        // 取得できない場合はエラー
        $stmt->store_result();
        if ($stmt->num_rows === 0) {
            http_response_code(500);
            $this->mysqli->close();
            exit();
        }

        // ステートメントを閉じる
        $stmt->close();

        // 文字列置換
        $result['disc_title'] = str_replace('~', '～', $result['disc_title']);
        $result['disc_title'] = str_replace(' [CD+DVD盤]', '', $result['disc_title']);
        $result['track_title'] = str_replace('~', '～', $result['track_title']);
        $result['track_title'] = str_replace('!', '！', $result['track_title']);
        $result['m_no_detail'] = (!preg_match('/^_temp_\d{6}$/', $result['m_no_detail']) ? $result['m_no_detail'] : '');
        $result['menu'] = str_replace('~', '～', $result['menu']);

        // 返却
        return $result;
    }

    /**
     * 歌曲シリーズ一覧取得
     */
    public function getSongSeriesList() {
        // クエリ
        $stmt = $this->mysqli->prepare("SELECT series.series_id
                ,series.series_title
            FROM series
            INNER JOIN songs
                ON series.series_id = songs.series_id
            GROUP BY series.series_id
            ORDER BY series.series_id ASC;");

        // ステートメントを実行
        $stmt->execute();

        // 結果をバインド
        $stmt->bind_result($series_id, $series_title);
        while ($stmt->fetch()) {
            $result[$series_id] = $series_title;
        }

        // ステートメントを閉じる
        $stmt->close();

        // 返却
        return $result;
    }

    /**
     * 歌曲検索一覧取得
     */
    public function getSongSearchList($condt, $argmt) {
        // クエリ
        if ($condt === 'series_id' && !empty($argmt)) {
            // シリーズ指定
            $stmt = $this->mysqli->prepare("SELECT songs.song_id
                ,songs.song_title
                ,songs.series_id
            FROM songs
            WHERE songs.series_id = ?
            ORDER BY songs.song_id ASC;");

            $stmt->bind_param(
                's',
                $argmt,
            );
        } else if ($condt === 'title' && !empty($argmt)) {
            // 曲名指定
            $stmt = $this->mysqli->prepare("SELECT songs.song_id
                ,songs.song_title
                ,songs.series_id
            FROM songs
            WHERE songs.song_title LIKE ?
            ORDER BY songs.song_id ASC;");

            $param = '%' . $argmt . '%';
            $stmt->bind_param(
                's',
                $param,
            );
        } else if ($condt === 'singer_name' && !empty($argmt)) {
            // 歌手指定
            $stmt = $this->mysqli->prepare("SELECT songs.song_id
                ,songs.song_title
                ,songs.series_id
            FROM songs
            WHERE songs.singer_name LIKE ?
            ORDER BY songs.song_id ASC;");

            $param = '%' . $argmt . '%';
            $stmt->bind_param(
                's',
                $param,
            );
        } else {
            // 検索条件なし
            $stmt = $this->mysqli->prepare("SELECT songs.song_id
                ,songs.song_title
                ,songs.series_id
            FROM songs
            ORDER BY songs.song_id ASC;");
        }

        // ステートメントを実行
        $stmt->execute();

        // 結果をバインド
        $stmt->bind_result($song_id, $song_title, $series_id);
        while ($stmt->fetch()) {
            $result[$song_id] = mb_substr($series_id, 0, 4) . ': ' . $song_title;
        }

        // ステートメントを閉じる
        $stmt->close();

        // 返却
        return $result;
    }

    /**
     * 劇伴シリーズ一覧取得
     */
    public function getBGMSeriesList() {
        // クエリの実行
        $stmt = $this->mysqli->prepare("SELECT series.series_id
                ,series.series_title
            FROM series
            INNER JOIN musics
                ON series.series_id = musics.series_id
            WHERE musics.disc_id IS NOT NULL
            GROUP BY series.series_id
            ORDER BY series.series_id ASC;");

        // ステートメントを実行
        $stmt->execute();

        // 結果をバインド
        $stmt->bind_result($series_id, $series_title);
        while ($stmt->fetch()) {
            $result[$series_id] = $series_title;
        }

        // ステートメントを閉じる
        $stmt->close();

        // 返却
        return $result;
    }

    /**
     * 劇伴検索一覧取得
     */
    public function getBGMSearchList($condt, $argmt) {
        // クエリ
        if ($condt === 'series_id' && !empty($argmt)) {
            // シリーズ指定
            $stmt = $this->mysqli->prepare("SELECT musics.disc_id,
                musics.track_no,
                musics.series_id,
                musics.m_no_detail,
                tracks.track_title
            FROM musics
            INNER JOIN series
                ON musics.series_id = series.series_id
            INNER JOIN discs
                ON musics.disc_id = discs.disc_id
            INNER JOIN tracks
                ON musics.disc_id = tracks.disc_id AND musics.track_no = tracks.track_no
            WHERE musics.series_id = ?
            ORDER BY musics.series_id ASC, musics.rec_session ASC, musics.m_no_detail ASC, musics.disc_id ASC, musics.track_no ASC;");

            $stmt->bind_param(
                's',
                $argmt,
            );
        } else if ($condt === 'title' && !empty($argmt)) {
            // 曲名指定
            $stmt = $this->mysqli->prepare("SELECT musics.disc_id,
                musics.track_no,
                musics.series_id,
                musics.m_no_detail,
                tracks.track_title
            FROM musics
            INNER JOIN series
                ON musics.series_id = series.series_id
            INNER JOIN discs
                ON musics.disc_id = discs.disc_id
            INNER JOIN tracks
                ON musics.disc_id = tracks.disc_id AND musics.track_no = tracks.track_no
            WHERE tracks.track_title LIKE ?
            ORDER BY musics.series_id ASC, musics.rec_session ASC, musics.m_no_detail ASC, musics.disc_id ASC, musics.track_no ASC;");

            $param = '%' . $argmt . '%';
            $stmt->bind_param(
                's',
                $param,
            );
        } else if ($condt === 'mno' && !empty($argmt)) {
            // Mナンバー指定
            $stmt = $this->mysqli->prepare("SELECT musics.disc_id,
                musics.track_no,
                musics.series_id,
                musics.m_no_detail,
                tracks.track_title
            FROM musics
            INNER JOIN series
                ON musics.series_id = series.series_id
            INNER JOIN discs
                ON musics.disc_id = discs.disc_id
            INNER JOIN tracks
                ON musics.disc_id = tracks.disc_id AND musics.track_no = tracks.track_no
            WHERE musics.m_no_detail LIKE ?
                AND musics.m_no_detail NOT LIKE '_temp_%'
            ORDER BY musics.series_id ASC, musics.rec_session ASC, musics.m_no_detail ASC, musics.disc_id ASC, musics.track_no ASC;");

            $param = '%' . $argmt . '%';
            $stmt->bind_param(
                's',
                $param,
            );
        } else {
            // 検索条件なし
            $stmt = $this->mysqli->prepare("SELECT musics.disc_id,
                musics.track_no,
                musics.series_id,
                musics.m_no_detail,
                tracks.track_title
            FROM musics
            INNER JOIN series
                ON musics.series_id = series.series_id
            INNER JOIN discs
                ON musics.disc_id = discs.disc_id
            INNER JOIN tracks
                ON musics.disc_id = tracks.disc_id AND musics.track_no = tracks.track_no
            ORDER BY musics.series_id ASC, musics.rec_session ASC, musics.m_no_detail ASC, musics.disc_id ASC, musics.track_no ASC;");
        }

        // ステートメントを実行
        $stmt->execute();

        // 結果をバインド
        $stmt->bind_result($disc_id, $track_no, $series_id, $m_no_detail, $track_title);
        while ($stmt->fetch()) {
            $result[$disc_id . '_' . $track_no] = mb_substr($series_id, 0, 4) . ': ' . (!preg_match('/^_temp_\d{6}$/', $m_no_detail) ? $m_no_detail . ' ' : '') . $track_title;
        }

        // ステートメントを閉じる
        $stmt->close();

        // 返却
        return $result;
    }
}

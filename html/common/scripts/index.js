$(() => {
    // 歌曲シリーズリスト
    $('#song_series_id').change(() => {
        $('#song_id option').remove()
        $('#song_id').prop('disabled', true)
        $('#song_title').val('')
        $('#song_singer_name').val('')
        clear_bgm()
        getList({
            class: 'song',
            condt: 'series_id',
            argmt: $('#song_series_id').val(),
        })
    })

    // 歌曲曲名
    $('#song_title').change(() => {
        $('#song_id option').remove()
        $('#song_id').prop('disabled', true)
        $('#song_series_id').val('')
        $('#song_singer_name').val('')
        clear_bgm()
        getList({
            class: 'song',
            condt: 'title',
            argmt: $('#song_title').val(),
        })
    })

    // 歌曲歌手
    $('#song_singer_name').change(() => {
        $('#song_id option').remove()
        $('#song_id').prop('disabled', true)
        $('#song_series_id').val('')
        $('#song_title').val('')
        clear_bgm()
        getList({
            class: 'song',
            condt: 'singer_name',
            argmt: $('#song_singer_name').val(),
        })
    })

    // 歌曲選択
    $('#song_id').change(() => {
        clear_bgm()
        setSong({
            class: 'song',
            id: $('#song_id').val(),
        })
    })
    
    // 劇伴シリーズリスト
    $('#bgm_series_id').change(() => {
        $('#bgm_id option').remove()
        $('#bgm_id').prop('disabled', true)
        clear_song()
        $('#bgm_title').val('')
        $('#bgm_mno').val('')
        getList({
            class: 'bgm',
            condt: 'series_id',
            argmt: $('#bgm_series_id').val(),
        })
    })

    // 劇伴曲名
    $('#bgm_title').change(() => {
        $('#bgm_id option').remove()
        $('#bgm_id').prop('disabled', true)
        clear_song()
        $('#bgm_series_id').val('')
        $('#bgm_mno').val('')
        getList({
            class: 'bgm',
            condt: 'title',
            argmt: $('#bgm_title').val(),
        })
    })

    // 劇伴Mナンバー
    $('#bgm_mno').change(() => {
        $('#bgm_id option').remove()
        $('#bgm_id').prop('disabled', true)
        clear_song()
        $('#bgm_series_id').val('')
        $('#bgm_title').val('')
        getList({
            class: 'bgm',
            condt: 'mno',
            argmt: $('#bgm_mno').val(),
        })
    })

    // 劇伴選択
    $('#bgm_id').change(() => {
        clear_song()
        setSong({
            class: 'bgm',
            id: $('#bgm_id').val(),
        })
    })

    // 歌曲クリア
    clear_song = () => {
        $('#song_series_id').val('')
        $('#song_title').val('')
        $('#song_singer_name').val('')
        $('#song_id').val('')
        $('#song_id option').remove()
        $('#song_id').prop('disabled', true)
    }
    
    // 劇伴クリア
    clear_bgm = () => {
        $('#bgm_series_id').val('')
        $('#bgm_title').val('')
        $('#bgm_mno').val('')
        $('#bgm_id').val('')
        $('#bgm_id option').remove()
        $('#bgm_id').prop('disabled', true)
    }

    /**
     * 楽曲リスト取得
     */
    getList = (arg) => {
        $.ajax({
            url: './common/search.php',
            type: 'POST',
            data: arg,
        })
        .done((list) => {
            if (list != null && Object.keys(list).length > 0) {
                if (arg['class'] == 'song') {
                    // 歌曲
                    $('#song_id').append($('<option>').text('-- 歌曲選択 --').attr('value', ''))
                    $.each(list, (id, title) => {
                        $('#song_id').append($('<option>').text(title).attr('value', id))
                    })
                    $('#song_id').prop('disabled', false)
                } else if (arg['class'] == 'bgm') {
                    // 劇伴
                    $('#bgm_id').append($('<option>').text('-- 劇伴選択 --').attr('value', ''))
                    $.each(list, (id, title) => {
                        $('#bgm_id').append($('<option>').text(title).attr('value', id))
                    })
                    $('#bgm_id').prop('disabled', false)
                }
            }
        })
    }

    /**
     * 楽曲設定
     */
    setSong = (arg) => {
        $.ajax({
            url: './common/set_song.php',
            type: 'POST',
            data: arg,
        })
    }

    // DJ情報
    $('.dj_data').change(() => {
        $.ajax({
            url: './common/set_dj.php',
            type: 'POST',
            data: ({
                dj_current_name: $('#dj_current_name').val(),
                dj_next_name: $('#dj_next_name').val(),
                dj_next_time: $('#dj_next_time').val(),
                dj_corner: $('#dj_corner').val(),
            }),
        })
    })

    // 送出ボタン
    $('#on_air').click(() => {
        $.ajax({
            url: './common/on_air.php',
            type: 'POST',
        })
    })

    // クリアボタン
    $('#clear').click(() => {
        clear_song()
        clear_bgm()
        $.ajax({
            url: './common/clear.php',
            type: 'POST',
        })
        $.ajax({
            url: './common/on_air.php',
            type: 'POST',
        })
    })
})
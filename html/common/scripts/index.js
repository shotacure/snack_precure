$(() => {
    // 初期処理
    $.ajax({
        url: './common/load_session.php',
        type: 'GET',
        cache: false,
        dataType:'json',
    })
    .done((data) => {
        if (data['dj'] != null) {
            $('#dj_current_name').val(data['dj']['current']['name'])
            $('#dj_next_name').val(data['dj']['next']['name'])
            $('#dj_next_time').val(data['dj']['next']['time'])
            $('#dj_corner').val(data['dj']['corner']['id'])
        }
        if (data['music'] != null) {
            setNext(data)
        }
    })

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
        .done((data) => {
            setNext(data)
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
        .done((data) => {
            setNext(data)
        })
    })

    // クリアボタン
    $('#clear').click(() => {
        $.ajax({
            url: './common/clear.php',
            type: 'POST',
        })
        .done((data) => {
            setNext(data)
            clear_song()
            clear_bgm()
        })
    })

    /**
     * NEXT表示
     */
    setNext = (data) => {
        $('#next_series').html(data['music']['series'])
        $('#next_disc').html(data['music']['disc'])
        $('#next_title').html(data['music']['title'])
        $('#next_mno').html(data['music']['mno'])
        $('#next_artist').html(data['music']['artist'])
    }
})
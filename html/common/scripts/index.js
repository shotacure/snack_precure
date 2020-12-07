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
        getTweet({
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
            argmt: $('#bgm_title').val(),
        })
    })

    // 劇伴選択
    $('#bgm_id').change(() => {
        clear_song()
        getTweet({
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
        // TODO: 選択値クリア
    }
    
    // 劇伴クリア
    clear_bgm = () => {
        $('#bgm_series_id').val('')
        $('#bgm_title').val('')
        $('#bgm_mno').val('')
        $('#bgm_id').val('')
        $('#bgm_id option').remove()
        $('#bgm_id').prop('disabled', true)
        // TODO: 選択値クリア
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
     * 楽曲選択
     */


    /**
     * 楽曲送出
     */


    /**
     * クリア送出
     */

})
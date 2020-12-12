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

    // アイテム選択
    $('#item_id').change(() => {
        setItem({
            id: $('#item_id').val(),
        })
    })

    /**
     * 楽曲設定
     */
    setItem = (arg) => {
        $.ajax({
            url: './auction/set_item.php',
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
            $('#item_id').val('');
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
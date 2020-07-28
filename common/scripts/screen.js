// 初期実行
$(window).on('load', function() {
    setTimeout(getjson, 1000);
});

// ajax取得
function getjson() {
    $.ajax({
        url: '/update.php',
        type: 'get',
        cache: false,
        dataType:'json',
        })
        .done(function(data) {
            // フォントリロード処理
            reloadfont(data);
        })
        .fail(function(xhr) {
            // 失敗時はページをリロード
            location.reload(true);
        })
        .always(function(xhr, msg) {
            // 1秒後に再度実行
            setTimeout(getjson, 1000);
        });
}

// 更新があればリロード
function reloadfont(data) {
    if (data['song']['series']['year_updated'] == '1' ||
        data['song']['series']['name_updated'] == '1' ||
        data['song']['album_updated'] == '1' ||
        data['song']['title_updated'] == '1' ||
        data['song']['artist_updated'] == '1' ||
        data['dj']['current']['name_updated'] == '1' ||
        data['dj']['next']['name_updated'] == '1' ||
        data['dj']['next']['time_updated'] == '1') {
        location.reload(true);
    }
}

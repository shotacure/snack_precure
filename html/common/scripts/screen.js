// 初期実行
$(window).on('load', () => {
    setTimeout(getUpdate, 1000);
});

// ajax取得
getUpdate = () => {
    $.ajax({
        url: './common/update.php',
        type: 'get',
        cache: false,
        dataType:'json',
    })
    .done((d) => {
        // 更新があればリロード
        location.reload(true);
    })
    .always(() => {
        // 1秒後に再度実行
        setTimeout(getUpdate, 1000);
    });
}

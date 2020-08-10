$(function() {
    // 歌曲シリーズリスト
    $('#song_series_id').change(function() {
        $('#song_id').prop('disabled', true)
        $('#mode').val('search')
        $('#searchcondition').val('song_series_id')
        $('form').submit()
    });

    // 歌曲ディスクリスト
    $('#song_disc_id').change(function() {
        $('#song_id').prop('disabled', true)
        $('#mode').val('search')
        $('#searchcondition').val('song_disc_id')
        $('form').submit()
    });

    // 歌曲曲名
    $('#song_title').change(function() {
        $('#song_id').prop('disabled', true)
        $('#mode').val('search')
        $('#searchcondition').val('song_title')
        $('form').submit()
    });

    // 歌曲歌手
    $('#song_singer_name').change(function() {
        $('#song_id').prop('disabled', true)
        $('#mode').val('search')
        $('#searchcondition').val('song_singer_name')
        $('form').submit()
    });

    // 歌曲条件クリア
    $('#song_clear').click(function() {
        $('#song_id').prop('disabled', true)
        $('#mode').val('search')
        $('#searchcondition').val('')
        $('form').submit()
    });    

    // 歌曲選択
    $('#song_id').change(function() {
        $('#bgm_series_id').val('')
        $('#bgm_disc_id').val('')
        $('#bgm_title').val('')
        $('#bgm_id').val('')
        $('#musictype').val('Vocal')
    });
    
    // 劇伴シリーズリスト
    $('#bgm_series_id').change(function() {
        $('#bgm_id').prop('disabled', true)
        $('#mode').val('search')
        $('#searchcondition').val('bgm_series_id')
        $('form').submit()
    });

    // 劇伴ディスクリスト
    $('#bgm_disc_id').change(function() {
        $('#bgm_id').prop('disabled', true)
        $('#mode').val('search')
        $('#searchcondition').val('bgm_disc_id')
        $('form').submit()
    });

    // 劇伴曲名
    $('#bgm_title').change(function() {
        $('#bgm_id').prop('disabled', true)
        $('#mode').val('search')
        $('#searchcondition').val('bgm_title')
        $('form').submit()
    });

    // 劇伴条件クリア
    $('#bgm_clear').click(function() {
        $('#bgm_id').prop('disabled', true)
        $('#mode').val('search')
        $('#searchcondition').val('')
        $('form').submit()
    });    

    // 劇伴選択
    $('#bgm_id').change(function() {
        $('#song_series_id').val('')
        $('#song_disc_id').val('')
        $('#song_title').val('')
        $('#song_singer_name').val('')
        $('#song_id').val('')
        $('#musictype').val('BGM')
    });

    // 送出
    $('#send').click(function() {
        $('#mode').val('send')
        $('form').submit()
    });

    // 楽曲クリア
    $('#music_clear').click(function() {
        $('#mode').val('send')
        $('#musictype').val('')
        $('#searchcondition').val('')
        $('form').submit()
    });
});
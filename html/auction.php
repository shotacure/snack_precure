<?php

// 設定読み込み
require_once __DIR__ . '/common/config.php';

// データクラス読み込み
require_once __DIR__ . '/auction/data.php';

// インスタンス
$data = new AuctionData();

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>すなっくプリキュア タイトルジェネレータ</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="./auction/auction.js"></script>
    </head>
    <body>
        <form method="post">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title pointer"><i class="glyphicon glyphicon-shopping-cart"></i> アイテム</h4>
                    </div>
                    <div class="panel-body">
                        <div class="panel-collapse collapse in">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <select id="item_id" name="item_id" class="form-control" size="20">
                                            <?php foreach($data->getItemList() as $item_id => $item_title) : ?>
                                            <option value="<?= $item_id ?>"><?= $item_title ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title pointer"><i class="glyphicon glyphicon-user"></i> DJ</h4>
                    </div>
                    <div class="panel-body">
                        <div class="panel-collapse collapse in">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-md-2">現在DJ</label>
                                    <div class="col-md-3">
                                        <select id="dj_current_name" name="dj_current_name" class="form-control dj_data">
                                            <option value=""></option>
                                            <? foreach(DJ_LIST as $dj_current_name) : ?>
                                            <option value="<?= $dj_current_name ?>"><?= $dj_current_name ?></option>
                                            <? endforeach; ?>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-2">コーナー</label>
                                    <div class="col-md-4">
                                        <select id="dj_corner" name="dj_corner" class="form-control dj_data">
                                            <option value=""></option>
                                            <? foreach(CORNER_LIST as $dj_corner_id => $dj_corner_name) : ?>
                                            <option value="<?= $dj_corner_id ?>"><?= $dj_corner_name ?></option>
                                            <? endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">次DJ</label>
                                    <div class="col-md-3">
                                        <select id="dj_next_name" name="dj_next_name" class="form-control dj_data">
                                            <option value=""></option>
                                            <? foreach(DJ_LIST as $dj_next_name) : ?>
                                            <option value="<?= $dj_next_name ?>"><?= $dj_next_name ?></option>
                                            <? endforeach; ?>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-2">次DJ時間</label>
                                    <div class="col-md-3">
                                        <input type="text" id="dj_next_time" name="dj_next_time" placeholder="15:00" class="form-control dj_data" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title pointer"><i class="glyphicon glyphicon-facetime-video"></i> NEXT</h4>
                    </div>
                    <div class="panel-body">
                        <div>
                            <small><span id="next_series"></span>
                            <span id="next_disc"></span></small>
                        </div>
                        <div>
                            <span id="next_title"></span><span id="next_mno"></span>
                        </div>
                        <div>
                            <small><span id="next_artist"></span></small>
                        </div>
                    </div>
                </div>
                <button type="button" id="on_air" class="btn btn btn-danger btn-lg"><i class="glyphicon glyphicon-send"></i> 送出</button>
                <button type="button" id="clear" class="btn btn-warning btn-lg"><i class="glyphicon glyphicon-remove-sign"></i> 楽曲クリア</button>
            </div>
        </form>
    </body>
</html>
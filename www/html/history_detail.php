<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'history.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

$token = get_csrf_token();

// 該当の注文番号の購入明細を表形式で一覧表示する。
// 表示項目は「商品名」「購入時の商品価格」 「購入数」「小計」とする。
// 画面上部に該当の「注文番号」「購入日時」「合計金額」を表示する。
// ログインしているユーザー以外の注文については、管理者以外は閲覧できないものとする。

$history = get_history($db, $user['user_id']);

$item_id = get_post('item_id');
$history_id = get_post('history_id');

//history_detailテーブルからhistory_idで該当するitem_idを取得する
$detail = get_history_data($db, $item_id, $history_id);
$price = $detail[0]['price'];
$amount = $detail[0]['amount'];
$created = $detail[0]['created'];

$history_name = get_history_name($db, $item_id);
$name = $history_name[0]['name'];


include_once VIEW_PATH . 'history_detail_view.php';
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

$history_id = get_post('history_id');

//2つのテーブルからhistory_idで該当する情報を取得する
$detail = get_history_data($db, $history_id);
$created = $detail[0]['created'];

$total = sum_total($db,$history_id);

include_once VIEW_PATH . 'history_detail_view.php';
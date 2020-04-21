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

//同じユーザIDを持つ購入履歴を取得する
$history = history_data($db, $user['user_id']);

//管理者だった場合
if ($user['type'] === USER_TYPE_ADMIN){
    $history = get_all_history($db);
}


include_once VIEW_PATH . 'history_view.php';

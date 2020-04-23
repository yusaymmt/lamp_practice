<?php
require_once '../conf/const.php';
require_once '../model/functions.php';
require_once '../model/user.php';
require_once '../model/item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$token = get_csrf_token();

$db = get_db_connect();
$user = get_login_user($db);

// 並び替え
if(isset($_GET['sort'])) {
  $sort = $_GET['sort'];
}

//sortが１の時(新着順)
if($sort === '1') {
  $items = get_items_by_newest($db);
}
//sortが2の時 (価格が安い順)
else if($sort === '2') {
    $items = get_items_by_lowest($db);
}
//sortが3の時（価格が高い順）
else if($sort === '3') {
    $items = get_items_by_highest($db);
}

include_once VIEW_PATH . 'index_view.php';
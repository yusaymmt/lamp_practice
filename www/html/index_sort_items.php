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

if(isset($_GET['sort'])) {
  $sort = $_GET['sort'];
}

$items = get_items_by($db, $sort);

include_once VIEW_PATH . 'index_view.php';
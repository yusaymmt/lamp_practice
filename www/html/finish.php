<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

$carts = get_user_carts($db, $user['user_id']);

$token = get_post('token');
if (is_valid_csrf_token($token) === false) {
  set_error('不正な動作が確認されました');
} else {

  if(confirm_purchase($db, $carts, $user_id, $history_id, $item_id, $price, $amount) === false) {
    set_error('商品が購入できませんでした。');
    redirect_to(CART_URL);   
  }
}

include_once '../view/finish_view.php';
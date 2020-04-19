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

  if(resist_history ($db, $carts, $user_id, $item_id, $price, $amount) === false) {
    set_error('商品が購入できませんでした。');
    redirect_to(CART_URL);   
  }
  $total_price = sum_carts($carts);

  
  // //流れとしてやりたいこと
  // try {
  //   $db->beginTransaction();
    
  //   //在庫等を確認し、OKであれば商品をカートから削除
  //   purchase_carts($db, $carts);

  //   //user_idを取得
  //   $user_id = $carts[0]['user_id'];

  //   // historyテーブルにuser_idを保存し、history_idを生成・取得
  //   insert_history($db, $user_id);
  //   $history_id = $db->lastInsertId();
    
  //   //商品ごとに同じhistory_idで商品の詳細を保存
  //   foreach ($carts as $key => $rec) {
  //     $item_id = (int)$rec['item_id'];
  //     $price = (int)$rec['price'];
  //     $amount = (int)$rec['amount'];
      
  //     insert_history_detail($db, $history_id, $item_id, $price, $amount);
  //   }
  //   //この全てをコミットする
  //   $db->commit();
  // } catch (PDOException $e) {
  //   //失敗した場合
  //   $db->rollback();
  //   throw $e;
  // }

}

  $total_price = sum_carts($carts);

include_once '../view/finish_view.php';
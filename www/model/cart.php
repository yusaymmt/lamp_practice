<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_user_carts($db, $user_id){

  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = :user_id
  ";
  return fetch_all_query($db, $sql, array('user_id' => $user_id));
}

function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = :user_id
    AND
      items.item_id = :item_id
  ";

  return fetch_query($db, $sql, array('user_id' => $user_id, 'item_id' => $item_id));

}

function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(:item_id, :user_id, :amount)
  ";

  return execute_query($db, $sql, array('item_id' => $item_id, 'user_id' => $user_id, 'amount' => $amount));
}

function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = :amount
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";
  return execute_query($db, $sql,array('amount' => $amount,'cart_id' => $cart_id));
}

function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";

  return execute_query($db, $sql, array('cart_id' => $cart_id));
}

function purchase_carts($db, $carts){
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  foreach($carts as $cart){
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  
  delete_user_carts($db, $carts[0]['user_id']);

  return true;
}

function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = :user_id
  ";

  execute_query($db, $sql, array('user_id' => $user_id));
}


function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

function validate_cart_purchase($carts){
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}

//購入を確定すると同時に購入履歴を保存する
function confirm_purchase($db, $carts, $user_id) {
  try {
    $db->beginTransaction();
    //購入を進め、在庫を減らし、カート削除に成功すれば
    if(purchase_carts($db, $carts) === true){
      //購入履歴を保存する
      register_history($db, $carts, $user_id);
      //コミットする
      $db->commit();
      return true;
    }
  } catch (PDOException $e) { 
      throw $e;
      $db->rollback();
      return false;
  }
}
//購入履歴をDBに保存する
function register_history ($db, $carts, $user_id) {
  $history_id = insert_history($db, $user_id);
    foreach ($carts as $key => $rec) {
      $item_id = (int)$rec['item_id'];
      $price = (int)$rec['price'];
      $amount = (int)$rec['amount'];
      insert_history_detail($db, $history_id, $item_id, $price, $amount);
    }
    return false;
}


//hisotryテーブルに保存
function insert_history($db, $user_id) {
  $sql = "INSERT INTO history (user_id) VALUES (:user_id)";
  execute_query($db, $sql, array('user_id' => $user_id));
  return $db->lastInsertId();
}

//hisotry_detailテーブルに保存
function insert_history_detail($db, $history_id, $item_id, $price, $amount) {
  $sql = "INSERT INTO history_detail (history_id, item_id, price, amount)
          VALUES (:history_id, :item_id, :price, :amount)";
  execute_query($db, $sql, array('history_id' => $history_id, 'item_id' => $item_id, 'price' => $price, 'amount' => $amount));
}


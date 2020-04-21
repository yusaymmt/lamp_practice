<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//ログイン中のユーザの購入履歴を取得
function history_data($db, $user_id) {
    $sql = "SELECT history.history_id, history.created, SUM(history_detail.price * history_detail.amount) AS total
            FROM history
            JOIN history_detail
            ON history.history_id = history_detail.history_id
            WHERE user_id = :user_id
            GROUP BY history_id
            ORDER BY created desc
        ";

return fetch_all_query($db, $sql, array('user_id' => $user_id));
}

//adminの場合全ての購入履歴を取得

function get_all_history($db) {
    $sql = "SELECT history.history_id, history.created, SUM(history_detail.price * history_detail.amount) AS total
            FROM history
            JOIN history_detail
            ON history.history_id = history_detail.history_id
            GROUP BY history_id
            ORDER BY created desc
        ";
    return fetch_all_query($db, $sql);   

}

//２つのテーブルから必要情報を一気に取得する
function get_history_data($db, $history_id) {
    $sql = "SELECT history_detail.created,
                    history_detail.amount,
                    history_detail.price,
                    items.name
            FROM history_detail
            JOIN items
            ON history_detail.item_id = items.item_id
            WHERE history_id = :history_id
    ";
    return fetch_all_query($db, $sql, array( 'history_id' => $history_id)); 
}

//購入明細のhistory_idごとの合計金額計算
function sum_total($db, $history_id) {
  $sql = "SELECT SUM(price * amount) AS subtotal
            FROM history_detail
            WHERE history_id = :history_id
        ";

return fetch_query($db, $sql, array( 'history_id' => $history_id));   
}   

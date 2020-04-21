<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//ログイン中のユーザの購入履歴を取得
function get_history($db, $user_id) {
    $sql = "SELECT  history.history_id,
                    history.created,
                    history_detail.item_id,
                    history_detail.price,
                    history_detail.amount
            FROM history 
            JOIN history_detail
            ON history.history_id = history_detail.history_id 
            WHERE history.user_id = :user_id
            ORDER BY created desc
    ";

    return fetch_all_query($db, $sql, array('user_id' => $user_id));

}

function get_all_history($db) {
    $sql = "SELECT  history.history_id,
                    history.created,
                    history_detail.price,
                    history_detail.amount
            FROM history 
            JOIN history_detail
            ON history.history_id = history_detail.history_id
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

function sum_history($db, $history_id) {
  $sql = "SELECT SUM(price * amount) 
            FROM history_detail
            WHERE history_id = :history_id
            GROUP BY history_id
        ";

return fetch_query($db, $sql, array( 'history_id' => $history_id));   
}   


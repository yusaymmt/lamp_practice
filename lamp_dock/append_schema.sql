CREATE TABLE history(
history_id INT AUTO_INCREMENT,
user_id INT,
created DATETIME,
updated DATETIME,
primary key(history_id)
);

CREATE history_detail (
detail_id INT AUTO_INCREMENT,
hisotry_id INT,
item_id INT,
amount INT,
subtotal INT,
created DATETIME,
updated DATETIME,
primary key(detail_id)
);


-- finish.phpにて購入が完了した時トランザクション処理を入れる
-- １．historyテーブルでどのユーザがいつ、いくら購入したかを保存、オートインクリメントでhistory_idを注文番号として作成
-- ２．history_idを受け取って、history_detailテーブルでどの商品を何個購入したのかを保存
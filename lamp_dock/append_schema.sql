CREATE TABLE history(
history_id INT AUTO_INCREMENT,
user_id INT,
created DATETIME,
updated DATETIME,
primary key(history_id)
);

CREATE TABLE history_detail (
detail_id INT AUTO_INCREMENT,
history_id INT,
item_id INT,
price INT,
amount INT,
created DATETIME,
updated DATETIME,
primary key(detail_id)
);
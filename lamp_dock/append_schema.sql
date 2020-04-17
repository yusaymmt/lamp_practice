CREATE TABLE history(
history_id INT AUTO_INCREMENT,
user_id INT,
item_id INT,
amount INT,
total INT,
created DATETIME,
updated DATETIME,
primary key(history_id)
);
CREATE TABLE history(
history_id INT AUTO_INCREMENT,
user_id INT,
item_id INT,
amount INT,
total INT,
create_datetime DATETIME,
primary key(history_id)
);
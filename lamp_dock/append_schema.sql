<<<<<<< HEAD
CREATE TABLE history(
history_id INT AUTO_INCREMENT,
user_id INT,
item_id INT,
amount INT,
total INT,
created DATETIME,
updated DATETIME,
=======
CREATE TABLE ec_history(
history_id INT AUTO_INCREMENT,
user_id INT,
item_id INT
create_datetime DATETIME,
>>>>>>> develop
primary key(history_id)
);
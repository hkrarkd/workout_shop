CREATE TABLE ec_item_master (
  item_id INT(11) AUTO_INCREMENT,
  name VARCHAR(100),
  price INT(11),
  img VARCHAR(100),
  status INT(11) DEFAULT 0,
  create_datetime DATETIME,
  update_datetime DATETIME,
  PRIMARY KEY(item_id)
);
CREATE TABLE ec_item_stock (
  stock_id INT(11),
  item_id INT(11),
  stock INT(11),
  create_datetime DATETIME,
  update_datetime DATETIME,
  PRIMARY KEY(stock_id)
);
CREATE TABLE ec_cart (
  cart_id INT(11) AUTO_INCREMENT,
  item_id INT(11),
  amount INT(11),
  create_datetime DATETIME,
  update_datetime DATETIME,
  PRIMARY KEY(cart_id)
);
CREATE TABLE ec_user (
  user_id INT(11) AUTO_INCREMENT,
  user_name VARCHAR(100),
  password VARCHAR(100),
  create_datetime DATETIME,
  update_datetime DATETIME,
  PRIMARY KEY(user_id)
);
CREATE TABLE ec_history (
  history_id INT(11) AUTO_INCREMENT,
  user_id INT(11),
  item_id INT(11),
  create_datetime DATETIME,
  PRIMARY KEY(history_id)
);
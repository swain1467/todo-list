CREATE TABLE user_details(
id INT AUTO_INCREMENT PRIMARY KEY,
user_name VARCHAR(200) NOT NULL,
first_name VARCHAR(50),
last_name VARCHAR(30),
contact_no VARCHAR(15),
mail_address VARCHAR(200) NOT NULL,
enc_password VARCHAR(1000) NOT NULL,
created datetime NOT NULL,
updated datetime NOT NULL,
status TINYINT NOT NULL DEFAULT 1
);

CREATE TABLE task_master(
id INT AUTO_INCREMENT PRIMARY KEY,
header VARCHAR(100) NOT NULL,
content text NOT NULL,
created_on datetime,
created_by VARCHAR(200) NOT NULL,
updated_on datetime,
status TINYINT
);

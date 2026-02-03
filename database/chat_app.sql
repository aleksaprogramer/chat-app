DROP DATABASE chat_app;
CREATE DATABASE chat_app;
USE chat_app;

CREATE TABLE users (
	id INT NOT NULL AUTO_INCREMENT,
	username VARCHAR(255) NOT NULL,
	phone_number VARCHAR(10) NOT NULL,
	hashed_pwd VARCHAR(255) NOT NULL,
	is_admin BOOLEAN DEFAULT 0,
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	
	PRIMARY KEY (id),
	UNIQUE KEY (phone_number)
);

CREATE TABLE chats (
	id INT NOT NULL AUTO_INCREMENT,
	chat_name VARCHAR(255) NOT NULL,
	user_one_id INT NOT NULL,
	user_two_id INT NOT NULL,
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	
	PRIMARY KEY (id),
	UNIQUE KEY (chat_name),
	FOREIGN KEY (user_one_id) REFERENCES users(id),
	FOREIGN KEY (user_two_id) REFERENCES users(id)
);
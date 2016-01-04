DROP TABLE IF EXISTS tbl_roles;

CREATE TABLE tbl_roles (
id INT(1) NOT NULL AUTO_INCREMENT,
title varchar(20),
PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET utf8;

DROP TABLE IF EXISTS tbl_user;

CREATE TABLE tbl_user (
id INT(10) NOT NULL AUTO_INCREMENT,
username varchar(50) NOT NULL,
password varchar(255) NOT NULL,
name varchar(50) NOT NULL,
phone varchar(20) NOT NULL,
email varchar(50) NOT NULL,
role_id int(1) NOT NULL,
authKey varchar(50),
PRIMARY KEY (id),
FOREIGN KEY (role_id) REFERENCES tbl_roles(id)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET utf8;

INSERT INTO tbl_roles VALUES 
(1, 'Администратор'),
(2, 'Пользователь')
;

INSERT INTO tbl_user VALUES
(1, 'vasya', 'pass', 'Вася', '222-22-22', 'vasya@gmail.com', 1, ''),
(2, 'petya', 'parol', 'Петя', '333-33-33', 'petya@mail.ru', 2, '')
;

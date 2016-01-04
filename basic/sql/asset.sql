CREATE TABLE asset (
	id_asset INT(10) NOT NULL AUTO_INCREMENT,
	name VARCHAR(50),
	description VARCHAR(50),
	PRIMARY KEY (id_asset)
);

CREATE TABLE asset_user (
	id_asset_user INT(10) NOT NULL AUTO_INCREMENT,
	id_user INT(10),
	id_asset INT(10),
	quantity INT(10),
	PRIMARY KEY (id_asset_user),
	FOREIGN KEY (id_user) REFERENCES tbl_user (id),
	FOREIGN KEY (id_asset) REFERENCES asset (id_asset)
);
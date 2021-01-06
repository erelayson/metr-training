Uses table PROMO from exam 1.

 ```mysql
 CREATE TABLE PROMO (
	keyword VARCHAR(30) PRIMARY KEY,
	name VARCHAR(30),
	description TEXT,
	expiry DATETIME,
	renewal INT,
  status BOOLEAN DEFAULT 0,
  activated BOOLEAN DEFAULT 0
);```

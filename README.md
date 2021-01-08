# Promo and SKU Creator

 Code submission for the Phase 2 Exam.
 
 Database table structure code below:
 
 ```mysql
 CREATE TABLE PROMO (
	keyword VARCHAR(30) PRIMARY KEY,
	name VARCHAR(30),
	description TEXT,
	expiry INT,
	expiry_unit ENUM('hour','day','month'),
	renewal INT,
  	status BOOLEAN DEFAULT 0,
  	activated BOOLEAN DEFAULT 0
);

CREATE TABLE PROMO_SKU (
	promo VARCHAR(30),
	keyword VARCHAR(30) PRIMARY KEY,
	name VARCHAR(30),
	description TEXT,
	price FLOAT,
  	status BOOLEAN DEFAULT 0
);

CREATE TABLE PROMO_SERVICE_SKU (
	promo_sku VARCHAR(30),
	service_sku VARCHAR(30),
  	status BOOLEAN DEFAULT 0
);


CREATE TABLE SERVICE_SKU (
	keyword VARCHAR(30) PRIMARY KEY
);
```

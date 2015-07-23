ALTER TABLE products CHANGE product_price product_price DECIMAL(17, 2) NOT NULL DEFAULT '0.00';

CREATE TABLE IF NOT EXISTS product_images (
  image_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  product_id INT(11) UNSIGNED NOT NULL,
  image VARCHAR(64) DEFAULT NULL,
  thumbnail VARCHAR(64) DEFAULT NULL,
  PRIMARY KEY (image_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE carts ADD options VARCHAR(100) DEFAULT NULL;

ALTER TABLE orders ADD coupon_name VARCHAR(128) NOT NULL;
ALTER TABLE orders ADD coupon_discount DECIMAL(17, 2) NOT NULL DEFAULT '0.00';
ALTER TABLE orders ADD tax_shipping INT(1) NOT NULL DEFAULT '0';

ALTER TABLE order_products CHANGE product_price product_price DECIMAL(17, 2) NOT NULL DEFAULT '0.00';

CREATE TABLE IF NOT EXISTS order_options (
	order_option_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	order_id INT(11) UNSIGNED NOT NULL,
	order_product_id INT(11) UNSIGNED NOT NULL,
	option_value_id INT(11) UNSIGNED NOT NULL,
	option_name VARCHAR(255) NOT NULL,
	option_value VARCHAR(255) NOT NULL,
	option_price DECIMAL(17, 2) NOT NULL DEFAULT '0.00',
	option_type CHAR(1) NOT NULL,
	PRIMARY KEY (order_option_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS coupons (
	coupon_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	coupon_name VARCHAR(128) NOT NULL,
	coupon_code VARCHAR(10) NOT NULL,
	coupon_type VARCHAR(10) NOT NULL,
	coupon_discount DECIMAL(17, 2) NOT NULL DEFAULT '0.00',
	date_start DATE NOT NULL DEFAULT '0000-00-00',
	date_end DATE NOT NULL DEFAULT '0000-00-00',
	coupon_status TINYINT(1) UNSIGNED DEFAULT NULL,
	PRIMARY KEY (coupon_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO coupons VALUES (1, 'Coupon (-10%)', '123456', 'P', 10.00, '2012-01-01', '2012-12-31', 1);
INSERT INTO coupons VALUES (2, 'Coupon (-10.00)', '789012', 'F', 10.00, '2012-01-01', '2012-12-31', 1);

CREATE TABLE IF NOT EXISTS product_options (
	option_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	product_id INT(11) UNSIGNED NOT NULL,
	option_name VARCHAR(255) NOT NULL,
	position INT(11) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (option_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS product_option_values (
	option_value_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	option_id INT(11) UNSIGNED NOT NULL,
	product_id INT(11) UNSIGNED NOT NULL,
	option_value VARCHAR(255) NOT NULL,
	option_price DECIMAL(17, 2) NOT NULL DEFAULT '0.00',
	option_type CHAR(1) NOT NULL,
	option_quantity INT(4) UNSIGNED NOT NULL DEFAULT '0',
	position INT(11) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (option_value_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE user_meta RENAME user_profiles;

ALTER TABLE user_profiles CHANGE meta_id profile_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE user_profiles DROP display_name;

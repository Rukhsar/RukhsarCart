CREATE TABLE IF NOT EXISTS categories (
	category_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	parent_id INT(11) UNSIGNED NOT NULL,
	category_name VARCHAR(255) DEFAULT NULL,
	category_status TINYINT(1) UNSIGNED DEFAULT NULL,
	PRIMARY KEY (category_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO categories VALUES(1, 0, 'Computer accessories', 1);
INSERT INTO categories VALUES(2, 1, 'Web Cameras', 1);
INSERT INTO categories VALUES(3, 1, 'Printers', 1);
INSERT INTO categories VALUES(4, 1, 'Monitors', 1);
INSERT INTO categories VALUES(5, 1, 'Scanners', 1);
INSERT INTO categories VALUES(6, 0, 'Phones', 1);
INSERT INTO categories VALUES(7, 0, 'Desktops', 1);
INSERT INTO categories VALUES(8, 7, 'Mac', 1);
INSERT INTO categories VALUES(9, 0, 'Software', 1);
INSERT INTO categories VALUES(10, 0, 'eBooks', 1);
INSERT INTO categories VALUES(11, 0, 'Laptops', 1);
INSERT INTO categories VALUES(12, 0, 'Digital Cameras', 1);

CREATE TABLE IF NOT EXISTS category_products (
	product_id INT(11) UNSIGNED NOT NULL,
	category_id INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (product_id, category_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO category_products VALUES(1, 11);
INSERT INTO category_products VALUES(2, 6);
INSERT INTO category_products VALUES(3, 6);
INSERT INTO category_products VALUES(4, 8);
INSERT INTO category_products VALUES(5, 12);
INSERT INTO category_products VALUES(6, 4);
INSERT INTO category_products VALUES(7, 3);
INSERT INTO category_products VALUES(8, 5);
INSERT INTO category_products VALUES(9, 2);
INSERT INTO category_products VALUES(10, 10);
INSERT INTO category_products VALUES(11, 9);

CREATE TABLE IF NOT EXISTS products (
	product_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	product_name VARCHAR(50) DEFAULT NULL, 
	product_description text DEFAULT NULL, 
	product_image VARCHAR(64) DEFAULT NULL,
	product_thumbnail VARCHAR(64) DEFAULT NULL,
	product_price DECIMAL(17, 2) NOT NULL DEFAULT '0.00',
	product_quantity INT(4) UNSIGNED NOT NULL DEFAULT '0',
	shipping INT(1) NOT NULL DEFAULT '1',
	PRIMARY KEY (product_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO products VALUES(1, 'Laptop', '<p>Laptop description</p>', 'laptop.png', 'laptop_thumbnail.png', 1700.00, 100, 1);
INSERT INTO products VALUES(2, 'HTC Aria', '<p>HTC Aria description</p>', 'htc_aria.png', 'htc_aria_thumbnail.png', 350.00, 100, 1);
INSERT INTO products VALUES(3, 'iPhone 3Gs', '<p>iPhone 3Gs description</p>', 'iphone.png', 'iphone_thumbnail.png', 539.00, 100, 1);
INSERT INTO products VALUES(4, 'iMac', '<p>iMac description</p>', 'imac.png', 'imac_thumbnail.png', 1199.00, 100, 1);
INSERT INTO products VALUES(5, 'Nikon', '<p>Nikon description</p>', 'nikon.png', 'nikon_thumbnail.png', 200.00, 100, 1);
INSERT INTO products VALUES(6, 'Monitor', '<p>Monitor description</p>', 'monitor.png', 'monitor_thumbnail.png', 225.00, 100, 1);
INSERT INTO products VALUES(7, 'Printer', '<p>Printer description</p>', 'printer.png', 'printer_thumbnail.png', 45.00, 100, 1);
INSERT INTO products VALUES(8, 'Scanner', '<p>Scanner description</p>', 'scanner.png', 'scanner_thumbnail.png', 30.00, 100, 1);
INSERT INTO products VALUES(9, 'Webcam', '<p>Webcam description</p>', 'webcam.png', 'webcam_thumbnail.png', 12.50, 100, 1);
INSERT INTO products VALUES(10, 'Sample PDF eBook', '<p>Sample PDF eBook description</p>', 'ebook.png', 'ebook.png', 9.50, 100, 0);
INSERT INTO products VALUES(11, 'Sample PHP script', '<p>Sample PHP script description</p>', 'php_script.png', 'php_script.png', 5.50, 100, 0);

CREATE TABLE IF NOT EXISTS product_images (
  image_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  product_id INT(11) UNSIGNED NOT NULL,
  image VARCHAR(64) DEFAULT NULL,
  thumbnail VARCHAR(64) DEFAULT NULL,
  PRIMARY KEY (image_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS digital_goods (
	digital_good_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	product_id INT(11) UNSIGNED NOT NULL,
	display_filename VARCHAR(128) NOT NULL,
	filename VARCHAR(128) NOT NULL ,
	date_added INT(11) UNSIGNED NOT NULL,
	number_days INT(11) UNSIGNED NOT NULL,
	number_downloadable INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (digital_good_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO digital_goods VALUES(1, 10, 'ebook.pdf', 'ebook.pdf', '', '', 10);
INSERT INTO digital_goods VALUES(2, 11, 'php_script.zip', 'php_script.zip', '', 10, '');

CREATE TABLE IF NOT EXISTS carts (
	cart_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	cart_session VARCHAR(50) DEFAULT NULL, 
	product_id INT(11) UNSIGNED NOT NULL,
	product_quantity INT(4) UNSIGNED NOT NULL DEFAULT '0',
	options VARCHAR(100) DEFAULT NULL,
	cart_created INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (cart_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
	user_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	group_id INT(11) UNSIGNED NOT NULL,
	user_email VARCHAR(100) NOT NULL,
	user_password VARCHAR(40) NOT NULL,
	user_status TINYINT(1) UNSIGNED DEFAULT NULL,
	user_approved TINYINT(1) UNSIGNED DEFAULT NULL,
	user_created INT(11) UNSIGNED NOT NULL,
	last_login INT(11) UNSIGNED NOT NULL,
	last_ip VARCHAR(40) NOT NULL,
	remember_code VARCHAR(40) DEFAULT NULL,
	activation_code VARCHAR(40) DEFAULT NULL,
	PRIMARY KEY (user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS user_profiles (
	profile_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	user_id INT(11) UNSIGNED NOT NULL,
	first_name VARCHAR(50) DEFAULT NULL,  
	last_name VARCHAR(50) DEFAULT NULL,
	PRIMARY KEY (profile_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS user_groups (
	group_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	group_name VARCHAR(20) NOT NULL,
	group_description VARCHAR(100) NOT NULL,
	PRIMARY KEY (group_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO user_groups (group_id, group_name, group_description) VALUES
(1, 'admin', 'Administrator'),
(2, 'customer', 'Customer');

CREATE TABLE IF NOT EXISTS customers (
	customer_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	user_id INT(11) UNSIGNED NOT NULL,
	first_name VARCHAR(32) NOT NULL,
	last_name VARCHAR(32) NOT NULL,
	customer_email VARCHAR(100) NOT NULL,
	customer_telephone VARCHAR(32) NOT NULL,
	PRIMARY KEY (customer_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS addresses (
	address_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	user_id INT(11) UNSIGNED NOT NULL,
	country_id INT(11) UNSIGNED NOT NULL,
	company VARCHAR(32) NOT NULL,
	first_name VARCHAR(32) NOT NULL,
	last_name VARCHAR(32) NOT NULL,
	address VARCHAR(128) NOT NULL,
	post_code VARCHAR(10) NOT NULL,
	city VARCHAR(128) NOT NULL,
	zone VARCHAR(128) NOT NULL,
	PRIMARY KEY (address_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS orders  (
	order_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	user_id INT(11) UNSIGNED NOT NULL,
	first_name VARCHAR(32) NOT NULL,
	last_name VARCHAR(32) NOT NULL,
	customer_email VARCHAR(100) NOT NULL,
	customer_telephone VARCHAR(32) NOT NULL,
	shipping_company VARCHAR(32) NOT NULL,
	shipping_first_name VARCHAR(32) NOT NULL,
	shipping_last_name VARCHAR(32) NOT NULL,
	shipping_address VARCHAR(128) NOT NULL,
	shipping_post_code VARCHAR(10) NOT NULL,
	shipping_city VARCHAR(128) NOT NULL,
	shipping_country_id INT(11) UNSIGNED NOT NULL,
	shipping_zone VARCHAR(128) NOT NULL,
	shipping_method VARCHAR(128) NOT NULL,
	payment_company VARCHAR(32) NOT NULL,
	payment_first_name VARCHAR(32) NOT NULL,
	payment_last_name VARCHAR(32) NOT NULL,
	payment_address VARCHAR(128) NOT NULL,
	payment_post_code VARCHAR(10) NOT NULL,
	payment_city VARCHAR(128) NOT NULL,
	payment_country_id INT(11) UNSIGNED NOT NULL,
	payment_zone VARCHAR(128) NOT NULL,
	payment_method VARCHAR(128) NOT NULL,
	comment text NOT NULL,
	total DECIMAL(17, 2) NOT NULL DEFAULT '0.00',
	shipping_cost DECIMAL(17, 2) NOT NULL DEFAULT '0.00',
	tax_description VARCHAR(255) NOT NULL,
	tax_rate DECIMAL(17, 2) NOT NULL DEFAULT '0.00',
	tax_shipping INT(1) NOT NULL DEFAULT '0',
	currency VARCHAR(20) NOT NULL,
	coupon_name VARCHAR(128) NOT NULL,
	coupon_discount DECIMAL(17, 2) NOT NULL DEFAULT '0.00',
	order_status_description_id INT(11) UNSIGNED NOT NULL,
	date_modified INT(11) UNSIGNED NOT NULL,
	date_added INT(11) UNSIGNED NOT NULL,
	ip VARCHAR(40) NOT NULL,
	PRIMARY KEY (order_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS order_products (
	order_product_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	order_id INT(11) UNSIGNED NOT NULL,
	product_id INT(11) UNSIGNED NOT NULL,
	product_name VARCHAR(50) DEFAULT NULL,
	product_price DECIMAL(17, 2) NOT NULL DEFAULT '0.00',
	total DECIMAL(17, 2) NOT NULL DEFAULT '0.00',
	tax DECIMAL(17, 2) NOT NULL DEFAULT '0.00',
	product_quantity INT(4) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (order_product_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS order_digital_goods (
	order_digital_good_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	order_id INT(11) UNSIGNED NOT NULL,
	product_id INT(11) UNSIGNED NOT NULL,
	display_filename VARCHAR(128) NOT NULL,
	filename VARCHAR(128) NOT NULL,
	date_added INT(11) UNSIGNED NOT NULL,
	date_expiration INT(11) UNSIGNED NOT NULL,
	number_days INT(11) UNSIGNED NOT NULL,
	number_downloadable INT(11) UNSIGNED NOT NULL,
	downloads INT(11) UNSIGNED NOT NULL,
	download_hash VARCHAR(32) NOT NULL,
	PRIMARY KEY (order_digital_good_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE IF NOT EXISTS order_status (
	order_status_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	order_id INT(11) UNSIGNED NOT NULL,
	order_status_description_id INT(11) UNSIGNED NOT NULL,
	date_added INT(11) UNSIGNED NOT NULL,	
	PRIMARY KEY (order_status_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS order_status_descriptions (
	order_status_description_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	status_name VARCHAR(40) NOT NULL,
	invoice TINYINT(1) UNSIGNED DEFAULT NULL,
	PRIMARY KEY (order_status_description_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO order_status_descriptions VALUES (1, 'Pending', 0);
INSERT INTO order_status_descriptions VALUES (2, 'Completed', 1);
INSERT INTO order_status_descriptions VALUES (3, 'Processing', 1);
INSERT INTO order_status_descriptions VALUES (4, 'Shipped', 1);
INSERT INTO order_status_descriptions VALUES (5, 'Canceled', 0);
INSERT INTO order_status_descriptions VALUES (6, 'Denied', 0);
INSERT INTO order_status_descriptions VALUES (7, 'Failed', 0);
INSERT INTO order_status_descriptions VALUES (8, 'Refunded', 0);

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

CREATE TABLE IF NOT EXISTS countries (
	country_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	country_name VARCHAR(128) NOT NULL,
	iso_code_2 VARCHAR(2) NOT NULL,
	iso_code_3 VARCHAR(3) NOT NULL,
	country_status TINYINT(1) UNSIGNED DEFAULT NULL,
	PRIMARY KEY (country_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO countries VALUES
(1, 'Afghanistan', 'AF', 'AFG', 1),
(2, 'Albania', 'AL', 'ALB', 1),
(3, 'Algeria', 'DZ', 'DZA', 1),
(4, 'American Samoa', 'AS', 'ASM', 1),
(5, 'Andorra', 'AD', 'AND', 1),
(6, 'Angola', 'AO', 'AGO', 1),
(7, 'Anguilla', 'AI', 'AIA', 1),
(8, 'Antarctica', 'AQ', 'ATA', 1),
(9, 'Antigua and Barbuda', 'AG', 'ATG', 1),
(10, 'Argentina', 'AR', 'ARG', 1),
(11, 'Armenia', 'AM', 'ARM', 1),
(12, 'Aruba', 'AW', 'ABW', 1),
(13, 'Australia', 'AU', 'AUS', 1),
(14, 'Austria', 'AT', 'AUT', 1),
(15, 'Azerbaijan', 'AZ', 'AZE', 1),
(16, 'Bahamas', 'BS', 'BHS', 1),
(17, 'Bahrain', 'BH', 'BHR', 1),
(18, 'Bangladesh', 'BD', 'BGD', 1),
(19, 'Barbados', 'BB', 'BRB', 1),
(20, 'Belarus', 'BY', 'BLR', 1),
(21, 'Belgium', 'BE', 'BEL', 1),
(22, 'Belize', 'BZ', 'BLZ', 1),
(23, 'Benin', 'BJ', 'BEN', 1),
(24, 'Bermuda', 'BM', 'BMU', 1),
(25, 'Bhutan', 'BT', 'BTN', 1),
(26, 'Bolivia', 'BO', 'BOL', 1),
(27, 'Bosnia and Herzegowina', 'BA', 'BIH', 1),
(28, 'Botswana', 'BW', 'BWA', 1),
(29, 'Bouvet Island', 'BV', 'BVT', 1),
(30, 'Brazil', 'BR', 'BRA', 1),
(31, 'British Indian Ocean Territory', 'IO', 'IOT', 1),
(32, 'Brunei Darussalam', 'BN', 'BRN', 1),
(33, 'Bulgaria', 'BG', 'BGR', 1),
(34, 'Burkina Faso', 'BF', 'BFA', 1),
(35, 'Burundi', 'BI', 'BDI', 1),
(36, 'Cambodia', 'KH', 'KHM', 1),
(37, 'Cameroon', 'CM', 'CMR', 1),
(38, 'Canada', 'CA', 'CAN', 1),
(39, 'Cape Verde', 'CV', 'CPV', 1),
(40, 'Cayman Islands', 'KY', 'CYM', 1),
(41, 'Central African Republic', 'CF', 'CAF', 1),
(42, 'Chad', 'TD', 'TCD', 1),
(43, 'Chile', 'CL', 'CHL', 1),
(44, 'China', 'CN', 'CHN', 1),
(45, 'Christmas Island', 'CX', 'CXR', 1),
(46, 'Cocos (Keeling) Islands', 'CC', 'CCK', 1),
(47, 'Colombia', 'CO', 'COL', 1),
(48, 'Comoros', 'KM', 'COM', 1),
(49, 'Congo', 'CG', 'COG', 1),
(50, 'Cook Islands', 'CK', 'COK', 1),
(51, 'Costa Rica', 'CR', 'CRI', 1),
(52, 'Cote D\'Ivoire', 'CI', 'CIV', 1),
(53, 'Croatia', 'HR', 'HRV', 1),
(54, 'Cuba', 'CU', 'CUB', 1),
(55, 'Cyprus', 'CY', 'CYP', 1),
(56, 'Czech Republic', 'CZ', 'CZE', 1),
(57, 'Denmark', 'DK', 'DNK', 1),
(58, 'Djibouti', 'DJ', 'DJI', 1),
(59, 'Dominica', 'DM', 'DMA', 1),
(60, 'Dominican Republic', 'DO', 'DOM', 1),
(61, 'East Timor', 'TP', 'TMP', 1),
(62, 'Ecuador', 'EC', 'ECU', 1),
(63, 'Egypt', 'EG', 'EGY', 1),
(64, 'El Salvador', 'SV', 'SLV', 1),
(65, 'Equatorial Guinea', 'GQ', 'GNQ', 1),
(66, 'Eritrea', 'ER', 'ERI', 1),
(67, 'Estonia', 'EE', 'EST', 1),
(68, 'Ethiopia', 'ET', 'ETH', 1),
(69, 'Falkland Islands (Malvinas)', 'FK', 'FLK', 1),
(70, 'Faroe Islands', 'FO', 'FRO', 1),
(71, 'Fiji', 'FJ', 'FJI', 1),
(72, 'Finland', 'FI', 'FIN', 1),
(73, 'France', 'FR', 'FRA', 1),
(74, 'France, Metropolitan', 'FX', 'FXX', 1),
(75, 'French Guiana', 'GF', 'GUF', 1),
(76, 'French Polynesia', 'PF', 'PYF', 1),
(77, 'French Southern Territories', 'TF', 'ATF', 1),
(78, 'Gabon', 'GA', 'GAB', 1),
(79, 'Gambia', 'GM', 'GMB', 1),
(80, 'Georgia', 'GE', 'GEO', 1),
(81, 'Germany', 'DE', 'DEU', 1),
(82, 'Ghana', 'GH', 'GHA', 1),
(83, 'Gibraltar', 'GI', 'GIB', 1),
(84, 'Greece', 'GR', 'GRC', 1),
(85, 'Greenland', 'GL', 'GRL', 1),
(86, 'Grenada', 'GD', 'GRD', 1),
(87, 'Guadeloupe', 'GP', 'GLP', 1),
(88, 'Guam', 'GU', 'GUM', 1),
(89, 'Guatemala', 'GT', 'GTM', 1),
(90, 'Guinea', 'GN', 'GIN', 1),
(91, 'Guinea-bissau', 'GW', 'GNB', 1),
(92, 'Guyana', 'GY', 'GUY', 1),
(93, 'Haiti', 'HT', 'HTI', 1),
(94, 'Heard and Mc Donald Islands', 'HM', 'HMD', 1),
(95, 'Honduras', 'HN', 'HND', 1),
(96, 'Hong Kong', 'HK', 'HKG', 1),
(97, 'Hungary', 'HU', 'HUN', 1),
(98, 'Iceland', 'IS', 'ISL', 1),
(99, 'India', 'IN', 'IND', 1),
(100, 'Indonesia', 'ID', 'IDN', 1),
(101, 'Iran (Islamic Republic of)', 'IR', 'IRN', 1),
(102, 'Iraq', 'IQ', 'IRQ', 1),
(103, 'Ireland', 'IE', 'IRL', 1),
(104, 'Israel', 'IL', 'ISR', 1),
(105, 'Italy', 'IT', 'ITA', 1),
(106, 'Jamaica', 'JM', 'JAM', 1),
(107, 'Japan', 'JP', 'JPN', 1),
(108, 'Jordan', 'JO', 'JOR', 1),
(109, 'Kazakhstan', 'KZ', 'KAZ', 1),
(110, 'Kenya', 'KE', 'KEN', 1),
(111, 'Kiribati', 'KI', 'KIR', 1),
(112, 'North Korea', 'KP', 'PRK', 1),
(113, 'Korea, Republic of', 'KR', 'KOR', 1),
(114, 'Kuwait', 'KW', 'KWT', 1),
(115, 'Kyrgyzstan', 'KG', 'KGZ', 1),
(116, 'Laos', 'LA', 'LAO', 1),
(117, 'Latvia', 'LV', 'LVA', 1),
(118, 'Lebanon', 'LB', 'LBN', 1),
(119, 'Lesotho', 'LS', 'LSO', 1),
(120, 'Liberia', 'LR', 'LBR', 1),
(121, 'Libyan Arab Jamahiriya', 'LY', 'LBY', 1),
(122, 'Liechtenstein', 'LI', 'LIE', 1),
(123, 'Lithuania', 'LT', 'LTU', 1),
(124, 'Luxembourg', 'LU', 'LUX', 1),
(125, 'Macau', 'MO', 'MAC', 1),
(126, 'Macedonia', 'MK', 'MKD', 1),
(127, 'Madagascar', 'MG', 'MDG', 1),
(128, 'Malawi', 'MW', 'MWI', 1),
(129, 'Malaysia', 'MY', 'MYS', 1),
(130, 'Maldives', 'MV', 'MDV', 1),
(131, 'Mali', 'ML', 'MLI', 1),
(132, 'Malta', 'MT', 'MLT', 1),
(133, 'Marshall Islands', 'MH', 'MHL', 1),
(134, 'Martinique', 'MQ', 'MTQ', 1),
(135, 'Mauritania', 'MR', 'MRT', 1),
(136, 'Mauritius', 'MU', 'MUS', 1),
(137, 'Mayotte', 'YT', 'MYT', 1),
(138, 'Mexico', 'MX', 'MEX', 1),
(139, 'Micronesia, Federated States of', 'FM', 'FSM', 1),
(140, 'Moldova, Republic of', 'MD', 'MDA', 1),
(141, 'Monaco', 'MC', 'MCO', 1),
(142, 'Mongolia', 'MN', 'MNG', 1),
(143, 'Montserrat', 'MS', 'MSR', 1),
(144, 'Morocco', 'MA', 'MAR', 1),
(145, 'Mozambique', 'MZ', 'MOZ', 1),
(146, 'Myanmar', 'MM', 'MMR', 1),
(147, 'Namibia', 'NA', 'NAM', 1),
(148, 'Nauru', 'NR', 'NRU', 1),
(149, 'Nepal', 'NP', 'NPL', 1),
(150, 'Netherlands', 'NL', 'NLD', 1),
(151, 'Netherlands Antilles', 'AN', 'ANT', 1),
(152, 'New Caledonia', 'NC', 'NCL', 1),
(153, 'New Zealand', 'NZ', 'NZL', 1),
(154, 'Nicaragua', 'NI', 'NIC', 1),
(155, 'Niger', 'NE', 'NER', 1),
(156, 'Nigeria', 'NG', 'NGA', 1),
(157, 'Niue', 'NU', 'NIU', 1),
(158, 'Norfolk Island', 'NF', 'NFK', 1),
(159, 'Northern Mariana Islands', 'MP', 'MNP', 1),
(160, 'Norway', 'NO', 'NOR', 1),
(161, 'Oman', 'OM', 'OMN', 1),
(162, 'Pakistan', 'PK', 'PAK', 1),
(163, 'Palau', 'PW', 'PLW', 1),
(164, 'Panama', 'PA', 'PAN', 1),
(165, 'Papua New Guinea', 'PG', 'PNG', 1),
(166, 'Paraguay', 'PY', 'PRY', 1),
(167, 'Peru', 'PE', 'PER', 1),
(168, 'Philippines', 'PH', 'PHL', 1),
(169, 'Pitcairn', 'PN', 'PCN', 1),
(170, 'Poland', 'PL', 'POL', 1),
(171, 'Portugal', 'PT', 'PRT', 1),
(172, 'Puerto Rico', 'PR', 'PRI', 1),
(173, 'Qatar', 'QA', 'QAT', 1),
(174, 'Reunion', 'RE', 'REU', 1),
(175, 'Romania', 'RO', 'ROM', 1),
(176, 'Russian Federation', 'RU', 'RUS', 1),
(177, 'Rwanda', 'RW', 'RWA', 1),
(178, 'Saint Kitts and Nevis', 'KN', 'KNA', 1),
(179, 'Saint Lucia', 'LC', 'LCA', 1),
(180, 'Saint Vincent and the Grenadines', 'VC', 'VCT', 1),
(181, 'Samoa', 'WS', 'WSM', 1),
(182, 'San Marino', 'SM', 'SMR', 1),
(183, 'Sao Tome and Principe', 'ST', 'STP', 1),
(184, 'Saudi Arabia', 'SA', 'SAU', 1),
(185, 'Senegal', 'SN', 'SEN', 1),
(186, 'Seychelles', 'SC', 'SYC', 1),
(187, 'Sierra Leone', 'SL', 'SLE', 1),
(188, 'Singapore', 'SG', 'SGP', 1),
(189, 'Slovakia (Slovak Republic)', 'SK', 'SVK', 1),
(190, 'Slovenia', 'SI', 'SVN', 1),
(191, 'Solomon Islands', 'SB', 'SLB', 1),
(192, 'Somalia', 'SO', 'SOM', 1),
(193, 'South Africa', 'ZA', 'ZAF', 1),
(194, 'South Georgia and South Sandwich Islands', 'GS', 'SGS', 1),
(195, 'Spain', 'ES', 'ESP', 1),
(196, 'Sri Lanka', 'LK', 'LKA', 1),
(197, 'St. Helena', 'SH', 'SHN', 1),
(198, 'St. Pierre and Miquelon', 'PM', 'SPM', 1),
(199, 'Sudan', 'SD', 'SDN', 1),
(200, 'Suriname', 'SR', 'SUR', 1),
(201, 'Svalbard and Jan Mayen Islands', 'SJ', 'SJM', 1),
(202, 'Swaziland', 'SZ', 'SWZ', 1),
(203, 'Sweden', 'SE', 'SWE', 1),
(204, 'Switzerland', 'CH', 'CHE', 1),
(205, 'Syrian Arab Republic', 'SY', 'SYR', 1),
(206, 'Taiwan', 'TW', 'TWN', 1),
(207, 'Tajikistan', 'TJ', 'TJK', 1),
(208, 'Tanzania, United Republic of', 'TZ', 'TZA', 1),
(209, 'Thailand', 'TH', 'THA', 1),
(210, 'Togo', 'TG', 'TGO', 1),
(211, 'Tokelau', 'TK', 'TKL', 1),
(212, 'Tonga', 'TO', 'TON', 1),
(213, 'Trinidad and Tobago', 'TT', 'TTO', 1),
(214, 'Tunisia', 'TN', 'TUN', 1),
(215, 'Turkey', 'TR', 'TUR', 1),
(216, 'Turkmenistan', 'TM', 'TKM', 1),
(217, 'Turks and Caicos Islands', 'TC', 'TCA', 1),
(218, 'Tuvalu', 'TV', 'TUV', 1),
(219, 'Uganda', 'UG', 'UGA', 1),
(220, 'Ukraine', 'UA', 'UKR', 1),
(221, 'United Arab Emirates', 'AE', 'ARE', 1),
(222, 'United Kingdom', 'GB', 'GBR', 1),
(223, 'United States', 'US', 'USA', 1),
(224, 'United States Minor Outlying Islands', 'UM', 'UMI', 1),
(225, 'Uruguay', 'UY', 'URY', 1),
(226, 'Uzbekistan', 'UZ', 'UZB', 1),
(227, 'Vanuatu', 'VU', 'VUT', 1),
(228, 'Vatican City State (Holy See)', 'VA', 'VAT', 1),
(229, 'Venezuela', 'VE', 'VEN', 1),
(230, 'Viet Nam', 'VN', 'VNM', 1),
(231, 'Virgin Islands (British)', 'VG', 'VGB', 1),
(232, 'Virgin Islands (U.S.)', 'VI', 'VIR', 1),
(233, 'Wallis and Futuna Islands', 'WF', 'WLF', 1),
(234, 'Western Sahara', 'EH', 'ESH', 1),
(235, 'Yemen', 'YE', 'YEM', 1),
(236, 'Yugoslavia', 'YU', 'YUG', 1),
(237, 'Zaire', 'ZR', 'ZAR', 1),
(238, 'Zambia', 'ZM', 'ZMB', 1),
(239, 'Zimbabwe', 'ZW', 'ZWE', 1);

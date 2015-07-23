<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

$config['table_categories'] = 'categories';
$config['table_category_products'] = 'category_products';
$config['table_products'] = 'products';
$config['table_product_images'] = 'product_images';
$config['table_digital_goods'] = 'digital_goods';
$config['table_carts'] = 'carts';
$config['table_cart_product_options'] = 'cart_product_options';
$config['table_countries'] = 'countries';
$config['table_customers'] = 'customers';
$config['table_addresses'] = 'addresses';
$config['table_orders'] = 'orders';
$config['table_order_status'] = 'order_status';
$config['table_order_status_descriptions'] = 'order_status_descriptions';
$config['table_order_products'] = 'order_products';
$config['table_order_digital_goods'] = 'order_digital_goods';
$config['table_order_options'] = 'order_options';
$config['table_coupons'] = 'coupons';
$config['table_product_options'] = 'product_options';
$config['table_product_option_values'] = 'product_option_values';

$config['site_title'] = '__SITE_TITLE__';

$config['site_url'] = '__HTTP_SERVER__';

$config['absolute_path'] = '__DIR_APPLICATION__';

$config['admin_email'] = '__ADMIN_EMAIL__';

$config['email_subject'] = 'Order received';

$config['per_page_catalog'] = 8;

$config['per_page_admin'] = 6;

$config['currency_symbol'] = '__CURRENCY_SYMBOL__';

$config['currency_code'] = '__CURRENCY_CODE__';

$config['currency_position'] = 'left';

$config['shipping_cost'] = __SHIPPING_COST__;

$config['tax_description'] = '__TAX_DESCRIPTION__';

$config['tax_rate'] = __TAX_RATE__;

$config['tax_shipping'] = false;

$config['cart_expire'] = 60 * 60 * 24;

$config['paypal_email'] = '__PAYPAL_EMAIL__';

$config['paypal_return'] = '__HTTP_SERVER__checkout_success.php';

$config['paypal_cancel_return'] = '__HTTP_SERVER__';

$config['paypal_notify_url'] = '__HTTP_SERVER__payments/paypal/ipn.php';

$config['paypal_sandbox'] = 0;

$config['log_path'] = '__DIR_APPLICATION__logs/';

$config['new_order_notification'] = 1;


?>

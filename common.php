<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

//Initialize core objects
$db = new Database();
$authentication = new Authentication();
$cart = new Cart();
$error = new Error();
$session = new Session();
$validate = new Validate();
$upload = new Upload();
$tpl = new Template(config_item('template', 'absolute_path'));

//Template values
$tpl->set('db', $db);
$tpl->set('authentication', $authentication);
$tpl->set('cart', $cart);
$tpl->set('error', $error);
$tpl->set('session', $session);
	
//Add new product
if (isset($_POST['add_product'])) {

	if (!empty($_POST['option']))
		$options = $_POST['option'];
	else
		$options = NULL;
						
	$cart->add_product($_POST['product_id'], $options);
	
}
//Remove product from cart
if (isset($_GET['cart_id']) && !$_POST)
	$cart->remove_item($_GET['cart_id'], $_GET['remove_item']);

//Empty cart
if (isset($_POST['empty_cart']))
	$cart->empty_cart();

//Check coupon
if (isset($_POST['coupon']))
	$cart->check_coupon($_POST['coupon_code']);

//Checkout
if (isset($_POST['checkout']))
	header("Location: checkout.php");

//Update cart
if (isset($_POST['update_cart'])) {

	for ($i = 0; $i < count($_POST['quantity']); $i++)
		$validate->numeric($_POST['quantity'][$i], 'Please enter a valid number for the quantity of ' . $_POST['product_name'][$i] . ' !');

	if (!$error->has_errors())
		$cart->update_cart($_POST['cart_id'], $_POST['product_id'], $_POST['quantity']);

}
			
//Logout
if (isset($_GET['logout']) && !$_POST) {

	$authentication->logout();

	header("Location: login.php");

}

/**
 * Load a config file
 */
function config_load($name) {
		
	$configuration = array();

	if (!file_exists(dirname(__FILE__) . '/config/' . $name . '.php'))
		die('The file ' . dirname(__FILE__) . '/config/' . $name . '.php does not exist.');

	require(dirname(__FILE__) . '/config/' . $name . '.php');
		
	if (!isset($config) OR !is_array($config))
		die('The file ' . dirname(__FILE__) . '/config/' . $name . '.php file does not appear to be formatted correctly.');
			
	if (isset($config) AND is_array($config))
		$configuration = array_merge($configuration, $config);
	
	return $configuration;

}

/**
 * Load a config item 
 */
function config_item($name, $item) {
	
	static $config_item = array();

	if (!isset($config_item[$item])) {
	
		$config = config_load($name);

		if (!isset($config[$item]))
			return FALSE;
	
		$config_item[$item] = $config[$item];
		
	}
	
	return $config_item[$item];

}

/**
 * Autoloading classes
 */
function __autoload($class_name) {

	require_once('libraries/' . $class_name . '.php');

}

/**
 * Get categories
 */				
function get_categories($category_id) {
	
	global $cart;
	
	$categories = $cart->get_categories($category_id);

	$output = '';
	
	if ($categories)
		$output  .= '<ul>';
		
	foreach ($categories as $row) {

		$sub_menu = '';
		
		$children = get_categories($row['category_id']);
			
		if (!empty($children))
			$sub_menu = '&bull;';
			
		$output .= '<li>';
		
		$output .= '<a href="category.php?category_id=' . $row['category_id'] . '"><span>' . $row['category_name'] . ' ' . $sub_menu . ' </span></a>';

		$output .= $children;
		
		$output .= '</li>';
		
	}
	
	if ($categories)
		$output .= '</ul>';
	
	return $output;
	
}

/**
 * Displays price with currency
 */
function price($price, $currency = NULL) {
	
	$currency_symbol = !is_null($currency) ? $currency : config_item('cart', 'currency_symbol');
	
	switch (config_item('cart', 'currency_position')) {
	
		case 'left':
			$price = $currency_symbol . ' ' . number_format($price, 2, '.', ',');
		break;
	
		case 'right':
			$price = number_format($price, 2, '.', ',') . ' ' . $currency_symbol;
		break;
		
	}
	
	return $price;
	
}
 
/**
 * Save the cart settings
 */
function save_config($config) {
	
	define('DIR_APPLICATION', str_replace('\'', '/', realpath(dirname(__FILE__))) . '/');
	
	$content = "";
	$content .= "<?php if (__FILE__ == \$_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');\n\n";

	$content .= "\$config['table_users'] = 'users';\n";
	$content .= "\$config['table_groups'] = 'user_groups';\n";
	$content .= "\$config['table_profiles'] = 'user_profiles';\n\n";
		
	$content .= "\$config['site_title'] = '" . filter_var($config['site_title'], FILTER_SANITIZE_STRING) . "';\n\n";

	$content .= "\$config['site_url'] = '" . filter_var($config['site_url'], FILTER_SANITIZE_STRING) . "';\n\n";

	$content .= "\$config['absolute_path'] = '" . DIR_APPLICATION . "';\n\n";
	
	$content .= "\$config['admin_email'] = '" . $config['admin_email'] . "';\n\n";

	$content .= "\$config['default_group'] = 2;\n\n";

	$content .= "\$config['admin_group'] =  1;\n\n";

	if ($config['type_registration'] == 0) {
		
		$email_activation = "false";
		$approve_registration = "false";
	
	} else if ($config['type_registration'] == 1) {
		
		$email_activation = "true";
		$approve_registration = "false";
		
	} else if ($config['type_registration'] == 2) {
	
		$email_activation = "false";
		$approve_registration = "true";
	
	}
	
	$content .= "\$config['email_activation'] = " . $email_activation . ";\n\n";

	$content .= "\$config['approve_registration'] = " . $approve_registration . ";\n\n";

	$content .= "\$config['email_activation_expire'] = 60 * 60 * 24;\n\n";

	$content .= "\$config['email_subject_1'] = 'Thank you for registering';\n\n";

	$content .= "\$config['email_subject_2'] = 'New password';\n\n";

	$content .= "\$config['email_subject_3'] = 'A new customer has registered';\n\n";

	$content .= "\$config['user_expire'] = 3600 * 24 * 30;\n\n";

	$content .= "\$config['secret_word'] = '" . config_item('authentication', 'secret_word') . "';\n\n";

	$content .= "?>";

	file_put_contents("../config/authentication.php", $content, LOCK_EX);
	
	$content = "";
	$content .= "<?php if (__FILE__ == \$_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');\n\n";

	$content .= "\$config['table_categories'] = 'categories';\n";
	$content .= "\$config['table_category_products'] = 'category_products';\n";
	$content .= "\$config['table_products'] = 'products';\n";
	$content .= "\$config['table_product_images'] = 'product_images';\n";
	$content .= "\$config['table_digital_goods'] = 'digital_goods';\n";
	$content .= "\$config['table_carts'] = 'carts';\n";
	$content .= "\$config['table_cart_product_options'] = 'cart_product_options';\n";
	$content .= "\$config['table_countries'] = 'countries';\n";
	$content .= "\$config['table_customers'] = 'customers';\n";
	$content .= "\$config['table_addresses'] = 'addresses';\n";
	$content .= "\$config['table_orders'] = 'orders';\n";
	$content .= "\$config['table_order_status'] = 'order_status';\n";
	$content .= "\$config['table_order_status_descriptions'] = 'order_status_descriptions';\n";
	$content .= "\$config['table_order_products'] = 'order_products';\n";
	$content .= "\$config['table_order_digital_goods'] = 'order_digital_goods';\n";
	$content .= "\$config['table_order_options'] = 'order_options';\n";
	$content .= "\$config['table_coupons'] = 'coupons';\n";
	$content .= "\$config['table_product_options'] = 'product_options';\n";
	$content .= "\$config['table_product_option_values'] = 'product_option_values';\n\n";

	$content .= "\$config['site_title'] = '" . filter_var($config['site_title'], FILTER_SANITIZE_STRING) . "';\n\n";

	$content .= "\$config['site_url'] = '" . filter_var($config['site_url'], FILTER_SANITIZE_STRING) . "';\n\n";

	$content .= "\$config['absolute_path'] = '" .DIR_APPLICATION . "';\n\n";
	
	$content .= "\$config['admin_email'] = '" . $config['admin_email'] . "';\n\n";

	$content .= "\$config['email_subject'] = 'Order received';\n\n";

	$content .= "\$config['per_page_catalog'] = " . $config['per_page_catalog'] . ";\n\n";

	$content .= "\$config['per_page_admin'] = " . $config['per_page_admin'] . ";\n\n";
	
	$currency = explode('|', $config['currency']);
	
	$content .= "\$config['currency_symbol'] = '" . $currency[0] . "';\n\n";
	
	$content .= "\$config['currency_code'] = '" . $currency[1] . "';\n\n";
	
	$content .= "\$config['currency_position'] = '" . $config['currency_position'] . "';\n\n";
	
	if (empty($config['shipping_cost']))
		$shipping_cost = 0;
	else
		$shipping_cost = $config['shipping_cost'];
		
	$content .= "\$config['shipping_cost'] = " . $shipping_cost . ";\n\n";

	$content .= "\$config['tax_description'] = '" . filter_var($config['tax_description'], FILTER_SANITIZE_STRING) . "';\n\n";

	if (empty($config['tax_rate']))
		$tax_rate = 0;
	else
		$tax_rate = $config['tax_rate'];
		
	$content .= "\$config['tax_rate'] = " . $tax_rate . ";\n\n";
	
	if (isset($config['tax_shipping']))
		$tax_shipping = 'true';
	else
		$tax_shipping = 'false';
		
	$content .= "\$config['tax_shipping'] = " . $tax_shipping . ";\n\n";

	$content .= "\$config['cart_expire'] = 60 * 60 * 24;\n\n";

	$content .= "\$config['paypal_email'] = '" . $config['paypal_email'] . "';\n\n";

	$content .= "\$config['paypal_return'] = '" . filter_var($config['site_url'], FILTER_SANITIZE_STRING) . "checkout_success.php';\n\n";

	$content .= "\$config['paypal_cancel_return'] = '" . filter_var($config['paypal_cancel_return'], FILTER_SANITIZE_STRING) . "';\n\n";

	$content .= "\$config['paypal_notify_url'] = '" . filter_var($config['site_url'], FILTER_SANITIZE_STRING) . "payments/paypal/ipn.php';\n\n";

	$content .= "\$config['paypal_sandbox'] = " . $config['paypal_sandbox'] . ";\n\n";

	$content .= "\$config['log_path'] = '" . DIR_APPLICATION . "logs/';\n\n";
			
	$content .= "\$config['new_order_notification'] = " . $config['new_order_notification'] . ";\n\n";

	$content .= "?>";

	file_put_contents("../config/cart.php", $content, LOCK_EX);

	$content = "";
	$content .= "<?php if (__FILE__ == \$_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');\n\n";

	$content .= "\$config['upload_path'] = '" . DIR_APPLICATION . "uploads/';\n\n";

	$allowed_filetypes = '';
	
	foreach (config_item('upload', 'allowed_filetypes') as $value) {
			
			if (end(config_item('upload', 'allowed_filetypes')) === $value)
				$allowed_filetypes .= "'" . $value . "'";
			else
				$allowed_filetypes .= "'" . $value . "', ";
				
	}
			
	$content .= "\$config['allowed_filetypes'] = array(" . $allowed_filetypes . ");\n\n";

	$content .= "\$config['max_filesize'] = " . $config['max_filesize'] . ";\n\n";

	$content .= "\$config['max_width_thumbnail'] = " . $config['max_width_thumbnail'] . ";\n\n";

	$content .= "\$config['max_height_thumbnail'] = " . $config['max_height_thumbnail'] . ";\n\n";

	$content .= "\$config['max_width'] = " . $config['max_width'] . ";\n\n";

	$content .= "\$config['max_height'] = " . $config['max_height'] . ";\n\n";

	if (isset($config['crop_thumbnail']))
		$crop_thumbnail = 'true';
	else
		$crop_thumbnail = 'false';
			
	$content .= "\$config['crop_thumbnail'] = " . $crop_thumbnail . ";\n\n";
		
	$content .= "?>";
	
	file_put_contents("../config/upload.php", $content, LOCK_EX);

	$content = "";
	$content .= "<?php if (__FILE__ == \$_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');\n\n";

	$content .= "\$config['site_url'] = '" . filter_var($config['site_url'], FILTER_SANITIZE_STRING) . "';\n\n";
	
	$content .= "\$config['absolute_path'] = '" . DIR_APPLICATION . "templates/';\n\n";

	$content .= "\$config['template_extension'] = '.tpl';\n\n";
	
	$content .= "?>";
	
	file_put_contents("../config/template.php", $content, LOCK_EX);

}

?>

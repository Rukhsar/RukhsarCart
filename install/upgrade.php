<?php

define('DIR_APPLICATION', realpath(dirname(__FILE__) . '/../') . '/');
define('HTTP_SERVER', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(rtrim(dirname($_SERVER['PHP_SELF']), 'install'), '/.\\'). '/');

$writeable_directories = array(
	DIR_APPLICATION . 'config',
	DIR_APPLICATION . 'logs',
	DIR_APPLICATION . 'uploads/files',
	DIR_APPLICATION . 'uploads/images'
);

$writeable_files = array(
	DIR_APPLICATION . 'config/authentication.php',
	DIR_APPLICATION . 'config/cart.php',
	DIR_APPLICATION . 'config/database.php',
	DIR_APPLICATION . 'config/upload.php',
	DIR_APPLICATION . 'config/template.php'
);

//Load a config file
function config_load($name) {
		
	$configuration = array();

	if (!file_exists(realpath(dirname(__FILE__) . '/../') . '/config/' . $name . '.php'))
		die('The file ' . realpath(dirname(__FILE__) . '/../') . '/config/' . $name . '.php does not exist.');

	require(realpath(dirname(__FILE__) . '/../') . '/config/' . $name . '.php');
		
	if (!isset($config) OR !is_array($config))
		die('The file ' . realpath(dirname(__FILE__) . '/../') . '/config/' . $name . '.php file does not appear to be formatted correctly.');
			
	if (isset($config) AND is_array($config))
		$configuration = array_merge($configuration, $config);
	
	return $configuration;

}

//Load a config item 
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

//Check settings server
function check_settings() {
	
	global $writeable_directories, $writeable_files;
	
	$error = array();
	
	if (phpversion() < '5.2')
		$error['warning'] = true;
		
	if (!ini_get('file_uploads'))
		$error['warning'] = true;
		
	if (!extension_loaded('session'))
		$error['warning'] = true;
	
	if (!extension_loaded('PDO'))
		$error['warning'] = true;
		
	if (!extension_loaded('gd'))
		$error['warning'] = true;

	if (ini_get('register_globals'))
		$error['warning'] = true;
		
	foreach ($writeable_directories as $value) {
		
		if (!is_writable($value))
			$error['warning'] = true;
		
	}

	foreach ($writeable_files as $value) {
		
		if (!is_writable($value))
			$error['warning'] = true;
		
	}
	
	if (!$error)
		return true;
	else
		return false;
    		
}

//Check if the form has been submitted
if (isset($_POST['submit'])) {

	if (isset($_POST['disclaimer'])) {
			
		if (@mysql_connect(config_item('database', 'hostname'), config_item('database', 'username'), config_item('database', 'password')) && @mysql_select_db(config_item('database', 'dbname'))) {

			$connection = mysql_connect(config_item('database', 'hostname'), config_item('database', 'username'), config_item('database', 'password'));

			mysql_select_db(config_item('database', 'dbname'), $connection);

			$sql = explode(';', file_get_contents(DIR_APPLICATION . 'install/upgrade.sql'));

			foreach($sql as $query)
				mysql_query($query);
			
			mysql_close($connection);

			$content = "";
			$content .= "<?php if (__FILE__ == \$_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');\n\n";

			$content .= "\$config['table_users'] = 'users';\n";
			$content .= "\$config['table_groups'] = 'user_groups';\n";
			$content .= "\$config['table_profiles'] = 'user_profiles';\n\n";
				
			$content .= "\$config['site_title'] = '" . config_item('authentication', 'site_title') . "';\n\n";

			$content .= "\$config['site_url'] = '" . config_item('authentication', 'site_url') . "';\n\n";
			
			$content .= "\$config['absolute_path'] = '" . DIR_APPLICATION . "';\n\n";

			$content .= "\$config['admin_email'] = '" . config_item('authentication', 'admin_email') . "';\n\n";

			$content .= "\$config['default_group'] = " . config_item('authentication', 'default_group') . ";\n\n";

			$content .= "\$config['admin_group'] =  " . config_item('authentication', 'admin_group') . ";\n\n";
			
			$email_activation = config_item('authentication', 'email_activation') ? 'true' : 'false';
			
			$content .= "\$config['email_activation'] = " . $email_activation . ";\n\n";
			
			$approve_registration = config_item('authentication', 'approve_user_registration') ? 'true' : 'false';
			
			$content .= "\$config['approve_registration'] = " . $approve_registration . ";\n\n";

			$content .= "\$config['email_activation_expire'] = 60 * 60 * 24;\n\n";

			$content .= "\$config['email_subject_1'] = '" . config_item('authentication', 'email_subject_1') . "';\n\n";

			$content .= "\$config['email_subject_2'] = '" . config_item('authentication', 'email_subject_2') . "';\n\n";

			$content .= "\$config['email_subject_3'] = '" . config_item('authentication', 'email_subject_3') . "';\n\n";

			$content .= "\$config['user_expire'] = 3600 * 24 * 30;\n\n";

			if (config_item('authentication', 'secret_word'))
				$secret_word = config_item('authentication', 'secret_word');
			else
				$secret_word = substr(md5(rand() . rand()), 0, 8);
				
			$content .= "\$config['secret_word'] = '" . $secret_word . "';\n\n";

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

			$content .= "\$config['site_title'] = '" . config_item('cart', 'site_title') . "';\n\n";

			$content .= "\$config['site_url'] = '" . config_item('cart', 'site_url') . "';\n\n";
			
			$content .= "\$config['absolute_path'] = '" . DIR_APPLICATION . "';\n\n";
			
			$content .= "\$config['admin_email'] = '" . config_item('cart', 'admin_email') . "';\n\n";

			$content .= "\$config['email_subject'] = 'Order received';\n\n";

			$content .= "\$config['per_page_catalog'] = " . config_item('cart', 'per_page_catalog') . ";\n\n";

			$content .= "\$config['per_page_admin'] = " . config_item('cart', 'per_page_admin') . ";\n\n";
			
			$content .= "\$config['currency_symbol'] = '" . config_item('cart', 'currency_symbol') . "';\n\n";
			
			$content .= "\$config['currency_code'] = '" . config_item('cart', 'currency_code') . "';\n\n";
			
			$content .= "\$config['currency_position'] = 'left';\n\n";
			
			$content .= "\$config['shipping_cost'] = " . config_item('cart', 'shipping_cost') . ";\n\n";

			$content .= "\$config['tax_description'] = '" . config_item('cart', 'tax_description') . "';\n\n";

			$content .= "\$config['tax_rate'] = " . config_item('cart', 'tax_rate') . ";\n\n";
			
			$content .= "\$config['tax_shipping'] = false;\n\n";

			$content .= "\$config['cart_expire'] = 60 * 60 * 24;\n\n";

			$content .= "\$config['paypal_email'] = '" . config_item('cart', 'paypal_email') . "';\n\n";

			$content .= "\$config['paypal_return'] = '" . config_item('cart', 'paypal_return') . "';\n\n";

			$content .= "\$config['paypal_cancel_return'] = '" . config_item('cart', 'paypal_cancel_return') . "';\n\n";

			$content .= "\$config['paypal_notify_url'] = '" . config_item('cart', 'paypal_notify_url') . "';\n\n";

			$content .= "\$config['paypal_sandbox'] = " . config_item('cart', 'paypal_sandbox') . ";\n\n";

			$content .= "\$config['log_path'] = '" . config_item('cart', 'log_path') . "';\n\n";
			
			$content .= "\$config['new_order_notification'] = 1;\n\n";

			$content .= "?>";
			
			file_put_contents("../config/cart.php", $content, LOCK_EX);

			$content = "";
			$content .= "<?php if (__FILE__ == \$_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');\n\n";

			$content .= "\$config['hostname'] = '" . config_item('database', 'hostname') . "';\n";
			
			$content .= "\$config['username'] = '" . config_item('database', 'username') . "';\n";
			
			$content .= "\$config['password'] = '" . config_item('database', 'password') . "';\n";
			
			$content .= "\$config['dbname'] = '" . config_item('database', 'dbname') . "';\n";
			
			$content .= "\$config['driver'] = '" . config_item('database', 'driver') . "';\n";
			
			$content .= "\$config['char_set'] = '" . config_item('database', 'char_set') . "';\n\n";
			
			$content .= "?>";
			
			file_put_contents("../config/database.php", $content, LOCK_EX);
						
			$content = "";
			$content .= "<?php if (__FILE__ == \$_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');\n\n";

			$content .= "\$config['upload_path'] = '" . config_item('upload', 'upload_path') . "';\n\n";

			$allowed_filetypes = '';
			
			foreach (config_item('upload', 'allowed_filetypes') as $value) {
					
					if (end(config_item('upload', 'allowed_filetypes')) === $value)
						$allowed_filetypes .= "'" . $value . "'";
					else
						$allowed_filetypes .= "'" . $value . "', ";
						
			}
			
			$content .= "\$config['allowed_filetypes'] = array(" . $allowed_filetypes . ");\n\n";

			$content .= "\$config['max_filesize'] = " . config_item('upload', 'max_filesize') . ";\n\n";

			$content .= "\$config['max_width_thumbnail'] = " . config_item('upload', 'max_width_thumbnail') . ";\n\n";

			$content .= "\$config['max_height_thumbnail'] = " . config_item('upload', 'max_height_thumbnail') . ";\n\n";

			$content .= "\$config['max_width'] = " . config_item('upload', 'max_width') . ";\n\n";

			$content .= "\$config['max_height'] = " . config_item('upload', 'max_height') . ";\n\n";
			
			$content .= "\$config['crop_thumbnail'] = true;\n\n";
			
			$content .= "?>";
			
			file_put_contents("../config/upload.php", $content, LOCK_EX);

			$content = "";
			$content .= "<?php if (__FILE__ == \$_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');\n\n";

			$content .= "\$config['site_url'] = '" . HTTP_SERVER . "';\n\n";
			
			$content .= "\$config['absolute_path'] = '" . DIR_APPLICATION . "templates/';\n\n";

			$content .= "\$config['template_extension'] = '.tpl';\n\n";
			
			$content .= "?>";
			
			file_put_contents("../config/template.php", $content, LOCK_EX);
	
			$success = true;
			
		} else {
			
			$failed = true;
			
		}
		
	}

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
<!-- BEGIN head -->	
<head>
	
	<!-- Meta Tags -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	
	<!-- Title -->
	<title>Installation</title>

	<!-- Style sheet -->
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="css/reset.css" rel="stylesheet" type="text/css" />
	<link href="css/text.css" rel="stylesheet" type="text/css" />
	<link href="css/960.css" rel="stylesheet" type="text/css" />
	<link href="css/table.css" rel="stylesheet" type="text/css" />

	<!-- jQuery -->
	<script type="text/javascript" src="js/jquery.min.js"></script>

	<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function() {

		//Alternate row colors
		$(".table tr:nth-child(odd)").addClass("odd");
		$(".table tr:nth-child(even)").addClass("even");
    						
	});
	// ]]>
	</script>
			
</head>
<!-- END head -->

<!-- BEGIN body -->
<body>

<!-- BEGIN #wrapper -->
<div id="wrapper">

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
		
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">

			<div class="title_background">
				<h1>Upgrade</h1>
			</div>

			<?php if (isset($failed)): ?>
				<div class="alert error">
					<span>
						<strong>An error occurred while processing request</strong>
					</span>	
					<p>Problem connecting to the database: <?php echo mysql_error(); ?></p>
				</div>
			<?php endif; ?>
			
			<?php if (!isset($_POST['disclaimer']) && !isset($success)): ?>
			
				<div class="alert error">
					<p>
						WARNING!
						<br />
						A manual backup is HIGHLY recommended before continuing, please backup your existing application 
						files and database.
					</p>
				</div>
								
				<table class="table">
					<thead>
						<tr>
							<th>&nbsp;</th>
							<th>Expected value</th>
							<th>Server value</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><strong>Required settings</strong></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>PHP version</td>
							<td>5.2 or higher</td>
							<td><?php echo phpversion(); ?></td>
						</tr>
						<tr>
							<td>File uploads</td>
							<td>On</td>
							<td>
								<?php if (ini_get('file_uploads')): ?> 
									<img src="images/accept.png" alt="" />
								<?php else: ?>
									<img src="images/cross.png" alt="" />
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td>Session</td>
							<td>On</td>
							<td>
								<?php if (extension_loaded('session')): ?> 
									<img src="images/accept.png" alt="" />
								<?php else: ?>
									<img src="images/cross.png" alt="" />
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td>Register globals</td>
							<td>Off</td>
							<td>
								<?php if (!ini_get('register_globals')): ?>
									<img src="images/accept.png" alt="" />
								<?php else: ?>
									<img src="images/cross.png" alt="" />
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td><strong>PHP Extensions</strong></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>PDO</td>
							<td>Loaded</td>
							<td>
								<?php if (extension_loaded('PDO')): ?> 
									<img src="images/accept.png" alt="" />
								<?php else: ?>
									<img src="images/cross.png" alt="" />
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td>GD library</td>
							<td>Loaded</td>
							<td>
								<?php if (extension_loaded('gd')): ?> 
									<img src="images/accept.png" alt="" />
								<?php else: ?>
									<img src="images/cross.png" alt="" />
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td><strong>Folder permissions</strong></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td><?php echo DIR_APPLICATION . 'config'; ?></td>
							<td>Writable</td>
							<td>
								<?php if (is_writable(DIR_APPLICATION . 'config')): ?>
									<img src="images/accept.png" alt="" />
								<?php else: ?>
									<img src="images/cross.png" alt="" />
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td><?php echo DIR_APPLICATION . 'logs'; ?></td>
							<td>Writable</td>
							<td>
								<?php if (is_writable(DIR_APPLICATION . 'logs')): ?>
									<img src="images/accept.png" alt="" />
								<?php else: ?>
									<img src="images/cross.png" alt="" />
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td><?php echo DIR_APPLICATION . 'uploads/files'; ?></td>
							<td>Writable</td>
							<td>
								<?php if (is_writable(DIR_APPLICATION . 'uploads/files')): ?>
									<img src="images/accept.png" alt="" />
								<?php else: ?>
									<img src="images/cross.png" alt="" />
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td><?php echo DIR_APPLICATION . 'uploads/images'; ?></td>
							<td>Writable</td>
							<td>
								<?php if (is_writable(DIR_APPLICATION . 'uploads/images')): ?>
									<img src="images/accept.png" alt="" />
								<?php else: ?>
									<img src="images/cross.png" alt="" />
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td><strong>File permissions</strong></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td><?php echo DIR_APPLICATION . 'config/authentication.php'; ?></td>
							<td>Writable</td>
							<td>
								<?php if (is_writable(DIR_APPLICATION . 'config/authentication.php')): ?>
									<img src="images/accept.png" alt="" />
								<?php else: ?>
									<img src="images/cross.png" alt="" />
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td><?php echo DIR_APPLICATION . 'config/cart.php'; ?></td>
							<td>Writable</td>
							<td>
								<?php if (is_writable(DIR_APPLICATION . 'config/cart.php')): ?>
									<img src="images/accept.png" alt="" />
								<?php else: ?>
									<img src="images/cross.png" alt="" />
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td><?php echo DIR_APPLICATION . 'config/database.php'; ?></td>
							<td>Writable</td>
							<td>
								<?php if (is_writable(DIR_APPLICATION . 'config/database.php')): ?>
									<img src="images/accept.png" alt="" />
								<?php else: ?>
									<img src="images/cross.png" alt="" />
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td><?php echo DIR_APPLICATION . 'config/upload.php'; ?></td>
							<td>Writable</td>
							<td>
								<?php if (is_writable(DIR_APPLICATION . 'config/upload.php')): ?>
									<img src="images/accept.png" alt="" />
								<?php else: ?>
									<img src="images/cross.png" alt="" />
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td><?php echo DIR_APPLICATION . 'config/template.php'; ?></td>
							<td>Writable</td>
							<td>
								<?php if (is_writable(DIR_APPLICATION . 'config/template.php')): ?>
									<img src="images/accept.png" alt="" />
								<?php else: ?>
									<img src="images/cross.png" alt="" />
								<?php endif; ?>
							</td>
						</tr>
					</tbody>
				</table>
							
				<form action="" method="post">

					<?php if (check_settings()): ?>
						<div class="alert info">
							<p>
								<input type="checkbox" name="disclaimer" value="1" />		
								I assume all responsibility for any data loss or damage related to this upgrade.	
							</p>
						</div>
					<?php else: ?>
						<div class="alert error">
							<p>
								It seems that your server failed to meet the requirements to run Shopping Cart. Please contact 
								your server administrator or hosting company to get this resolved.
							</p>
						</div>
					<?php endif; ?>
			
					<?php if (check_settings()): ?>
						<button type="submit" name="submit" class="button orange float_right">Upgrade</button>
					<?php else: ?>
						<a href="upgrade.php" class="button orange float_right">Try again</a>
					<?php endif; ?>
										
				</form>
		
			<?php endif; ?>
			
			<?php if (isset($success)): ?>

				<p>You have just updated your Shopping Cart!</p>
				
				<p>Finally, delete the installer from the server.</p>

				<p>
					<a href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . rtrim(rtrim(dirname($_SERVER['PHP_SELF']), 'install'), '/.\\'). '/'; ?>" class="button orange">Catalog</a>
					<a href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . rtrim(rtrim(dirname($_SERVER['PHP_SELF']), 'install'), '/.\\'). '/admin'; ?>" class="button orange">Admin panel</a>
				</p>
							
			<?php endif; ?>
											
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
				
	</div>
	<!-- END #main -->

	<!-- BEGIN .container_12 -->
	<div class="container_12">
		
		<!-- BEGIN #footer -->
		<div id="footer" class="grid_12 text_center">
			<p>Copyright &copy; 2014 Rukhsar Manzoor</p>
		</div>
		<!-- END #footer -->
		
		<div class="clear">&nbsp;</div>
		
	</div>
	<!-- END .container_12 -->
			
</div>
<!-- END #wrapper -->
		
</body>
<!-- END body -->

</html>

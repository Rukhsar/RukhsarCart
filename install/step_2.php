<?php

//Include libraries
require_once('../libraries/Error.php');
require_once('../libraries/Session.php');
require_once('../libraries/Validate.php');

//Initialize objects
$error = new Error();
$session = new Session();
$validate = new Validate();

if (!$session->get('step_2')) header("Location: step_1.php");

//Check if the form has been submitted	
if (isset($_POST['submit'])) {

	$validate->required($_POST['site_title'], 'Enter your site title.');
	$validate->email($_POST['user_email'], 'User email address not valid.');
	$validate->required($_POST['user_password'], 'Enter your password.');
	$validate->required($_POST['first_name'], 'Enter your first name.');
	$validate->required($_POST['last_name'], 'Enter your last name.');
	$validate->email($_POST['admin_email'], 'Admin email address not valid.');
	
	if (!empty($_POST['shipping_cost']))
		$validate->numeric($_POST['shipping_cost'], 'Please enter a valid number for shipping cost.');
	
	if (!empty($_POST['tax_rate']))
		$validate->numeric($_POST['tax_rate'], 'Please enter a valid number for tax rate.');
		
	$validate->email($_POST['paypal_email'], 'PayPal email address not valid.');
	
	if (!$error->has_errors()) {

		define('DIR_APPLICATION', str_replace('\'', '/', realpath(dirname(__FILE__) . '/../')) . '/');
		define('HTTP_SERVER', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(rtrim(dirname($_SERVER['PHP_SELF']), 'install'), '/.\\'). '/');

		$config_authentication = file_get_contents('../config/authentication.php');	
		
		$replace = array(
			'__SITE_TITLE__'		=> $_POST['site_title'],
			'__HTTP_SERVER__' 		=> HTTP_SERVER,
			'__DIR_APPLICATION__'	=> DIR_APPLICATION,
			'__ADMIN_EMAIL__' 		=> $_POST['admin_email'],
			'__SECRET_WORD__' 		=> substr(md5(rand() . rand()), 0, 8)
		);
		
		if ($_POST['type_registration'] == 0) {
			
			$replace['__EMAIL_ACTIVATION__'] = "false";
			$replace['__APPROVE_REGISTRATION__'] = "false";
		
		} else if ($_POST['type_registration'] == 1) {
			
			$replace['__EMAIL_ACTIVATION__'] = "true";
			$replace['__APPROVE_REGISTRATION__'] = "false";
			
		} else if ($_POST['type_registration'] == 2) {
		
			$replace['__EMAIL_ACTIVATION__'] = "false";
			$replace['__APPROVE_REGISTRATION__'] = "true";
		
		}
		
		$output	= str_replace(array_keys($replace), $replace, $config_authentication);
		
		$file = fopen('../config/authentication.php', 'w');
		fwrite($file, $output);
		fclose($file);
		
		$config_cart = file_get_contents('../config/cart.php');
		
		$currency = explode('|', $_POST['currency']);
		
		$replace = array(
			'__SITE_TITLE__'		=> $_POST['site_title'],
			'__HTTP_SERVER__' 		=> HTTP_SERVER,
			'__DIR_APPLICATION__'	=> DIR_APPLICATION,
			'__ADMIN_EMAIL__' 		=> $_POST['admin_email'],
			'__CURRENCY_SYMBOL__'	=> $currency[0],
			'__CURRENCY_CODE__'		=> $currency[1],
			'__SHIPPING_COST__'		=> $_POST['shipping_cost'],
			'__TAX_DESCRIPTION__'	=> $_POST['tax_description'],
			'__TAX_RATE__'			=> $_POST['tax_rate'],
			'__PAYPAL_EMAIL__'		=> $_POST['paypal_email'],
		);

		$output	= str_replace(array_keys($replace), $replace, $config_cart);
		
		$file = fopen('../config/cart.php', 'w');
		fwrite($file, $output);
		fclose($file);
				
		$config_database = file_get_contents('../config/database.php');
		
		$replace = array(
			'__HOSTNAME__' 	=> $session->get('hostname'),
			'__USERNAME__' 	=> $session->get('username'),
			'__PASSWORD__' 	=> $session->get('password'),
			'__DBNAME__' 	=> $session->get('dbname')
		);
		
		$output	= str_replace(array_keys($replace), $replace, $config_database);
		
		$file = fopen('../config/database.php', 'w');
		fwrite($file, $output);
		fclose($file);
				
		$config_upload = file_get_contents('../config/upload.php');
		
		$replace = array(
			'__DIR_APPLICATION__'	=> DIR_APPLICATION
		);
		
		$output	= str_replace(array_keys($replace), $replace, $config_upload);
		
		$file = fopen('../config/upload.php', 'w');
		fwrite($file, $output);
		fclose($file);
		
		$config_template = file_get_contents('../config/template.php');
		
		$replace = array(
			'__HTTP_SERVER__'		=> HTTP_SERVER,
			'__DIR_APPLICATION__'	=> DIR_APPLICATION
		);
		
		$output	= str_replace(array_keys($replace), $replace, $config_template);
		
		$file = fopen('../config/template.php', 'w');
		fwrite($file, $output);
		fclose($file);

		$connection = mysql_connect($session->get('hostname'), $session->get('username'), $session->get('password'));
		
		if ($session->get('create_db'))
			mysql_query('CREATE DATABASE IF NOT EXISTS ' . $session->get('dbname'), $connection);

		mysql_select_db($session->get('dbname'), $connection);

		$sql = explode(';', file_get_contents(DIR_APPLICATION . 'install/database.sql'));

		foreach($sql as $query) mysql_query($query);
		
		mysql_query("INSERT INTO users VALUES (1, 1, '" . $_POST['user_email'] . "', SHA1('" . $_POST['user_password'] . "'), 1, '1', '', '', '', '', '')");	
		mysql_query("INSERT INTO user_profiles VALUES (1, 1, '" . $_POST['first_name'] . "', '" . $_POST['last_name'] . "')");	
		
		mysql_close($connection);
		
		$session->set('user_email', $_POST['user_email']);
		$session->set('user_password', $_POST['user_password']);
		$session->set('step_3', true);
		
		header("Location: step_3.php");
													
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
	<title>Installation (step 2)</title>

	<!-- Style sheet -->
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="css/reset.css" rel="stylesheet" type="text/css" />
	<link href="css/text.css" rel="stylesheet" type="text/css" />
	<link href="css/960.css" rel="stylesheet" type="text/css" />
		
</head>
<!-- END head -->

<!-- BEGIN body -->
<body>

<!-- BEGIN #wrapper -->
<div id="wrapper">

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
		
		<form action="" method="post">
			
			<!-- BEGIN .grid_12 -->
			<div class="grid_12">

				<div class="title_background">
					<h1>3. Shopping cart settings</h1>
				</div>

				<?php if ($error->has_errors()): ?>
				
					<div class="alert error">
						<span>
							<strong>An error occurred while processing request</strong>
						</span>
						<?php foreach ($error->display_errors() as $value): ?>		
							<p><?php echo $value; ?></p>
						<?php endforeach; ?>
					</div>
				
				<?php $error->clear_errors(); endif; ?>
						
				<p>
					<label>Site title:</label>
					<input type="text" id="site_title" name="site_title" size="50" value="<?php if (isset($_POST['site_title'])) echo $_POST['site_title']; ?>" />
					<br />
					<span>The name of the online store that will be used to send email.</span>
				</p>

				<p>
					<label>First name:</label>
					<input type="text" id="first_name" name="first_name" size="50" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" />
					<br />
					<span>Your first name.</span>
				</p>

				<p>
					<label>Last name:</label>
					<input type="text" id="last_name" name="last_name" size="50" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" />
					<br />
					<span>Your last name.</span>
				</p>

				<p>
					<label>User email address:</label>
					<input type="text" id="user_email" name="user_email" size="50" value="<?php if (isset($_POST['user_email'])) echo $_POST['user_email']; ?>" />
					<br />
					<span>The email address that will be used to login in the admin panel.</span>
				</p>

				<p>
					<label>User password:</label>
					<input type="password" id="user_password" name="user_password" size="50" value="<?php if (isset($_POST['user_password'])) echo $_POST['user_password']; ?>" />
					<br />
					<span>The password that is used together with the user email address to login in the admin panel.</span>
				</p>

				<p>
					<label>Owner email address:</label>
					<input type="text" id="admin_email" name="admin_email" size="50" value="<?php if (isset($_POST['admin_email'])) echo $_POST['admin_email']; ?>" />
					<br />
					<span>The owner email address that will be used to send email.</span>
				</p>

				<p>
					<label>Customer registration</label>					
					<select id="type_registration" name="type_registration">
						<option value="0">Immediate registration</option>
						<option value="1">Activation by email</option>
						<option value="2">Approval by the administrator</option>
					</select>
				</p>
				
				<p>
					<label>Currency</label>
					<select id="currency" name="currency">
						<option value="&amp#1583;&amp#46;&amp#1573;|AED">United Arab Emirates Dirham</option>
						<option value="&amp#36;|AUD">Australian Dollar</option>
						<option value="&amp#82;&amp#36;|BRL">Brazilian Real</option>
						<option value="&amp#36;|CAD">Canadian Dollar</option>
						<option value="&amp#67;&amp#72;&amp#70;|CHF">Swiss Franc</option>
						<option value="&amp#165;|CNY">Chinese Yuan</option>
						<option value="&amp#75;&amp#269;|CZK">Czech Koruna</option>
						<option value="&amp#107;&amp#114;|DKK">Danish Krone</option>
						<option value="&amp#8364;|EUR">Euro</option>
						<option value="&amp#163;|GBP">Pound Sterling</option>
						<option value="&amp#36;|HKD">Hong Kong Dollar</option>
						<option value="&amp#107;&amp#110;|HRK">Croatia Kuna</option>
						<option value="&amp#70;&amp#116;|HUF">Hungary Forint</option>
						<option value="&amp#82;&amp#112;|IDR">Indonesia Rupiah</option>
						<option value="&amp#8362;|ILS">Israeli Shekel</option>
						<option value="&amp#8377;|INR">Indian Rupee</option>						
						<option value="&amp#165;|JPY">Japanese Yen</option>
						<option value="&amp#36;|MXN">Mexican Peso</option>
						<option value="&amp#82;&amp#77;|MYR">Malaysian Ringgit</option>
						<option value="&amp#8358;|NGN">Nigerian Naira</option>
						<option value="&amp#107;&amp#114;|NOK">Norwegian Krone</option>
						<option value="&amp#36;|NZD">New Zealand Dollar</option>
						<option value="&amp#8369;|PHP">Philippine Peso</option>
						<option value="&amp#122;&amp#322;|PLN">Polish Zloty</option>
						<option value="&amp#108;&amp#101;&amp#105;|RON">Romanian New Leu</option>
						<option value="&amp#1088;&amp#1091;&amp#1073;|RUB">Russian Ruble</option>
						<option value="&amp#107;&amp#114;|SEK">Swedish Krona</option>
						<option value="&amp#36;|SGD">Singapore Dollar</option>
						<option value="&amp#3647;|THB">Thai Baht</option>
						<option value="&amp#8356;|TRY">Turkish Lira</option>
						<option value="&amp#78;&amp#84;&amp#36;|TWD">Taiwan New Dollar</option>
						<option value="&amp#36;|USD">US Dollar</option>
						<option value="&amp#82;|ZAR">South Africa Rand</option>
					</select>
				</p>

				<p>
					<label>Shipping cost</label>
					<input type="text" id="shipping_cost" name="shipping_cost" size="50" value="<?php echo empty($_POST['shipping_cost']) ? 0 : $_POST['shipping_cost']; ?>" />
					<br />
					<span>The cost of shipping.</span>
				</p>

				<p>
					<label>Tax description</label>
					<input type="text" id="tax_description" name="tax_description" size="50" value="<?php if (isset($_POST['tax_description'])) echo $_POST['tax_description']; ?>" />
					<br />
					<span>The tax description, example: VAT for UK.</span>
				</p>

				<p>
					<label>Tax rate</label>
					<input type="text" id="tax_rate" name="tax_rate" size="50" value="<?php echo empty($_POST['tax_rate']) ? 0 : $_POST['tax_rate']; ?>" />
					<br />
					<span>The tax rate.</span>
				</p>

				<p>
					<label>PayPal email address</label>
					<input type="text" id="paypal_email" name="paypal_email" size="50" value="<?php if (isset($_POST['paypal_email'])) echo $_POST['paypal_email']; ?>" />
					<br />
					<span>Your email address associated with your PayPal account.</span>
				</p>
																																						
			</div>
			<!-- END .grid_12 -->
			
			<div class="clear">&nbsp;</div>

			<!-- BEGIN .grid_12 -->
			<div class="grid_12">
				
				<button type="submit" name="submit" class="button orange float_right">Install</button>
										
			</div>
			<!-- END .grid_12 -->
		
			<div class="clear">&nbsp;</div>
		
		</form>
				
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

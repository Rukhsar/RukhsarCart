<?php

//Include the common file
require('../common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_admin()) header("Location: login.php");
//Countries
foreach ($db->query("SELECT * FROM " . config_item('cart', 'table_countries') . "") as $row)
	$countries[] = $row;
	
//Check if the form has been submitted
if (isset($_POST['submit'])) {

	$validate->required($_POST['first_name'], 'Insert first name.');
	$validate->required($_POST['last_name'], 'Insert last name.');
	$validate->email($_POST['email'], 'Email address not valid.');
	$validate->numeric($_POST['telephone'], 'Please enter a valid number for telephone.');
	$validate->required($_POST['address'], 'Insert address.');
	$validate->required($_POST['city'], 'Insert city.');
	$validate->required($_POST['post_code'], 'Insert post code.');
	$validate->required($_POST['zone'], 'Insert zone.');
	$validate->required($_POST['password'], 'Insert password.');
	$validate->matches($_POST['password'], $_POST['confirm_password'], 'The password field does not match the confirm password field.');

	if (!$error->has_errors()) {
		
		if ($authentication->check_email($_POST['email'])) {
		
			$additional_data = array(
				'first_name' 	=> $_POST['first_name'],
				'last_name' 	=> $_POST['last_name']
			);		
		
			$parameters = array(
				'user_status'	=> $_POST['user_status']
			);		
			
			if (isset($_POST['send_email']))
				$parameters['send_email'] = $_POST['send_email'];
				
			$authentication->create_user($_POST['email'], $_POST['password'], $additional_data, $parameters);

			$result = $db->fetch_row_assoc("SELECT user_id FROM " . config_item('authentication', 'table_users') . " WHERE user_email = '" . $_POST['email'] . "'");
			
			$values = array(
				'user_id' 				=> $result['user_id'],
				'first_name'			=> $_POST['first_name'], 
				'last_name'				=> $_POST['last_name'],
				'customer_email'		=> $_POST['email'], 
				'customer_telephone'	=> $_POST['telephone']
			); 
					
			$db->insert(config_item('cart', 'table_customers'), $values);

			$values = array(
				'user_id' 		=> $result['user_id'],
				'country_id' 	=> $_POST['country'],
				'company'		=> $_POST['company'],
				'first_name'	=> $_POST['first_name'], 
				'last_name'		=> $_POST['last_name'],
				'address'		=> $_POST['address'],
				'post_code' 	=> $_POST['post_code'],
				'city' 			=> $_POST['city'],
				'zone' 			=> ucfirst($_POST['zone'])
			);		

			$db->insert(config_item('cart', 'table_addresses'), $values);
			
			$tpl->set('success', true);
			
		} else {
			
			$tpl->set('failed', true);
			
		}
			
	}
	
}

//Template values
$tpl->set('countries', $countries);

//Display the template
$tpl->display('admin/add_customer');

?>

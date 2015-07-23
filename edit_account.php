<?php

//Include the common file
require_once('common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_group('customer')) header("Location: login.php");

//Customer details
foreach ($db->query("SELECT * FROM " . config_item('cart', 'table_customers') . " WHERE user_id = '" . $session->get('user_id') . "'") as $row)
	$customer_details[] = $row;

//Customer addresses
foreach ($db->query("SELECT * FROM " . config_item('cart', 'table_addresses') . " WHERE user_id = '" . $session->get('user_id') . "'") as $row)
	$customer_addresses[] = $row;

//Countries
foreach ($db->query("SELECT * FROM " . config_item('cart', 'table_countries') . "") as $row)
	$countries[] = $row;
	
//Check if the form has been submitted
if (isset($_POST['submit'])) {
	
	$validate->required($_POST['first_name'], 'Enter your first name.');
	$validate->required($_POST['last_name'], 'Enter your last name.');
	$validate->numeric($_POST['telephone'], 'Please enter a valid number for telephone.');
	$validate->required($_POST['address'], 'Enter your address.');
	$validate->required($_POST['city'], 'Enter your city.');
	$validate->required($_POST['post_code'], 'Enter your post code.');
	$validate->required($_POST['zone'], 'Enter your zone.');
	$validate->required($_POST['password'], 'Enter your password.');
	$validate->matches($_POST['password'], $_POST['confirm_password'], 'The password field does not match the confirm password field.');
	if (!$error->has_errors()) {

		$additional_data = array(
			'first_name' 	=> $_POST['first_name'],
			'last_name' 	=> $_POST['last_name']
		);
		
		$user = $authentication->get_user($session->get('user_id'));
		
		$authentication->update_user($session->get('user_id'), $user['user_email'], $_POST['password'], $additional_data);

		$values = array(
			'first_name'			=> $_POST['first_name'], 
			'last_name'				=> $_POST['last_name'],
			'customer_telephone'	=> $_POST['telephone']
		); 

		$where = array(
			'user_id' => $session->get('user_id')
		);
			
		$db->where($where);
		$db->update(config_item('cart', 'table_customers'), $values);

		$values = array(
			'country_id' 	=> $_POST['country'],
			'company'		=> $_POST['company'],
			'first_name'	=> $_POST['first_name'], 
			'last_name'		=> $_POST['last_name'],
			'address'		=> $_POST['address'],
			'post_code' 	=> $_POST['post_code'],
			'city' 			=> $_POST['city'],
			'zone' 			=> $_POST['zone']
		);		

		$where = array(
			'user_id' => $session->get('user_id')
		);
				
		$db->where($where);
		$db->update(config_item('cart', 'table_addresses'), $values);
		
		$session->set('success', true);
		
		header('Location: account.php');
			
	}
	
}

//Template values
$tpl->set('customer_details', $customer_details);
$tpl->set('customer_addresses', $customer_addresses);
$tpl->set('countries', $countries);

//Display the template
$tpl->display('edit_account');
			
?>

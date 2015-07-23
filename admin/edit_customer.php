<?php

//Include the common file
require('../common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_admin()) header("Location: login.php");

//Returns the number of rows
$row_count = $db->row_count("SELECT user_id FROM " . config_item('cart', 'table_customers') . " WHERE user_id = '" . $_GET['user_id'] . "'");
//Customer details
$customer_details = array();

foreach ($db->query("SELECT * FROM " . config_item('cart', 'table_customers') . " c, " . config_item('authentication', 'table_users') . " u WHERE c.user_id = '" . $_GET['user_id'] . "' AND c.user_id = u.user_id") as $row)
	$customer_details[] = $row;

//Customer addresses
$customer_addresses = array();

foreach ($db->query("SELECT * FROM " . config_item('cart', 'table_addresses') . " WHERE user_id = '" . $_GET['user_id'] . "'") as $row)
	$customer_addresses[] = $row;

//Countries
foreach ($db->query("SELECT * FROM " . config_item('cart', 'table_countries') . "") as $row)
	$countries[] = $row;
	
//Check if the form has been submitted
if (isset($_POST['submit'])) {
	
	$validate->required($_POST['first_name'], 'Insert first name.');
	$validate->required($_POST['last_name'], 'Insert last name.');
	$validate->numeric($_POST['telephone'], 'Please enter a valid number for telephone.');
	$validate->required($_POST['address'], 'Insert address.');
	$validate->required($_POST['city'], 'Insert city.');
	$validate->required($_POST['post_code'], 'Insert post code.');
	$validate->required($_POST['zone'], 'Insert zone.');
	
	if (!empty($_POST['password'])) {
		
		$validate->required($_POST['password'], 'Insert password.');
		$validate->matches($_POST['password'], $_POST['confirm_password'], 'The password field does not match the confirm password field.');
	
	}
	
	if (!$error->has_errors()) {

		$additional_data = array(
			'first_name' 	=> $_POST['first_name'],
			'last_name' 	=> $_POST['last_name']
		);

		$parameters = array(
			'user_status' => $_POST['user_status']
		);
		
		if (!empty($_POST['password']))
			$password = $_POST['password'];
		else
			$password = false;
		
		$user = $authentication->get_user($_GET['user_id']);
		
		$authentication->update_user($_POST['user_id'], $user['user_email'], $password, $additional_data, $parameters);

		$values = array(
			'first_name'			=> $_POST['first_name'], 
			'last_name'				=> $_POST['last_name'],
			'customer_telephone'	=> $_POST['telephone']
		); 
			
		$where = array(
			'user_id' => $_POST['user_id']
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
			'user_id' => $_POST['user_id']
		);
				
		$db->where($where);
		$db->update(config_item('cart', 'table_addresses'), $values);
		
		$session->set('success', true);
		
		header('Location: customers.php');
			
	}
	
}

//Template values
$tpl->set('row_count', $row_count);
$tpl->set('customer_details', $customer_details);
$tpl->set('customer_addresses', $customer_addresses);
$tpl->set('countries', $countries);

//Display the template
$tpl->display('admin/edit_customer');

?>

<?php

//Include the common file
require_once('common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_group('customer')) header("Location: login.php");

//Check if the form has been submitted
if (isset($_POST['confirm_order']))
	$cart->checkout($session->get('user_id'), $session->get('user_email'), $_POST['payment_method'], $_POST['comment']);

//Customer addresses
foreach ($db->query("SELECT * FROM " . config_item('cart', 'table_addresses') . " WHERE user_id = '" . $session->get('user_id') . "'") as $row)
	$customer_addresses[] = $row;

//Countries
foreach ($db->query("SELECT * FROM " . config_item('cart', 'table_countries') . "") as $row)
	$countries[] = $row;
		
//Template values
$tpl->set('customer_addresses', $customer_addresses);
$tpl->set('countries', $countries);
//Display the template
$tpl->display('checkout');
			
?>

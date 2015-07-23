<?php

//Include the common file
require('../common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_admin()) header("Location: login.php");

//Returns the number of rows
$row_count = $db->row_count("SELECT coupon_id FROM " . config_item('cart', 'table_coupons') . " WHERE coupon_id = '" . $_GET['coupon_id'] . "'");
//Coupon details
$coupon_details = array();

foreach ($db->query("SELECT * FROM " . config_item('cart', 'table_coupons') . " WHERE coupon_id = '" . $_GET['coupon_id'] . "'") as $row)
	$coupon_details[] = $row;
	
//Check if the form has been submitted
if (isset($_POST['submit'])) {

	$validate->required($_POST['coupon_name'], 'Enter a coupon name.');
	$validate->required($_POST['coupon_code'], 'Please enter a valid number for code.');

	if (!empty($_POST['coupon_discount']))
		$validate->numeric($_POST['coupon_discount'], 'Please enter a valid number for discount.');

	$validate->required($_POST['date_start_1'], 'Please select a valid date start.');
	
	if (!empty($_POST['date_start_1']) && empty($_POST['date_start_2']))
		$validate->required($_POST['date_start_2'], 'Please select a valid date start.');
	
	if (!empty($_POST['date_start_1']) && !empty($_POST['date_start_2']) && empty($_POST['date_start_3']))
		$validate->required($_POST['date_start_3'], 'Please select a valid date start.');
		
	$validate->required($_POST['date_end_1'], 'Please select a valid date end.');
	
	if (!empty($_POST['date_end_1']) && empty($_POST['date_end_2']))	
		$validate->required($_POST['date_end_2'], 'Please select a valid date end.');
	
	if (!empty($_POST['date_end_1']) && !empty($_POST['date_end_2']) && empty($_POST['date_end_3']))
		$validate->required($_POST['date_end_3'], 'Please select a valid date end.');
		
	if (!$error->has_errors()) {

		$values = array(
			'coupon_name'		=> $_POST['coupon_name'], 
			'coupon_code' 		=> $_POST['coupon_code'],
			'coupon_type' 		=> $_POST['coupon_type'],
			'coupon_discount'	=> $_POST['coupon_discount'],
			'date_start'		=> $_POST['date_start_3'] . '-' . $_POST['date_start_2'] . '-' . $_POST['date_start_1'],
			'date_end'			=> $_POST['date_end_3'] . '-' . $_POST['date_end_2'] . '-' . $_POST['date_end_1'],
			'coupon_status'		=> $_POST['coupon_status']
		); 			

		$where = array(
			'coupon_id' => $_POST['coupon_id']
		);
		
		$db->where($where);
		$db->update(config_item('cart', 'table_coupons'), $values);
							
		$session->set('success', true);
		
		header('Location: coupons.php');
			
	}
	
}

//Template values
$tpl->set('row_count', $row_count);
$tpl->set('coupon_details', $coupon_details);

//Display the template
$tpl->display('admin/edit_coupon');

?>

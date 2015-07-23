<?php

//Include the common file
require('../common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_admin()) header("Location: login.php");

//Check if the form has been submitted
if (isset($_POST['submit'])) {

	$validate->required($_POST['site_title'], 'Site title required.');
	$validate->required($_POST['site_url'], 'Site URL required.');
	$validate->email($_POST['admin_email'], 'Email address admin not valid.');

	if (!empty($_POST['shipping_cost']))
		$validate->numeric($_POST['shipping_cost'], 'Please enter a valid number for shipping cost.');

	if (!empty($_POST['tax_rate']))
		$validate->numeric($_POST['tax_rate'], 'Please enter a valid number for tax rate.');
					
	$validate->numeric($_POST['per_page_catalog'], 'Catalog items limit required.');
	$validate->numeric($_POST['per_page_admin'], 'Admin items limit required.');
	
	$validate->email($_POST['paypal_email'], 'Email address PayPal not valid.');
	$validate->required($_POST['paypal_cancel_return'], 'PayPal URL for "Payment Canceled" page is required.');
	
	$validate->numeric($_POST['max_filesize'], 'Maximum upload size is required.');
	$validate->numeric($_POST['max_width_thumbnail'], 'Image thumbnail size dimensions required.');
	$validate->numeric($_POST['max_height_thumbnail'], 'Image thumbnail size dimensions required.');
	$validate->numeric($_POST['max_width'], 'Image size dimensions required.');
	$validate->numeric($_POST['max_height'], 'Image size size dimensions required.');
	
	if (!$error->has_errors()) {

		save_config($_POST);
		
		$tpl->set('success', true);
			
	}
	
}
//Display the template
$tpl->display('admin/settings');

?>

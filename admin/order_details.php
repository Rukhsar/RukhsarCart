<?php

//Include the common file
require('../common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_admin()) header("Location: login.php");

//Returns the number of rows
$row_count = $db->row_count("SELECT order_id FROM " . config_item('cart', 'table_orders') . " WHERE order_id = '" . $_GET['order_id'] . "'");

//Countries
foreach ($db->query("SELECT * FROM " . config_item('cart', 'table_countries') . "") as $row)
	$countries[] = $row;

//Order details
$order_details = array();

foreach ($db->query("SELECT * FROM " . config_item('cart', 'table_orders') . " WHERE order_id = '" . $_GET['order_id'] . "'") as $row) {
	
	//Subtotal
	$order_products = array();
	$subtotal = 0;

	foreach ($db->query("SELECT op.order_product_id, op.product_name, op.product_quantity, op.product_price, op.total, o.currency FROM " . config_item('cart', 'table_order_products') . " op, " . config_item('cart', 'table_orders') . " o WHERE op.order_id = '" . $_GET['order_id'] . "' AND o.order_id = '" . $_GET['order_id'] . "'") as $product) {

		$option_price = 0;
		$options = array();
		
		foreach ($db->query("SELECT * FROM " . config_item('cart', 'table_order_options') . " WHERE order_id = '" . $_GET['order_id'] . "' AND order_product_id = '" . $product['order_product_id'] . "'") as $option) {

			$options[] = array(
				'option_value'	=> $option['option_value']
			);
					
			if ($option['option_type'] == '+')
				$option_price = $option_price + $option['option_price'];
			elseif ($option['option_type'] == '-')
				$option_price = $option_price - $option['option_price'];

		}
		
		$subtotal += ($product['product_price'] + $option_price) * $product['product_quantity'];

		$order_products[] = array(
			'product_price' 	=> $product['product_price'] + $option_price,
			'total_price' 		=> $product['total'] + ($product['product_quantity'] * $option_price),
			'product_quantity' 	=> $product['product_quantity'],
			'product_name' 		=> $product['product_name'],
			'currency' 			=> $product['currency'],
			'options' 			=> $options
		);
		
	}

	if ($row['tax_shipping'])
		$total_tax = ($subtotal + $row['shipping_cost']) * $row['tax_rate'] / 100;
	else	
		$total_tax = $subtotal * $row['tax_rate'] / 100;
		
	$order_details[] = array(
		'shipping_first_name' 	=> $row['shipping_first_name'],
		'shipping_last_name' 	=> $row['shipping_last_name'],
		'shipping_company' 		=> $row['shipping_company'],
		'shipping_address' 		=> $row['shipping_address'],
		'shipping_city' 		=> $row['shipping_city'],
		'shipping_post_code' 	=> $row['shipping_post_code'],
		'shipping_country_id' 	=> $row['shipping_country_id'],
		'shipping_zone' 		=> $row['shipping_zone'],
		'payment_first_name' 	=> $row['payment_first_name'],
		'payment_last_name' 	=> $row['payment_last_name'],
		'payment_company' 		=> $row['payment_company'],
		'payment_address' 		=> $row['payment_address'],
		'payment_city' 			=> $row['payment_city'],
		'payment_post_code' 	=> $row['payment_post_code'],
		'payment_country_id' 	=> $row['payment_country_id'],
		'payment_zone' 			=> $row['payment_zone'],
		'shipping_method' 		=> $row['shipping_method'],
		'payment_method' 		=> $row['payment_method'],
		'currency' 				=> $row['currency'],
		'shipping_cost' 		=> $row['shipping_cost'],
		'tax_rate' 				=> $row['tax_rate'],
		'tax_description' 		=> $row['tax_description'],
		'coupon_discount' 		=> $row['coupon_discount'],
		'coupon_name' 			=> $row['coupon_name'],
		'total' 				=> $row['total'],
		'comment' 				=> $row['comment'],
		'order_products' 		=> $order_products,
		'subtotal' 				=> $subtotal,
		'total_tax'				=> $total_tax
	);

}

//Order status
$order_status = array();

foreach ($db->query("SELECT date_added, status_name FROM " . config_item('cart', 'table_order_status') . " os, " . config_item('cart', 'table_order_status_descriptions') . " osd WHERE os.order_id = '" . $_GET['order_id'] . "' AND os.order_status_description_id = osd.order_status_description_id ORDER BY os.order_status_id DESC") as $row)
	$order_status[] = $row;
			
//Check if the form has been submitted
if (isset($_POST['submit'])) {

	$values = array(
		'order_status_description_id' => $_POST['order_status_description_id']
	); 	
		
	$where = array(
		'order_id' => $_POST['order_id']
	);

	$db->where($where);
	$db->update(config_item('cart', 'table_orders'), $values);

	foreach ($db->query("SELECT order_digital_good_id, number_days FROM " . config_item('cart', 'table_order_digital_goods') . " WHERE order_id = '" . $_POST['order_id'] . "'") as $row) {
		
		if ($row['number_days'] > 0) {
		
			$values = array(
				'date_expiration' => time() + ($row['number_days'] * 3600 * 24)
			);
						
		}

		$where = array(
			'order_id'				=> $_POST['order_id'],
			'order_digital_good_id'	=> $row['order_digital_good_id']
		);

		$db->where($where);
		$db->update(config_item('cart', 'table_order_digital_goods'), $values);
					
	}

	$values = array(
		'order_id' 						=> $_POST['order_id'],
		'order_status_description_id'	=> $_POST['order_status_description_id'],
		'date_added'           			=> time()
	);
				
	$db->insert(config_item('cart', 'table_order_status'), $values);

	$session->set('success', true);
	
	header('Location: orders.php');
						
}
//Template values
$tpl->set('row_count', $row_count);
$tpl->set('order_details', $order_details);
$tpl->set('countries', $countries);
$tpl->set('order_status', $order_status);

//Display the template
$tpl->display('admin/order_details');

?>

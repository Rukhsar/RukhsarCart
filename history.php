<?php

//Include the common file
require_once('common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_group('customer')) header("Location: login.php");

//Returns the number of rows
$row_count = $db->row_count("SELECT * FROM " . config_item('cart', 'table_orders') . " WHERE user_id = '" . $session->get('user_id') . "'");

//Orders
$orders = array();

foreach ($db->query("SELECT * FROM " . config_item('cart', 'table_orders') . " o, " . config_item('cart', 'table_order_status_descriptions') . " osd WHERE o.user_id = '" . $session->get('user_id') . "' AND o.order_status_description_id = osd.order_status_description_id ORDER BY o.order_id DESC") as $row) {
	$order_products = array();
	$subtotal = 0;
	
	foreach ($db->query("SELECT order_product_id, product_id, product_name, product_price, product_quantity FROM " . config_item('cart', 'table_order_products') . " op, " . config_item('cart', 'table_orders') . " o WHERE o.user_id = '" . $session->get('user_id') . "' AND o.order_id = '" . $row['order_id'] . "' AND op.order_id = '" . $row['order_id'] . "'") as $product) {
		
		$option_price = 0;
		$options = array();
		
		foreach ($db->query("SELECT * FROM " . config_item('cart', 'table_order_options') . " WHERE order_id = '" . $row['order_id'] . "' AND order_product_id = '" . $product['order_product_id'] . "'") as $option) {
		
			$options[] = array(
				'option_name' 	=> $option['option_name'],
				'option_value'	=> $option['option_value']
			);

			if ($option['option_type'] == '+')
				$option_price = $option_price + $option['option_price'];
			elseif ($option['option_type'] == '-')
				$option_price = $option_price - $option['option_price'];
															
		}
		
		$subtotal += ($product['product_price'] + $option_price) * $product['product_quantity'];

		if ($row['tax_shipping'])
			$tax_cart = ($subtotal + $row['shipping_cost']) * $row['tax_rate'] / 100;
		else	
			$tax_cart = $subtotal * $row['tax_rate'] / 100;
				
		$order_products[] = array(
			'product_id' 		=> $product['product_id'],
			'product_name' 		=> $product['product_name'],
			'product_price' 	=> number_format($product['product_price'] + $option_price, 2, '.', ','),
			'product_quantity' 	=> $product['product_quantity'],
			'tax_cart' 			=> number_format($tax_cart, 2, '.', ','),
			'options' 			=> $options
		);
					
	}

	$orders[] = array(
		'order_id' 			=> $row['order_id'],
		'date_added' 		=> $row['date_added'],
		'first_name' 		=> $row['first_name'],
		'last_name' 		=> $row['last_name'],
		'status_name' 		=> $row['status_name'],
		'currency' 			=> $row['currency'],
		'total' 			=> $row['total'],
		'payment_method' 	=> $row['payment_method'],
		'shipping_cost' 	=> number_format($row['shipping_cost'], 2, '.', ','),
		'tax_rate' 			=> $row['tax_rate'],
		'coupon_discount' 	=> number_format($row['coupon_discount'], 2, '.', ','),
		'order_products' 	=> $order_products
	);
			
}
	
//Template values
$tpl->set('row_count', $row_count);
$tpl->set('orders', $orders);

//Display the template
$tpl->display('history');
			
?>

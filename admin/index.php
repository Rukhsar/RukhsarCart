<?php

//Include the common file
require('../common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_admin()) header("Location: login.php");

//Returns the number of rows
$row_count = $db->row_count("SELECT order_id FROM " . config_item('cart', 'table_orders'));
//Top sellers
$top_sellers = array();

foreach ($db->query("SELECT op.product_name, SUM(op.product_quantity) AS total FROM " . config_item('cart', 'table_order_products') . " op LEFT JOIN " . config_item('cart', 'table_orders') . " o ON (op.order_id = o.order_id) GROUP BY op.product_id ORDER BY total DESC LIMIT 5") as $row)
	$top_sellers[] = $row;

//Latest 5 orders
$latest_orders = array();

foreach($db->query("SELECT * FROM " . config_item('cart', 'table_orders') . " o, " . config_item('cart', 'table_order_status_descriptions') . " osd WHERE o.order_status_description_id = osd.order_status_description_id ORDER BY o.order_id DESC LIMIT 5") as $row) {
	
	$latest_orders[] = array(
		'order_id' 		=> $row['order_id'],
		'date_added' 	=> $row['date_added'],
		'first_name' 	=> $row['first_name'],
		'last_name' 	=> $row['last_name'],
		'status_name' 	=> $row['status_name'],
		'currency' 		=> $row['currency'],
		'total' 		=> $row['total']
	);

}
	
//Logout
if (isset($_GET['logout'])) {
	
	$authentication->logout('admin/login.php');
	
	header("Location: login.php");
	
}

//Template values
$tpl->set('row_count', $row_count);
$tpl->set('top_sellers', $top_sellers);
$tpl->set('latest_orders', $latest_orders);

//Display the template
$tpl->display('admin/index');

?>

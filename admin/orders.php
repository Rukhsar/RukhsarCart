<?php

//Include the common file
require('../common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_admin()) header("Location: login.php");

//Returns the number of rows
$row_count = $db->row_count("SELECT order_id FROM " . config_item('cart', 'table_orders'));

//Pagination
if (isset($_GET['page']))
	$current_page = trim($_GET['page']);
else
	$current_page = 1;
	 
$start = ($current_page -1 ) * config_item('cart', 'per_page_admin'); 	

$sql = "SELECT * FROM " . config_item('cart', 'table_orders') . " o, " . config_item('cart', 'table_order_status_descriptions') . " osd WHERE o.order_status_description_id = osd.order_status_description_id";

$implode = array();
	
if (isset($_POST['filter']) && !empty($_POST['filter_order_id']))
	$implode[] = " o.order_id = '" . $_POST['filter_order_id'] . "'";

if (isset($_POST['filter']) && !empty($_POST['filter_name']))
	$implode[] = " CONCAT(o.first_name, ' ', o.last_name) LIKE '%" . $_POST['filter_name'] . "%'";

if (isset($_POST['filter']) && !empty($_POST['order_status_description_id']))
	$implode[] = " o.order_status_description_id = '" . $_POST['order_status_description_id'] . "'";
											
if ($implode)
	$sql .= " AND " . implode(" AND ", $implode);
									
$sql .= " ORDER BY o.order_id DESC";

$sql .= " LIMIT " . $start . ", " . config_item('cart', 'per_page_admin') . "";
											
$pages = ceil($db->row_count("SELECT order_id FROM " . config_item('cart', 'table_orders') . "") / config_item('cart', 'per_page_admin'));

//Orders
$orders = array();

foreach ($db->query($sql) as $row) {
	
	$orders[] = array(
		'order_id' 		=> $row['order_id'],
		'first_name'	=> $row['first_name'],
		'last_name'		=> $row['last_name'],
		'status_name'	=> $row['status_name'],
		'currency' 		=> $row['currency'],
		'total'			=> $row['total']
	);

}							
//Template values
$tpl->set('row_count', $row_count);
$tpl->set('current_page', $current_page);
$tpl->set('pages', $pages);
$tpl->set('orders', $orders);

//Display the template
$tpl->display('admin/orders');

?>

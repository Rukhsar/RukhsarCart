<?php

//Include the common file
require_once('common.php');

//Category details
$result = $cart->get_category($_GET['category_id']);

//Pagination
if (isset($_GET['page']))
	$current_page = trim($_GET['page']);
else
	$current_page = 1;
	 
$start = ($current_page -1 ) * config_item('cart', 'per_page_catalog'); 	

$sql = "SELECT * FROM " . config_item('cart', 'table_products') . " p, " . config_item('cart', 'table_category_products') . " cp WHERE p.product_id = cp.product_id AND cp.category_id = '" . $_GET['category_id'] . "' LIMIT " . $start . ", " . config_item('cart', 'per_page_catalog') . "";
					
$pages = ceil($db->row_count("SELECT product_id FROM " . config_item('cart', 'table_category_products') . " WHERE category_id = '" . $_GET['category_id'] . "'") / config_item('cart', 'per_page_catalog'));

//Products
$products = array();

foreach ($db->query($sql) as $row) {
	
	$products[] = array(
		'product_id' 		=> $row['product_id'],
		'product_thumbnail'	=> $row['product_thumbnail'],
		'product_price' 	=> $row['product_price'],
		'product_name' 		=> $row['product_name']
	);

}
//Template values
$tpl->set('category_name', $result['category_name']);
$tpl->set('current_page', $current_page);
$tpl->set('pages', $pages);
$tpl->set('products', $products);

//Display the template
$tpl->display('category');
	
?>

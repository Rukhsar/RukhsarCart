<?php

//Include the common file
require_once('common.php');

//Products
$products = array();

foreach ($db->query("SELECT * FROM " . config_item('cart', 'table_products') . " ORDER BY product_id DESC LIMIT " . config_item('cart', 'per_page_catalog') . "") as $row) {
	
	$products[] = array(
		'product_id' 		=> $row['product_id'],
		'product_thumbnail' => $row['product_thumbnail'],
		'product_price' 	=> $row['product_price'],
		'product_name' 		=> $row['product_name']
	);

}
//Template values
$tpl->set('products', $products);

//Display the template
$tpl->display('index');

?>

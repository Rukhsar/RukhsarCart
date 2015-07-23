<?php

//Include the common file
require_once('common.php');

//Search
$sql = "SELECT * FROM " . config_item('cart', 'table_products') . "";

$implode = array();

if (isset($_POST['filter']) && !empty($_POST['filter_product_name']))
	$implode[] = " LCASE(product_name) LIKE '%" . strtolower($_POST['filter_product_name']) . "%'";

if ($implode)
	$sql .= " WHERE " . implode(" AND ", $implode);

//Results
$results = array();

foreach ($db->query($sql) as $row) {
	
	$results[] = array(
		'product_id' 		=> $row['product_id'],
		'product_thumbnail'	=> $row['product_thumbnail'],
		'product_price' 	=> $row['product_price'],
		'product_name' 		=> $row['product_name']
	);

}
//Template values
$tpl->set('results', $results);

//Display the template
$tpl->display('search');
																							
?>

<?php

//Include the common file
require('../common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_admin()) header("Location: login.php");

//Returns the number of rows
$row_count = $db->row_count("SELECT product_id FROM " . config_item('cart', 'table_products'));

//Pagination
if (isset($_GET['page']))
	$current_page = trim($_GET['page']);
else
	$current_page = 1;
	 
$start = ($current_page -1 ) * config_item('cart', 'per_page_admin'); 	

$sql = "SELECT * FROM " . config_item('cart', 'table_products') . "";

$implode = array();

if (isset($_POST['filter']) && !empty($_POST['filter_product_name']))
	$implode[] = " LCASE(product_name) LIKE '%" . strtolower($_POST['filter_product_name']) . "%'";

if (isset($_POST['filter']) && !empty($_POST['filter_product_price']))
	$implode[] = " LCASE(product_price) LIKE '%" . strtolower($_POST['filter_product_price']) . "%'";

if (isset($_POST['filter']) && !empty($_POST['filter_product_quantity']))
	$implode[] = " product_quantity = '" . $_POST['filter_product_quantity'] . "'";

if ($implode)
	$sql .= " WHERE " . implode(" AND ", $implode);

$sql .= " LIMIT " . $start . ", " . config_item('cart', 'per_page_admin') . "";
												
$pages = ceil($db->row_count("SELECT product_id FROM " . config_item('cart', 'table_products') . "") / config_item('cart', 'per_page_admin'));

//Products
$products = array();

foreach ($db->query($sql) as $row) {
	
	$products[] = array(
		'product_id' 		=> $row['product_id'],
		'product_image'		=> $row['product_image'],
		'product_thumbnail'	=> $row['product_thumbnail'],
		'product_name' 		=> $row['product_name'],
		'product_price' 	=> $row['product_price'],
		'product_quantity'	=> $row['product_quantity']
	);

}

//Check if the form has been submitted
if (isset($_POST['cb_product'])) {

	foreach ($_POST['cb_product'] as $value) {
		
		$result = $db->fetch_row_assoc("SELECT product_image, product_thumbnail FROM " . config_item('cart', 'table_products') . " WHERE product_id = '" . $value . "'");
		
		if ($result['product_image']) {
		
			@unlink(config_item('upload', 'upload_path') . 'images/' . $result['product_image']);
			@unlink(config_item('upload', 'upload_path') . 'images/' . $result['product_thumbnail']);
		
		}
		
		if ($db->fetch_row_assoc("SELECT image_id FROM " . config_item('cart', 'table_product_images') . " WHERE product_id = '" . $value . "'") > 0) {
			
			foreach ($db->query("SELECT image, thumbnail FROM " . config_item('cart', 'table_product_images') . " WHERE product_id = '" . $value . "'") as $row) {
				
				@unlink(config_item('upload', 'upload_path') . 'images/' . $row['image']);
				@unlink(config_item('upload', 'upload_path') . 'images/' . $row['thumbnail']);
			
			}
		
		}
		
		$where = array(
			'product_id' => $value
		);
		
		$db->where($where);
		$db->delete(config_item('cart', 'table_products'));
		$db->delete(config_item('cart', 'table_category_products'));
		$db->delete(config_item('cart', 'table_product_options'));
		$db->delete(config_item('cart', 'table_product_option_values'));
		$db->delete(config_item('cart', 'table_product_images'));
		
		$result = $db->fetch_row_assoc("SELECT filename FROM " . config_item('cart', 'table_digital_goods') . " WHERE product_id = '" . $value . "'");
		
		if ($result['filename'])
			@unlink(config_item('upload', 'upload_path') . 'files/' . $result['filename']);
		
		$db->delete(config_item('cart', 'table_digital_goods'));
		
	}
	header("Location: products.php");
	
}

//Template values
$tpl->set('row_count', $row_count);
$tpl->set('current_page', $current_page);
$tpl->set('pages', $pages);
$tpl->set('products', $products);

//Display the template
$tpl->display('admin/products');

?>

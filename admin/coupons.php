<?php

//Include the common file
require('../common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_admin()) header("Location: login.php");

//Returns the number of rows
$row_count = $db->row_count("SELECT coupon_id FROM " . config_item('cart', 'table_coupons'));
//Pagination
if (isset($_GET['page']))
	$current_page = trim($_GET['page']);
else
	$current_page = 1;
	 
$start = ($current_page -1 ) * config_item('cart', 'per_page_admin'); 	

$sql = "SELECT *, DATE_FORMAT(date_start, '%d - %m - %Y') AS date_start, DATE_FORMAT(date_end, '%d - %m - %Y') AS date_end FROM " . config_item('cart', 'table_coupons') . "";

$sql .= " LIMIT " . $start . ", " . config_item('cart', 'per_page_admin') . "";
											
$pages = ceil($db->row_count("SELECT coupon_id FROM " . config_item('cart', 'table_coupons') . "") / config_item('cart', 'per_page_admin'));

//Coupons
$coupons = array();

foreach ($db->query($sql) as $row) {
	
	$coupons[] = array(
		'coupon_id' 		=> $row['coupon_id'],
		'coupon_name'		=> $row['coupon_name'],
		'coupon_code'		=> $row['coupon_code'],
		'coupon_discount'	=> $row['coupon_discount'],
		'date_start'		=> $row['date_start'],
		'date_end'			=> $row['date_end'],
		'coupon_status'		=> $row['coupon_status']
	);

}
								
//Check if the form has been submitted
if (isset($_POST['cb_coupon'])) {

	foreach ($_POST['cb_coupon'] as $value) {

		$where = array(
			'coupon_id' => $value
		);
		
		$db->where($where);
		$db->delete(config_item('cart', 'table_coupons'));
					
	}
	
	header("Location: coupons.php");
	
}

//Template values
$tpl->set('row_count', $row_count);
$tpl->set('current_page', $current_page);
$tpl->set('pages', $pages);
$tpl->set('coupons', $coupons);

//Display the template
$tpl->display('admin/coupons');

?>

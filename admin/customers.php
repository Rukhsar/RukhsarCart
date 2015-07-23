<?php

//Include the common file
require('../common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_admin()) header("Location: login.php");

//Returns the number of rows
$row_count = $db->row_count("SELECT customer_id FROM " . config_item('cart', 'table_customers'));
//Pagination
if (isset($_GET['page']))
	$current_page = trim($_GET['page']);
else
	$current_page = 1;
	 
$start = ($current_page -1 ) * config_item('cart', 'per_page_admin'); 	

$sql = "SELECT * FROM " . config_item('cart', 'table_customers') . " c, " . config_item('authentication', 'table_users') . " u WHERE c.user_id = u.user_id";

$implode = array();

if (isset($_POST['filter']) && !empty($_POST['filter_name']))
	$implode[] = " CONCAT(c.first_name, ' ', c.last_name) LIKE '%" . $_POST['filter_name'] . "%'";
		
if (isset($_POST['filter']) && !empty($_POST['filter_email']))
	$implode[] = " c.customer_email = '" . $_POST['filter_email'] . "'";
	
if ($implode)
	$sql .= " AND " . implode(" AND ", $implode);
									
$sql .= " ORDER BY c.customer_id DESC";

$sql .= " LIMIT " . $start . ", " . config_item('cart', 'per_page_admin') . "";
											
$pages = ceil($db->row_count("SELECT customer_id FROM " . config_item('cart', 'table_customers') . "") / config_item('cart', 'per_page_admin'));

//Customers
$customers = array();

foreach ($db->query($sql) as $row) {
	
	$customers[] = array(
		'user_id' 			=> $row['user_id'],
		'first_name'		=> $row['first_name'],
		'last_name'			=> $row['last_name'],
		'customer_email'	=> $row['customer_email'],
		'user_created'		=> $row['user_created']
	);

}
								
//Check if the form has been submitted
if (isset($_POST['cb_customer'])) {

	foreach ($_POST['cb_customer'] as $value) {

		$where = array(
			'user_id' => $value
		);
		
		$db->where($where);	
		$db->delete(config_item('authentication', 'table_users'));
		$db->delete(config_item('authentication', 'table_meta'));
		$db->delete(config_item('cart', 'table_customers'));
		$db->delete(config_item('cart', 'table_addresses'));
			
	}
	
	header("Location: customers.php");
	
}

//Template values
$tpl->set('row_count', $row_count);
$tpl->set('current_page', $current_page);
$tpl->set('pages', $pages);
$tpl->set('customers', $customers);

//Display the template
$tpl->display('admin/customers');

?>

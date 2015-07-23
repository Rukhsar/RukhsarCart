<?php

//Include the common file
require('../common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_admin()) header("Location: login.php");

//Returns the number of rows
$row_count = $db->row_count("SELECT category_id FROM " . config_item('cart', 'table_categories') . " WHERE category_id = '" . $_GET['category_id'] . "'");
//Category details
$category_details = array();

foreach ($db->query("SELECT * FROM " . config_item('cart', 'table_categories') . " WHERE category_id = '" . $_GET['category_id'] . "'") as $row)
	$category_details[] = $row;
	
//Categories
function categories($parent_id) {
	
	global $db;
	
	$categories = array();
	
	foreach ($db->query("SELECT category_id, category_name FROM " . config_item('cart', 'table_categories') . " WHERE parent_id = '" . $parent_id . "' ORDER BY category_name ASC") as $value) {
							
		$categories[] = array(
			'category_id'	=> $value['category_id'],
			'category_name'	=> get_path($value['category_id'])
		);
		
		$categories = array_merge($categories, categories($value['category_id']));
		
	}	
	
	return $categories;
}

function get_path($category_id) {

	global $db;
	
	$result = $db->fetch_row_assoc("SELECT category_name, parent_id FROM " . config_item('cart', 'table_categories') . " WHERE category_id = '" . $category_id . "' ORDER BY category_name ASC");
	
	if ($result['parent_id'])
		return get_path($result['parent_id']) .' > '. $result['category_name'];
	else
		return $result['category_name'];	

}
							
//Check if the form has been submitted
if (isset($_POST['submit'])) {

	$validate->required($_POST['category_name'], 'Enter category name.');

	if (!$error->has_errors()) {

		$values = array(
			'category_name'		=> $_POST['category_name'], 
			'parent_id' 		=> $_POST['parent_id'],
			'category_status'	=> $_POST['category_status']
		); 			

		$where = array(
			'category_id' => $_POST['category_id']
		);
		
		$db->where($where);
		$db->update(config_item('cart', 'table_categories'), $values);
							
		$session->set('success', true);
		
		header('Location: categories.php');
			
	}
}

//Template values
$tpl->set('row_count', $row_count);
$tpl->set('category_details', $category_details);

//Display the template
$tpl->display('admin/edit_category');

?>

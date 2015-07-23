<?php

//Include the common file
require('../common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_admin()) header("Location: login.php");
//Returns the number of rows
$row_count = $db->row_count("SELECT category_id FROM " . config_item('cart', 'table_categories'));

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
if (isset($_POST['cb_category'])) {
	
	foreach ($_POST['cb_category'] as $value) {

		$where = array(
			'category_id' => $value
		);
		
		$db->where($where);
		$db->delete(config_item('cart', 'table_categories'));
		
		foreach ($db->query("SELECT category_id FROM " . config_item('cart', 'table_categories') . " WHERE parent_id = '" . $value . "'") as $row) {

			$where = array(
				'category_id' => $row['category_id']
			);
			
			$db->where($where);
			$db->delete(config_item('cart', 'table_categories'));
		
		}
					
	}
	
	header("Location: categories.php");
	
}

//Template values
$tpl->set('row_count', $row_count);

//Display the template
$tpl->display('admin/categories');

?>

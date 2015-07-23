<?php

//Include the common file
require('../common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_admin()) header("Location: login.php");
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

	$validate->required($_POST['category_id'], 'Select category.');
	$validate->required($_POST['product_name'], 'Enter product name.');
	$validate->numeric($_POST['product_price'], 'Please enter a valid number for price.');
	$validate->numeric($_POST['product_quantity'], 'Please enter a valid number for quantity.');
	
	if (isset($_POST['digital_good'])) {
		
		$validate->numeric($_POST['expiry'], 'Please enter a valid number for the product expiration.');
		
		if (empty($_FILES['file']['name']))
			$error->set_error('Please select product file.');
			
	}
		
	if (!empty($_FILES['image']['name'])) {
		
		$image = $upload->upload_image('image', config_item('upload', 'max_width'), config_item('upload', 'max_height'));
		$thumb = $upload->upload_image('image', config_item('upload', 'max_width_thumbnail'), config_item('upload', 'max_height_thumbnail'), config_item('upload', 'crop_thumbnail'));
	
	}
		
	if ($_FILES['file']['name'])
		$file = $upload->upload_file('file');

	if (!$error->has_errors()) {
		
		$quantity = 0;
		
		if (isset($_POST['product_option'])) {
		
			foreach ($_POST['product_option'] as $product_option) {
			
				foreach ($product_option['product_option_value'] as $product_option_values) {
				
					$quantity += $product_option_values['quantity'];
					
				}
				
			}
		
		} else {
		
			$quantity = $_POST['product_quantity'];
		
		}

		$values = array(
			'product_name' 			=> $_POST['product_name'], 
			'product_description' 	=> $_POST['product_description'],
			'product_price' 		=> $_POST['product_price'], 
			'product_quantity' 		=> $quantity,
			'shipping'				=> $_POST['shipping']
		); 			
		
		if (isset($image)) {
			
			$values['product_image'] = $image;
			$values['product_thumbnail'] = $thumb;
		
		}
			
		$db->insert(config_item('cart', 'table_products'), $values);
		
		$product_id = $db->last_insert_id();

		$values = array(
			'product_id'	=> $product_id, 
			'category_id' 	=> $_POST['category_id']
		); 	
				
		$db->insert(config_item('cart', 'table_category_products'), $values);
		
		if (isset($_POST['product_option'])) {
			
			foreach ($_POST['product_option'] as $product_option) {
				
				$values = array(
					'product_id'	=> $product_id, 
					'option_name' 	=> $product_option['name'],
					'position' 		=> $product_option['position']
				);
				
				$db->insert(config_item('cart', 'table_product_options'), $values);
				
				$option_id = $db->last_insert_id();
				
				if (isset($product_option['product_option_value'])) {
					
					foreach ($product_option['product_option_value'] as $product_option_values) {

						$values = array(
							'option_id'			=> $option_id, 
							'product_id'		=> $product_id,
							'option_value'		=> $product_option_values['value'],
							'option_price'		=> $product_option_values['price'],
							'option_quantity'	=> $product_option_values['quantity'],
							'option_type'		=> $product_option_values['type'],
							'position'			=> $product_option_values['position']
						);
						
						$db->insert(config_item('cart', 'table_product_option_values'), $values);
					
					}
						
				}
					
			}
			
		}
							
		if (isset($_POST['digital_good'])) {

			$values = array(
				'product_id'	=> $product_id,
				'date_added'	=> time()
			); 
			
			if ($_FILES['file']['name']) {
				
				$values['display_filename'] = $file;
				$values['filename'] = $file;
				
			}
				
			if ($_POST['expiry_type'] == 'days')
				$values['number_days'] = $_POST['expiry'];
			
			if ($_POST['expiry_type'] == 'downloads')
				$values['number_downloadable'] = $_POST['expiry'];
			
			$db->insert(config_item('cart', 'table_digital_goods'), $values);
			
		}
		
		if (isset($_FILES['product_image'])) {
				
			$files = array();
			
			foreach ($_FILES['product_image'] as $key => $values) {
				
				foreach ($values as $i => $value)
					$files[$i][$key] = $value;

			}

			foreach ($files as $file) {
				
				if (!empty($file['name'])) {
					
					$image = $upload->upload_image($file, config_item('upload', 'max_width'), config_item('upload', 'max_height'));
					$thumb = $upload->upload_image($file, config_item('upload', 'max_width_thumbnail'), config_item('upload', 'max_height_thumbnail'), config_item('upload', 'crop_thumbnail'));

					$values = array(
						'product_id' 	=> $product_id, 
						'image'			=> $image,
						'thumbnail'		=> $thumb
					); 	
							
					$db->insert(config_item('cart', 'table_product_images'), $values);
				
				}
			
			}
			
		}
				
		$tpl->set('success', true);
			
	}
		
}

//Display the template
$tpl->display('admin/add_product');

?>

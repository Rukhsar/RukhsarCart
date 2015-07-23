<?php

//Include the common file
require('../common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_admin()) header("Location: login.php");

//Returns the number of rows
$row_count = $db->row_count("SELECT product_id FROM " . config_item('cart', 'table_products') . " WHERE product_id = '" . $_GET['product_id'] . "'");
//Product details
$product_details = array();

foreach ($db->query("SELECT * FROM " . config_item('cart', 'table_products') . " p, " . config_item('cart', 'table_category_products') . " cp WHERE p.product_id = cp.product_id AND p.product_id = '" . $_GET['product_id'] . "'") as $row)
	$product_details[] = $row;

//Digital goods
$digital_goods = $db->fetch_row_assoc("SELECT * FROM " . config_item('cart', 'table_digital_goods') . " WHERE product_id = '" . $_GET['product_id'] . "'");
	
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

	$validate->required($_POST['product_name'], 'Enter product name.');
	$validate->numeric($_POST['product_price'], 'Please enter a valid number for price.');
	$validate->numeric($_POST['product_quantity'], 'Please enter a valid number for quantity.');

	if (isset($_POST['digital_good'])) {
		
		$validate->numeric($_POST['expiry'], 'Please enter a valid number for the product expiration.');
		
		if ($db->row_count("SELECT digital_good_id FROM " . config_item('cart', 'table_digital_goods') . " WHERE product_id = '" . $_GET['product_id'] . "'") == 0) {
			
			if (empty($_FILES['file']['name']))
				$error->set_error('Please select product file.');
				
		}
			
	}
	
	if ($_FILES['image']['name']) {
		
		$result = $db->fetch_row_assoc("SELECT product_image, product_thumbnail FROM " . config_item('cart', 'table_products') . " WHERE product_id = '" . $_POST['product_id'] . "'");
		
		if ($result['product_image']) {
			
			@unlink(config_item('upload', 'upload_path') . 'images/' . $result['product_image']);
			@unlink(config_item('upload', 'upload_path') . 'images/' . $result['product_thumbnail']);
			
		}
		
		$image = $upload->upload_image('image', config_item('upload', 'max_width'), config_item('upload', 'max_height'));
		$thumb = $upload->upload_image('image', config_item('upload', 'max_width_thumbnail'), config_item('upload', 'max_height_thumbnail'), config_item('upload', 'crop_thumbnail'));
		
	}

	if ($_FILES['file']['name']) {
		
		$result = $db->fetch_row_assoc("SELECT filename FROM " . config_item('cart', 'table_digital_goods') . " WHERE product_id = '" . $_POST['product_id'] . "'");
		
		if ($result['filename'])
			@unlink(config_item('upload', 'upload_path') . 'files/' . $result['filename']);
		
		$file = $upload->upload_file('file');
		
	}
			
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
			'shipping' 				=> $_POST['shipping']
		); 			

		if (isset($image)) {
			
			$values['product_image'] = $image;
			$values['product_thumbnail'] = $thumb;
		
		}
					
		$where = array(
			'product_id' => $_POST['product_id']
		);
		
		$db->where($where);
		$db->update(config_item('cart', 'table_products'), $values);

		$where = array(
			'product_id' => $_POST['product_id']
		);
		
		$db->where($where);		
		$db->delete(config_item('cart', 'table_category_products'));

		$values = array(
			'product_id'	=> $_POST['product_id'], 
			'category_id' 	=> $_POST['category_id']
		); 	
				
		$db->insert(config_item('cart', 'table_category_products'), $values);

		$where = array(
			'product_id' => $_POST['product_id']
		);
		
		$db->where($where);			
		$db->delete(config_item('cart', 'table_product_options'));
		$db->delete(config_item('cart', 'table_product_option_values'));

		if (isset($_POST['product_option'])) {
			
			foreach ($_POST['product_option'] as $product_option) {
				
				$values = array(
					'product_id'	=> $_POST['product_id'], 
					'option_name' 	=> $product_option['name'],
					'position' 		=> $product_option['position']
				);
				
				$db->insert(config_item('cart', 'table_product_options'), $values);
				
				$option_id = $db->last_insert_id();
				
				if (isset($product_option['product_option_value'])) {
					
					foreach ($product_option['product_option_value'] as $product_option_values) {

						$values = array(
							'option_id'			=> $option_id, 
							'product_id'		=> $_POST['product_id'],
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

			if ($db->row_count("SELECT digital_good_id FROM " . config_item('cart', 'table_digital_goods') . " WHERE product_id = '" . $_POST['product_id'] . "'") > 0) {

				$result = $db->fetch_row_assoc("SELECT digital_good_id, display_filename, filename, date_added FROM " . config_item('cart', 'table_digital_goods') . " WHERE product_id = '" . $_POST['product_id'] . "'");

				$values = array(
					'product_id'	=> $_POST['product_id'],
					'date_added'	=> $result['date_added']
				); 
				
				if ($_FILES['file']['name']) {
					
					if ($result['filename'])
						@unlink(config_item('upload', 'upload_path') . 'files/' . $result['filename']);
					
					$values['display_filename'] = $file;
					$values['filename'] = $file;
					
				} else {
					
					$values['display_filename'] = $result['display_filename'];
					$values['filename'] = $result['filename'];
					
				}
					
				if ($_POST['expiry_type'] == 'days')
					$values['number_days'] = $_POST['expiry'];
				
				if ($_POST['expiry_type'] == 'downloads')
					$values['number_downloadable'] = $_POST['expiry'];
				
				$db->insert(config_item('cart', 'table_digital_goods'), $values);

				$where = array(
					'digital_good_id' => $result['digital_good_id']
				);
				
				$db->where($where);										
				$db->delete(config_item('cart', 'table_digital_goods'));	
			
			} else {
				
				$values = array(
					'product_id'	=> $_POST['product_id'],
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
			
		} else {
			
			if ($db->row_count("SELECT digital_good_id FROM " . config_item('cart', 'table_digital_goods') . " WHERE product_id = '" . $_POST['product_id'] . "'") > 0) {

				$result = $db->fetch_row_assoc("SELECT filename FROM " . config_item('cart', 'table_digital_goods') . " WHERE product_id = '" . $_POST['product_id'] . "'");
				
				if ($result['filename'])
					@unlink(config_item('upload', 'upload_path') . 'files/' . $result['filename']);

				$where = array(
					'product_id' => $_POST['product_id']
				);
				
				$db->where($where);							
				$db->delete(config_item('cart', 'table_digital_goods'));
			
			}	
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
						'product_id' 	=> $_POST['product_id'], 
						'image'			=> $image,
						'thumbnail'		=> $thumb
					); 	
							
					$db->insert(config_item('cart', 'table_product_images'), $values);
				
				}
			
			}
			
		}
					
		$session->set('success', true);
		
		header('Location: products.php');
						
	}
	
}

//Check download
if (isset($_GET['download'])) {

	$result = $db->fetch_row_assoc("SELECT filename, display_filename FROM " . config_item('cart', 'table_digital_goods') . " WHERE digital_good_id = '" . $_GET['digital_good_id'] . "'");
	
	if (!headers_sent()) {
		
		if (file_exists(config_item('upload', 'upload_path') . 'files/' . $result['filename'])) {
			
			@ini_set('zlib.output_compression', 'Off');
			
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . $result['display_filename']);
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize(config_item('upload', 'upload_path') . 'files/' . $result['filename']));

			$file = readfile(config_item('upload', 'upload_path') . 'files/' . $result['filename'], 'rb');
		
			print($file);
			
		} else {
			
			exit('Error: Could not find file.');
			
		}
		
	} else {
		
		exit('Error: Headers already sent out.');
		
	}
			
}

//Check if the form has been submitted
if (isset($_GET['delete_image']) && isset($_GET['image_id'])) {

	$result = $db->fetch_row_assoc("SELECT image, thumbnail FROM " . config_item('cart', 'table_product_images') . " WHERE image_id = '" . $_GET['image_id'] . "'");
	
	if ($result['image'])
		@unlink(config_item('upload', 'upload_path') . 'images/' . $result['image']);
	
	if ($result['thumbnail'])
		@unlink(config_item('upload', 'upload_path') . 'images/' . $result['thumbnail']);
						
	$where = array(
		'image_id' => $_GET['image_id']
	);
	
	$db->where($where);
	$db->delete(config_item('cart', 'table_product_images'));
	
	header("Location: edit_product.php?product_id=" . $_GET['product_id']);
	
}

//Template values
$tpl->set('row_count', $row_count);
$tpl->set('product_details', $product_details);
$tpl->set('digital_goods', $digital_goods);

//Display the template
$tpl->display('admin/edit_product');

?>

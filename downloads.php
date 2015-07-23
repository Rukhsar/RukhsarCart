<?php

//Include the common file
require_once('common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_group('customer')) header("Location: login.php");

//Returns the number of rows
$row_count = $db->row_count("SELECT * FROM " . config_item('cart', 'table_order_digital_goods') . " odg, " . config_item('cart', 'table_orders') . " o WHERE o.user_id = '" . $session->get('user_id') . "' AND odg.order_id = o.order_id");

//Digital goods
$digital_goods = array();

foreach ($db->query("SELECT odg.order_digital_good_id, odg.date_added, odg.downloads, odg.number_downloadable, odg.date_expiration, odg.download_hash, o.order_id, p.product_name, osd.invoice FROM " . config_item('cart', 'table_order_digital_goods') . " odg, " . config_item('cart', 'table_orders') . " o, " . config_item('cart', 'table_products') . " p, " . config_item('cart', 'table_order_status_descriptions') . " osd WHERE o.user_id = '" . $session->get('user_id') . "' AND odg.order_id = o.order_id AND odg.product_id = p.product_id AND o.order_status_description_id = osd.order_status_description_id ORDER BY odg.order_id DESC") as $row)
	$digital_goods[] = $row;

//Check download
if (isset($_GET['download']) && !empty($_GET['download_hash'])) {

	if ($db->row_count("SELECT * FROM " . config_item('cart', 'table_order_digital_goods') . " WHERE order_digital_good_id = '" . $_GET['order_digital_good_id'] . "' AND download_hash = '" . $_GET['download_hash'] . "'")) {

		$result = $db->fetch_row_assoc("SELECT * FROM " . config_item('cart', 'table_order_digital_goods') . " WHERE order_digital_good_id = '" . $_GET['order_digital_good_id'] . "' AND download_hash = '" . $_GET['download_hash'] . "'");
		
		if ($result['number_downloadable'] > 0 && $result['date_expiration'] == 0) {
		
			if ($result['number_downloadable'] == $result['downloads']) {
				
				header("Location: downloads.php"); 
				
				exit();
			
			}	
		}
		
		if ($result['date_expiration'] > 0 && $result['number_downloadable'] == 0) {
			
			if ($result['date_expiration'] < time()) {
				
				header("Location: downloads.php"); 
				
				exit();
			
			}
			
		}

		$values = array(
			'downloads' => ($result['downloads'] + 1)
		); 	
			
		$where = array(
			'order_digital_good_id' => $_GET['order_digital_good_id']
		);
		
		$db->where($where);
		$db->update(config_item('cart', 'table_order_digital_goods'), $values);

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
	
}

//Template values
$tpl->set('row_count', $row_count);
$tpl->set('digital_goods', $digital_goods);

//Display the template
$tpl->display('downloads');
			
?>

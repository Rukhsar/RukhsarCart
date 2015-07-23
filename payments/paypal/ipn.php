<?php

//Include the common file
require_once('../../common.php');

//Initialize object
$paypal = new Paypal();

//Validate ipn
$paypal->validate_ipn($_POST);

//Check ipn transaction
if (!$paypal->is_verified()) {
	
	$paypal->log_response();
	die();
	
}

//Switch action
switch ($paypal->get_payment_status()) {

	case 'Pending':
	
		$paypal->log_payment("Pending Payment");
		
	break;

	case 'Completed':
	
		$paypal->log_payment("Completed Payment");

		$values = array(
			'order_status_description_id' => '2'
		); 	
			
		$where = array(
			'order_id' => $paypal->get_order_id()
		);
		
		$db->where($where);
		$db->update(config_item('cart', 'table_orders'), $values);

		foreach ($db->query("SELECT order_digital_good_id, number_days FROM " . config_item('cart', 'table_order_digital_goods') . " WHERE order_id = '" . $paypal->get_order_id() . "'") as $row) {
			
			if ($row['number_days'] > 0) {
			
				$values = array(
					'date_expiration' => time() + ($row['number_days'] * 3600 * 24)
				);
								
			} else {
				
				$values = array(
					'date_expiration' => 0
				);
					
			}

			$where = array(
				'order_id'				=> $paypal->get_order_id(),
				'order_digital_good_id'	=> $row['order_digital_good_id']
			);
	
			$db->where($where);
			$db->update(config_item('cart', 'table_order_digital_goods'), $values);
							
		}
				
		$values = array(
			'order_id' 						=> $paypal->get_order_id(),
			'order_status_description_id'	=> '2',
			'date_added'           			=> time()
		);
					
		$db->insert(config_item('cart', 'table_order_status'), $values);
			
	break;

	case 'Failed':
	
		$paypal->log_payment("Failed Payment");
		
		$values = array(
			'order_status_description_id' => '7'
		); 	
			
		$where = array(
			'order_id' => $paypal->get_order_id()
		);
		
		$db->where($where);
		$db->update(config_item('cart', 'table_orders'), $values);

		$values = array(
			'order_id' 						=> $paypal->get_order_id(),
			'order_status_description_id'	=> '7',
			'date_added'            		=> time()
		);
					
		$db->insert(config_item('cart', 'table_order_status'), $values);
						
	break;

	case 'Denied':
	
		$paypal->log_payment("Denied Payment");

		$values = array(
			'order_status_description_id' => '6'
		); 	
			
		$where = array(
			'order_id' => $paypal->get_order_id()
		);
		
		$db->where($where);
		$db->update(config_item('cart', 'table_orders'), $values);

		$values = array(
			'order_id'						=> $paypal->get_order_id(),
			'order_status_description_id'	=> '6',
			'date_added'            		=> time()
		);
					
		$db->insert(config_item('cart', 'table_order_status'), $values);
				
	break;

	case 'Refunded':
	
		$paypal->log_payment("Refunded Payment");

		$values = array(
			'order_status_description_id' => '8'
		); 	
			
		$where = array(
			'order_id' => $paypal->get_order_id()
		);
		
		$db->where($where);
		$db->update(config_item('cart', 'table_orders'), $values);

		$values = array(
			'order_id'						=> $paypal->get_order_id(),
			'order_status_description_id'	=> '8',
			'date_added'            		=> time()
		);
					
		$db->insert(config_item('cart', 'table_order_status'), $values);
				
	break;

	default:
	
		$paypal->log_payment("Unknown Payment Status");
		
	break;
	
}

?>

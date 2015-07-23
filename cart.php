<?php

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {

	//Include the common file
	require('common.php');
	
	//Switch action
	if (isset($_GET['action'])) {
		
		switch ($_GET['action']) {

			case 'add_product':
				
				if (!empty($_POST['option']))
					$options = $_POST['option'];
				else
					$options = NULL;
					
				$cart->add_product($_POST['product_id'], $options);
				
			break;
			
			case 'get_cart':
				
				require_once('display_cart.php');
				
			break;
			
			case 'show_errors':
				
				require_once('errors.php');
				
			break;
			
		}
	}

} else {
	
	exit('No direct access allowed.');
	
}
?>

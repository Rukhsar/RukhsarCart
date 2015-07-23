<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.'); ?>
				
<?php if (count($cart->get_cart()) > 0): ?>

	<?php
	
		$quantity = 0;
		
		foreach ($cart->get_cart() as $value)
			$quantity += $value['product_quantity'];
			
	?>
	
	<p>Cart (<?php echo $quantity; ?> / <?php echo price($cart->subtotal()); ?>)</p>
			
<?php else: ?>

	<p>Cart (- / -)</p>
<?php endif; ?>

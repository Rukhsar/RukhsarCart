<?php require_once('header' . config_item('template', 'template_extension')); ?>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
		
		<!-- BEGIN .grid_12 -->
		<div class="grid_12 title_background">
			<h1>My order history</h1>
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
						
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">

			<?php if ($row_count > 0): ?>
			
				<table class="table">
					<thead>
						<tr>
							<th>Order id</th>
							<th>Date added</th>
							<th>Customer</th>
							<th>Status</th>
							<th>Total</th>
							<th>Action</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
				
				<?php foreach ($orders as $row): ?>

					<tr>
						<td># <?php echo $row['order_id']; ?></td>
						<td><?php echo date('d/m/Y H.i.s', $row['date_added']); ?></td>
						<td><?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?></td>
						<td><?php echo $row['status_name']; ?></td>
						<td><?php echo price($row['total'], $row['currency']); ?></td>
						<td><a href="order_details.php?order_id=<?php echo $row['order_id']; ?>"><img src="<?php echo config_item('template', 'site_url'); ?>templates/images/details.png" alt="" /></a></td>
						<td>
							<?php if ($row['status_name'] == 'Pending' && $row['payment_method'] == 'PayPal'): ?>
								<form action="https://www.paypal.com/cgi-bin/webscr" method="post"> 
								 
									<!-- Specify a Buy Now button. --> 
									<input type="hidden" name="cmd" value="_cart">
									<input type="hidden" name="upload" value="1">

									<!-- Identify your business so that you can collect the payments. --> 
									<input type="hidden" name="business" value="<?php echo config_item('cart', 'paypal_email'); ?>">
									<input type="hidden" name="custom" value="<?php echo $row['order_id']; ?>">
									 
									<input type="hidden" name="currency_code" value="<?php echo config_item('cart', 'currency_code'); ?>">
									<input type="hidden" name="return" value="<?php echo config_item('cart', 'paypal_return'); ?>">
									<input type="hidden" name="cancel_return" value="<?php echo config_item('cart', 'paypal_cancel_return'); ?>">
									<input type="hidden" name="notify_url" value="<?php echo config_item('cart', 'paypal_notify_url'); ?>">
									<input type="hidden" name="discount_amount_cart" value="<?php echo $row['coupon_discount']; ?>">
									
									<?php if ($row['shipping_cost'] != 0): ?>
										<input type="hidden" name="shipping_1" value="<?php echo $row['shipping_cost']; ?>">
									<?php endif; ?>
																		 
									<!-- Specify details about the item that buyers will purchase. -->
									<?php $count = 1; $i = 0; ?>
									
									<?php foreach ($row['order_products'] as $product): ?>
										
										<?php foreach ($product['options'] as $option): ?>
											
											<input type="hidden" name="on<?php echo $i; ?>_<?php echo $count; ?>" value="<?php echo $option['option_name']; ?>">
											<input type="hidden" name="os<?php echo $i; ?>_<?php echo $count; ?>" value="<?php echo $option['option_value']; ?>">
											
										<?php $i++; endforeach; ?>
										
										<input type="hidden" name="tax_cart" value="<?php echo $product['tax_cart']; ?>">
										<input type="hidden" name="item_number_<?php echo $count; ?>" value="<?php echo $product['product_id']; ?>">
										<input type="hidden" name="item_name_<?php echo $count; ?>" value="<?php echo $product['product_name']; ?>">
										<input type="hidden" name="amount_<?php echo $count; ?>" value="<?php echo $product['product_price']; ?>">
										<input type="hidden" name="quantity_<?php echo $count; ?>" value="<?php echo $product['product_quantity']; ?>">

									<?php $count++; endforeach; ?>
									
									<!-- Display the payment button. -->
									<button type="submit" name="submit" class="button orange">Pay</button>
								
								</form>
							<?php endif; ?>
						</td>
					</tr>
					
				<?php endforeach; ?>
				
					</tbody>
				</table>

			<?php else: ?>

				<div class="alert info">
					<p>You have not made any previous orders.</p>
				</div>
									
			<?php endif; ?>
																		
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
				
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

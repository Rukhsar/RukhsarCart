<?php require_once('header' . config_item('template', 'template_extension')); ?>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
		
		<!-- BEGIN .grid_12 -->
		<div class="grid_12 title_background">
			<h1>Shopping cart summary</h1>
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">
			
			<?php if (count($cart->get_cart()) > 0): ?>

				<form id="update_cart" method="post" action="">

					<?php if ($error->has_errors()): ?>
					
						<div class="alert error">
							<span>
								<strong>An error occurred while processing request</strong>
							</span>
							<?php foreach ($error->display_errors() as $value): ?>		
								<p><?php echo $value; ?></p>
							<?php endforeach; ?>
						</div>
					
					<?php $error->clear_errors(); endif; ?>
			
					<table class="table">
						<thead>
							<tr>
								<th>Product</th>
								<th>Quantity</th>
								<th>Unit price</th>
								<th>Total</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>

						<?php foreach ($cart->get_cart() as $value): ?>

							<tr>
								<td>
									<a href="product_details.php?product_id=<?php echo $value['product_id']; ?>" title="<?php echo $value['product_name']; ?>"><?php echo $value['product_name']; ?></a>
									<br />
									<?php foreach ($value['options'] as $option): ?>
										<?php if (isset($option['option_value'])): ?>
											- <?php echo $option['option_value']; ?>
										<?php endif; ?>
									<?php endforeach; ?>
								</td>
								<td>
									<input type="hidden" name="cart_id[]" value="<?php echo $value['cart_id']; ?>" />
									<input type="hidden" name="product_name[]" value="<?php echo $value['product_name']; ?>" />
									<input type="hidden" name="product_id[]" value="<?php echo $value['product_id']; ?>" />
									<input type="text" name="quantity[]" size="5" value="<?php echo $value['product_quantity']; ?>" />
								</td>
								<td><?php echo price($value['product_price']); ?></td>
								<td><?php echo price($value['total_price']); ?></td>
								<td>
									<a href="?cart_id=<?php echo $value['cart_id']; ?>&amp;remove_item=<?php echo $value['product_id']; ?>">
										<img src="<?php echo config_item('template', 'site_url'); ?>templates/images/cross.png" alt="" />
									</a>
								</td>
							</tr>

						<?php endforeach; ?>
						
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td><strong>Sub-Total:</strong></td>
								<td><?php echo price($subtotal); ?></td>
								<td></td>
							</tr>
							<?php if (config_item('cart', 'shipping_cost')): ?>
								<tr>
									<td></td>
									<td></td>
									<td><strong>Shipping cost:</strong></td>
									<td><?php echo price($shipping_cost); ?></td>
									<td></td>
								</tr>
							<?php endif; ?>
							<?php if (config_item('cart', 'tax_rate')): ?>
								<tr>
									<td></td>
									<td></td>
									<td>
										<strong><?php echo config_item('cart', 'tax_description'); ?> <?php echo config_item('cart', 'tax_rate'); ?> %:</strong>
									</td>
									<td><?php echo price($tax_rate); ?></td>
									<td></td>
								</tr>
							<?php endif; ?>
							<?php if ($session->get('coupon_id')): ?>
								<tr>
									<td></td>
									<td></td>
									<td>
										<strong>
											<?php 
													
												foreach ($cart->get_coupon($session->get('coupon_id')) as $value)
													echo $value['coupon_name'];

											?>
										</strong>
									</td>
									<td>
										<?php
										
											foreach ($cart->get_coupon($session->get('coupon_id')) as $value)
												echo '- ' . price($value['coupon_discount']);
											
										?>
									</td>
									<td></td>
								</tr>
							<?php endif; ?>
							<tr>
								<td></td>
								<td></td>
								<td><strong>Total:</strong></td>
								<td><?php echo price($total); ?></td>
								<td></td>
							</tr>
						</tbody>
					</table>
					
					<input type="text" name="coupon_code" />&nbsp;
					<button type="submit" name="coupon" class="button orange">Apply coupon</button>
					
					<button type="submit" name="update_cart" class="button orange">Update</button>
					<button type="submit" name="empty_cart" class="button orange">Empty</button>
					<button type="submit" name="checkout" class="button orange">Checkout</button>
					
				</form>
										
			<?php else: ?>
			
				<div class="alert info">
					<p>Your shopping cart is empty.</p>
				</div>

			<?php endif; ?>	
													
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
				
	</div>
	<!-- END #main -->
		
<?php require_once('footer' . config_item('template', 'template_extension')); ?>

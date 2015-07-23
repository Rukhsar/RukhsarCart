<?php require_once('header' . config_item('template', 'template_extension')); ?>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
		
		<!-- BEGIN .grid_12 -->
		<div class="grid_12 title_background">
			<h1>Checkout</h1>
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">
			
			<?php if (count($cart->get_cart()) > 0): ?>

				<?php foreach ($customer_addresses as $row): ?>

					<table class="table">
						<thead>
							<tr>
								<th>Shipping address</th>
								<th>Payment address</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>					
									<p><?php echo $row['first_name']; ?></p>
									<p><?php echo $row['last_name']; ?></p>
									<p><?php echo $row['company']; ?></p>
									<p><?php echo $row['address']; ?></p>
									<p><?php echo $row['city']; ?></p>
									<p><?php echo $row['post_code']; ?></p>
									<?php foreach ($countries as $value): ?>
										<?php if ($value['country_id'] == $row['country_id']): ?>
											<p><?php echo $value['country_name']; ?></p>
										<?php endif; ?>
									<?php endforeach; ?>
									<p><?php echo $row['zone']; ?></p>
								</td>
								<td>
									<p><?php echo $row['first_name']; ?></p>
									<p><?php echo $row['last_name']; ?></p>
									<p><?php echo $row['company']; ?></p>
									<p><?php echo $row['address']; ?></p>
									<p><?php echo $row['city']; ?></p>
									<p><?php echo $row['post_code']; ?></p>
									<?php foreach ($countries as $value): ?>
										<?php if ($value['country_id'] == $row['country_id']): ?>
											<p><?php echo $value['country_name']; ?></p>
										<?php endif; ?>
									<?php endforeach; ?>
									<p><?php echo $row['zone']; ?></p>								
								</td>
							</tr>
						</tbody>
					</table>
						
				<?php endforeach; ?>

				<form method="post" action="">

					<table class="table">
						<thead>
							<tr>
								<th style="width: 100px;">Payment method</th>
								<th style="width: 100px;">&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<p>PayPal</p>
								</td>
								<td>
									<p><input type="radio" name="payment_method" value="PayPal" checked="checked" /></p>
								</td>
							</tr>
							<tr>
								<td>
									<p>Bank transfer</p>
								</td>
								<td>
									<p><input type="radio" name="payment_method" value="Bank transfer" /></p>
								</td>
							</tr>
							<tr>
								<td>
									<p>Cash on delivery</p>
								</td>
								<td>
									<p><input type="radio" name="payment_method" value="Cash on delivery" /></p>
								</td>
							</tr>
						</tbody>
					</table>
					
					<h6>Add comment about your order</h6>
					<p><textarea name="comment" rows="8" cols="90"></textarea></p>
						
					<p><button type="submit" name="confirm_order" class="button orange">Confirm order</button></p>
					
				</form>

			<?php else: ?>

				<div class="alert info">
					<p>Cart empty.</p>
				</div>
			
			<?php endif; ?>
																																		
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
				
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

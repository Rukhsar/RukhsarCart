<?php require_once('header' . config_item('template', 'template_extension')); ?>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
							
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">

			<!-- BEGIN .title_background -->
			<div class="title_background">
				<h1>Order details</h1>
			</div>
			<!-- END .title_background -->
			
			<div class="clear">&nbsp;</div>
			
			<?php if (isset($_GET['order_id']) && $row_count > 0): ?>
			
				<?php foreach ($order_details as $row): ?>
					
					<table class="table">
						<thead>
							<tr>
								<th>Shipping address</th>
								<th>Payment address</th>
								<th style="width: 130px;">Shipping method</th>
								<th style="width: 130px;">Payment method</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<p><?php echo $row['shipping_first_name']; ?> <?php echo $row['shipping_last_name']; ?></p>
									<p><?php if ($row['shipping_company']) echo $row['shipping_company'] ?></p>
									<p><?php echo $row['shipping_address']; ?></p>
									<p><?php echo $row['shipping_city']; ?></p>
									<p><?php echo $row['shipping_post_code']; ?></p>
									<p>
										<?php foreach ($countries as $value): ?>
											<?php if ($value['country_id'] == $row['shipping_country_id']): ?>
												<?php echo $value['country_name']; ?>
											<?php endif; ?>
										<?php endforeach; ?>
									</p>
									<p><?php echo $row['shipping_zone']; ?></p>
								</td>
								<td>
									<p><?php echo $row['payment_first_name']; ?> <?php echo $row['payment_last_name']; ?></p>
									<p><?php if ($row['payment_company']) echo $row['payment_company']; ?></p>
									<p><?php echo $row['payment_address']; ?></p>
									<p><?php echo $row['payment_city']; ?></p>
									<p><?php echo $row['payment_post_code']; ?></p>
									<p>
										<?php foreach ($countries as $value): ?>
											<?php if ($value['country_id'] == $row['payment_country_id']): ?>
												<?php echo $value['country_name']; ?>
											<?php endif; ?>
										<?php endforeach; ?>
									</p>
									<p><?php echo $row['payment_zone']; ?></p>					
								</td>
								<td><p><?php echo $row['shipping_method']; ?></p></td>
								<td><p><?php echo $row['payment_method']; ?></p></td>
							</tr>
						</tbody>
					</table>
					
				<?php endforeach; ?>
				
				<table class="table">
					<thead>
						<tr>
							<th>Product</th>
							<th>Quantity</th>
							<th>Price</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						
						<?php foreach ($order_details as $row): ?>
						
							<?php foreach ($row['order_products'] as $product): ?>
								<tr>
									<td>
										<?php echo $product['product_name']; ?> <br />
										<?php if (isset($product['options'])): ?>
											<?php foreach ($product['options'] as $option): ?>
												- <?php echo $option['option_value']; ?>
											<?php endforeach; ?>
										<?php endif; ?>
									</td>
									<td><?php echo $product['product_quantity']; ?></td>
									<td><?php echo price($product['product_price'], $product['currency']); ?></td>
									<td><?php echo price($product['total_price'], $product['currency']); ?></td>
								</tr>
							<?php endforeach; ?>

							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td><strong>Sub-Total:</strong></td>
								<td><?php echo price($row['subtotal'], $row['currency']); ?></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td><strong>Shipping cost:</strong></td>
								<td><?php echo price($row['shipping_cost'], $row['currency']); ?></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td><strong><?php echo $row['tax_description']; ?> <?php echo $row['tax_rate']; ?> %:</strong></td>
								<td><?php echo price($row['total_tax'], $row['currency']); ?></td>
							</tr>
							<?php if ($row['coupon_discount'] != 0): ?>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><strong><?php echo $row['coupon_name']; ?></strong></td>
									<td><?php echo '- ' . price($row['coupon_discount'], $row['currency']); ?></td>
								</tr>
							<?php endif; ?>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td><strong>Total:</strong></td>
								<td><?php echo price($row['total'], $row['currency']); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<table class="table">
					<thead>
						<tr>
							<th>Date added</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>

						<?php foreach ($order_status as $row): ?>				
							<tr>
								<td><p><?php echo date('d/m/Y H.i.s', $row['date_added']); ?></p></td>
								<td><p><?php echo $row['status_name']; ?></p></td>
							</tr>
						<?php endforeach; ?>
											
					</tbody>
				</table>

				<form method="post" action="">
				
					<table class="table">
						<thead>
							<tr>
								<th>Order status</th>
								<th><input type="hidden" name="order_id" value="<?php echo $_GET['order_id']; ?>" /></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<select name="order_status_description_id">
										<option value="1">Pending</option>
										<option value="2">Completed</option>
										<option value="3">Processing</option>
										<option value="4">Shipped</option>
										<option value="5">Canceled</option>
										<option value="6">Denied</option>
										<option value="7">Failed</option>
										<option value="8">Refunded</option>
									</select>
								</td>
								<td>
									<button type="submit" name="submit" class="button orange">Add status</button>
								</td>
							</tr>
						</tbody>
					</table>
					
				</form>
				
				<?php foreach ($order_details as $row): ?>
					<?php if ($row['comment']): ?>
						<table class="table">
							<thead>
								<tr>
									<th>Comment</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo $row['comment']; ?></td>
								</tr>
							</tbody>
						</table>
					<?php endif; ?>
				<?php endforeach; ?>
				
			<?php else: ?>

				<div class="alert info">
					<p>Order not found.</p>
				</div>
		
			<?php endif; ?>
															
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

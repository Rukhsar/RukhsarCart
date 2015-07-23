<?php require_once('header' . config_item('template', 'template_extension')); ?>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
							
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">

			<!-- BEGIN .title_background -->
			<div class="title_background">
				<h1>Manage orders</h1>
			</div>
			<!-- END .title_background -->
			
			<div class="clear">&nbsp;</div>
			
			<?php if ($session->get('success')): ?>
				<div class="alert success">
					<p>
						<span>Order updated successfully!</span>
					</p>
				</div>
			<?php $session->delete('success'); endif; ?>
			
			<?php if ($row_count > 0): ?>
				
				<form action="" method="post">

					<table class="table">
						<thead>
							<tr>
								<th>Filter by order id</th>
								<th>Filter by customer</th>
								<th>Filter by status</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							
							<tr>
								<td><input type="text" name="filter_order_id" size="50" /></td>
								<td><input type="text" name="filter_name" size="50" /></td>
								<td>
									<select name="order_status_description_id">
										<option value="">All</option>
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
								<td><button type="submit" name="filter" class="button orange">Filter</button></td>
							</tr>
															
						</tbody>
					</table>
																																			
					<table class="table">
						<thead>
							<tr>
								<th>Order id</th>
								<th>Customer</th>
								<th>Status</th>
								<th>Total</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							
							<?php foreach ($orders as $row): ?>
								
								<tr>
									<td># <?php echo $row['order_id']; ?></td>
									<td><?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?></td>
									<td><?php echo $row['status_name']; ?></td>
									<td><?php echo price($row['total'], $row['currency']); ?></td>
									<td><a href="order_details.php?order_id=<?php echo $row['order_id']; ?>"><img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/details.png" alt="" /></a></td>
								</tr>
																	
							<?php endforeach; ?>
							
						</tbody>
					</table>

					<div class="grid_5 alpha omega">
							
						<?php

							if($current_page > 1 && ($current_page - 1) < $pages)
								echo '<a href="?page=' . ($current_page - 1) . '" class="button orange">&laquo; Previous page</a>';
							if($pages > $current_page && ($current_page - 1) < $pages)
								echo '<a href="?page=' . ($current_page + 1) . '" class="button orange">Next page &raquo;</a>';

						?>
						
					</div>
					
					<div class="clear">&nbsp;</div>
			
				</form>
				
			<?php else: ?>
			
				<div class="alert info">
					<p>Orders not found.</p>
				</div>
				
			<?php endif; ?>
													
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

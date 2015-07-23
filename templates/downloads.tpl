<?php require_once('header' . config_item('template', 'template_extension')); ?>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">

		<!-- BEGIN .grid_12 -->
		<div class="grid_12 title_background">
			<h1>Downloads</h1>
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
							<th>Name</th>
							<th>Date added</th>
							<th>File downloads</th>
							<th>Expires after</th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						
					<?php foreach ($digital_goods as $row): ?>
				
						<tr>
							<td>#<?php echo $row['order_id']; ?></td>
							<td><?php echo $row['product_name']; ?></td>
							<td><?php echo date('d/m/Y H.i.s', $row['date_added']); ?></td>
							<td><?php echo $row['downloads']; ?></td>
							
							<?php if ($row['invoice']): ?>
							
								<?php if ($row['number_downloadable'] > 0 && $row['date_expiration'] == 0): ?>
									<td><?php echo $row['number_downloadable']; ?> downloads</td>
									<td><a href="downloads.php?download&order_digital_good_id=<?php echo $row['order_digital_good_id']; ?>&download_hash=<?php echo $row['download_hash']; ?>"><img src="<?php echo config_item('template', 'site_url'); ?>templates/images/download.png" alt="Download" /></a></td>
								<?php elseif ($row['date_expiration'] > 0 && $row['number_downloadable'] == 0): ?>
									<td><?php echo date('d/m/Y H.i.s', $row['date_expiration']); ?></td>
									<td><a href="downloads.php?download&order_digital_good_id=<?php echo $row['order_digital_good_id']; ?>&download_hash=<?php echo $row['download_hash']; ?>"><img src="<?php echo config_item('template', 'site_url'); ?>templates/images/download.png" alt="Download" /></a></td>
								<?php else: ?>
									<td>--</td>
									<td>--</td>
								<?php endif; ?>
								
							<?php else: ?>
							
								<td>--</td>
								<td>--</td>
							
							<?php endif; ?>
							
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

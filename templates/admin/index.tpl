<?php require_once('header' . config_item('template', 'template_extension')); ?>

<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {

	var s1 = [];
	var ticks = [];
	
	<?php foreach ($top_sellers as $row): ?>
				
		s1.push(<?php echo $row['total']; ?>);
		ticks.push('<?php echo $row['product_name']; ?>');
		
	<?php endforeach; ?>  
	
	plot1 = $.jqplot('top5', [s1], {
		seriesDefaults:{
			renderer:$.jqplot.BarRenderer,
			rendererOptions: {
				fillToZero: true, 
				barWidth: 100
			},
			pointLabels: {show: true}
		},
		title: 'Top 5 Sellers',
		axes: {
			xaxis: {
				renderer: $.jqplot.CategoryAxisRenderer,
				ticks: ticks
			},
			yaxis: {
				autoscale: false
			}
		}
	});
					
});
// ]]>
</script>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">

		<!-- BEGIN .grid_12 -->
		<div class="grid_12">

			<!-- BEGIN .title_background -->
			<div class="title_background">
				<h1>Latest 5 orders &amp; Top 5 product</h1>
			</div>
			<!-- END .title_background -->
			
			<?php if ($row_count > 0): ?>
				
				<table class="table">
					<thead>
						<tr>
							<th>Order id</th>
							<th>Date added</th>
							<th>Customer</th>
							<th>Status</th>
							<th>Total</th>
							<th>Details</th>
						</tr>
					</thead>
					<tbody>
										
					<?php foreach($latest_orders as $row): ?>
									
						<tr>
							<td># <?php echo $row['order_id']; ?></td>
							<td><?php echo date('d/m/Y', $row['date_added']); ?></td>
							<td><?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?></td>
							<td><?php echo $row['status_name']; ?></td>
							<td><?php echo price($row['total'], $row['currency']); ?></td>
							<td><a href="order_details.php?order_id=<?php echo $row['order_id']; ?>" title="View details"><img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/details.png" alt="" /></a></td>
						</tr>
					
					<?php endforeach; ?>
					
					</tbody>
				</table>

			<?php else: ?>
			
				<div class="alert info">
					<p>Orders not found.</p>
				</div>
				
			<?php endif; ?>
			
			<div id="top5"></div>
													
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

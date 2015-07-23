<?php require_once('header' . config_item('template', 'template_extension')); ?>

<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
	
	$('#dialog:ui-dialog').dialog('destroy');

	$('#dialog_confirm').dialog({
		autoOpen: false,
		resizable: false,
		height: 180,
		width: 450,
		modal: true,
		create: function(event, ui) {
			$('.ui-button').addClass('button orange');
		},
		buttons: {
			'Delete': function() {
				$('form:first').submit();
			},
			'Cancel': function() {
				$(this).dialog('close');
			}
		}
	});
	
	$('.delete').click(function() {
			
		$('input:checked').each(function() {
			
			$('#dialog_confirm').dialog('open');
			
		});

		return false;
		
	});
						
});
// ]]>
</script>

	<!-- BEGIN #dialog_confirm -->
	<div id="dialog_confirm" title="Delete item(s)?">
		<p>
			These item(s) will be permanently deleted and cannot be recovered. Are you sure?
		</p>
	</div>
	<!-- END #dialog_confirm -->

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
							
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">

			<!-- BEGIN .title_background -->
			<div class="title_background grid_10 alpha omega">
				<h1>Manage coupons</h1>
			</div>
			<!-- END .title_background -->

			<div class="float_right">
				<a href="add_coupon.php" class="button orange">Add coupon</a>
				<button type="submit" class="button orange delete">Delete</button>
			</div>
			
			<div class="clear">&nbsp;</div>
			
			<?php if ($session->get('success')): ?>
				<div class="alert success">
					<p>
						<span>Coupon edited successfully!</span>
					</p>
				</div>
			<?php $session->delete('success'); endif; ?>
			
			<?php if ($row_count > 0): ?>
				
				<form action="" method="post">
																																			
					<table class="table">
						<thead>
							<tr>
								<th><input type="checkbox" value="cb_coupon" class="select_all" /></th>
								<th>Coupon name</th>
								<th>Code</th>
								<th>Discount</th>
								<th>Date start</th>
								<th>Date end</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							
							<?php foreach ($coupons as $row): ?>
								
								<tr>
									<td><input type="checkbox" value="<?php echo $row['coupon_id']; ?>" name="cb_coupon[]" /></td>
									<td><?php echo $row['coupon_name']; ?></td>
									<td><?php echo $row['coupon_code']; ?></td>
									<td><?php echo $row['coupon_discount']; ?></td>
									<td><?php echo $row['date_start']; ?></td>
									<td><?php echo $row['date_end']; ?></td>
									<td><?php if ($row['coupon_status']) echo 'Active'; else echo 'Inactive'; ?></td>
									<td><a href="edit_coupon.php?coupon_id=<?php echo $row['coupon_id']; ?>" title="Edit: <?php echo $row['coupon_name']; ?>"><img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/details.png" alt="" /></a></td>
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
					<p>Coupons not found.</p>
				</div>
				
			<?php endif; ?>
													
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

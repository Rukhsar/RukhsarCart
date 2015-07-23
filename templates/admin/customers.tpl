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
				<h1>Manage customers</h1>
			</div>
			<!-- END .title_background -->

			<div class="float_right">
				<a href="add_customer.php" class="button orange">Add customer</a>
				<button type="submit" class="button orange delete">Delete</button>
			</div>
			
			<div class="clear">&nbsp;</div>
			
			<?php if ($session->get('success')): ?>
				<div class="alert success">
					<p>
						<span>Customer edited successfully!</span>
					</p>
				</div>
			<?php $session->delete('success'); endif; ?>
			
			<?php if ($row_count > 0): ?>
				
				<form action="" method="post">

					<table class="table">
						<thead>
							<tr>
								<th>Filter by name</th>
								<th>Filter by email</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							
							<tr>
								<td><input type="text" name="filter_name" size="50" /></td>
								<td><input type="text" name="filter_email" size="50" /></td>
								<td><button type="submit" name="filter" class="button orange">Filter</button></td>
							</tr>
															
						</tbody>
					</table>
																																			
					<table class="table">
						<thead>
							<tr>
								<th><input type="checkbox" value="cb_customer" class="select_all" /></th>
								<th>Client name</th>
								<th>Email address</th>
								<th>Registered since</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
														
							<?php foreach ($customers as $row): ?>
								
								<tr>
									<td><input type="checkbox" value="<?php echo $row['user_id']; ?>" name="cb_customer[]" /></td>
									<td><?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?></td>
									<td><?php echo $row['customer_email']; ?></td>
									<td><?php echo date('d/m/Y H.i.s', $row['user_created']); ?></td>
									<td><a href="edit_customer.php?user_id=<?php echo $row['user_id']; ?>" title="Edit: <?php echo $row['customer_email']; ?>"><img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/details.png" alt="" /></a></td>
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
					<p>Customers not found.</p>
				</div>
				
			<?php endif; ?>
													
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

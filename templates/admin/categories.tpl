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
				<h1>Manage categories</h1>
			</div>
			<!-- END .title_background -->

			<div class="float_right">
				<a href="add_category.php" class="button orange">Add category</a>
				<button type="submit" class="button orange delete">Delete</button>
			</div>
			
			<div class="clear">&nbsp;</div>
					
			<?php if ($session->get('success')): ?>
				<div class="alert success">
					<p>
						<span>Category edited successfully!</span>
					</p>
				</div>
			<?php $session->delete('success'); endif; ?>
			
			<?php if ($row_count > 0): ?>

				<form action="" method="post">

					<table class="table">
						<thead>
							<tr>
								<th><input type="checkbox" value="cb_category" class="select_all" /></th>
								<th>Category name</th>
								<th>Edit</th>
							</tr>
						</thead>
						<tbody>

						<?php foreach (categories(0) as $value): ?>
					
							<tr>
								<td><input type="checkbox" value="<?php echo $value['category_id']; ?>" name="cb_category[]" /></td>
								<td><?php echo $value['category_name']; ?></td>
								<td><a href="edit_category.php?category_id=<?php echo $value['category_id']; ?>" title="Edit: <?php echo $value['category_name']; ?>"><img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/details.png" alt="" /></a></td>
							</tr>
							
						<?php endforeach; ?>
					
						</tbody>
					</table>
					
				</form>
				
			<?php else: ?>

				<div class="alert info">
					<p>Categories not found.</p>
				</div>

			<?php endif; ?>
													
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

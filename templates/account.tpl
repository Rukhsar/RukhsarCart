<?php require_once('header' . config_item('template', 'template_extension')); ?>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
				
		<!-- BEGIN .grid_12 -->
		<div class="grid_12 title_background">
			<h1>Manage your account</h1>
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
		<!-- BEGIN .grid_12 -->	
		<div class="grid_12">

			<?php if ($session->get('success')): ?>
				<div class="alert success">
					<p>
						<span>User edited successfully!</span>
					</p>
				</div>
			<?php $session->delete('success'); endif; ?>
											
			<p>
				<a href="edit_account.php">Edit your account information</a>
			</p>
			
			<p>
				<a href="history.php">View your order history</a>
			</p>
			
			<p>
				<a href="downloads.php">Downloads</a>
			</p>
									
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
				
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

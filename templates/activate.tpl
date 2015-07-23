<?php require_once('header' . config_item('template', 'template_extension')); ?>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">

		<!-- BEGIN .grid_12 -->
		<div class="grid_12 title_background">
			<h1>Email activation</h1>
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
				
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">
			
			<?php if (isset($success)): ?>
				<div class="alert success">
					<p>
						<span>Congratulations! Activation completed successfully.</span>
					</p>
				</div>
			<?php endif; ?>
			
			<?php if (isset($failed)) : ?>
				<div class="alert error">
					<span>
						<strong>An error occurred while processing request</strong>
					</span>	
					<p>Sorry, email or code is wrong!</p>
				</div>
			<?php endif; ?>
													
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
				
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

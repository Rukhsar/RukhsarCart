<?php require_once('header' . config_item('template', 'template_extension')); ?>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">

		<!-- BEGIN .grid_12 -->
		<div class="grid_12 title_background">
			<h1>Checkout success</h1>
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
						
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">
			
			<?php if (!$session->get('payment_method')) header('Location: history.php'); ?>
			
			<?php if ($session->get('payment_method') == 'Bank transfer'): ?>
				<div class="alert success">
					<p>
						<span>Thank you! We have received your order and have started processing it.</span>
					</p>
					<p>You have chosen to pay by Bank transfer.</p>
					<p>Please transfer the total amount to the following bank account.</p>
					<p>Bank transfer instructions</p>
					<p>Your order will not ship until we receive payment.</p>
				</div>
			<?php endif; ?>

			<?php if ($session->get('payment_method') == 'Cash on delivery'): ?>
				<div class="alert success">
					<p>
						<span>Thank you! We have received your order and have started processing it.</span>
					</p>
				</div>
			<?php endif; ?>
			
			<?php $session->delete('payment_method'); ?>
																					
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
				
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

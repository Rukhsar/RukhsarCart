<?php require_once('header' . config_item('template', 'template_extension')); ?>

	<!-- BEGIN .container_12 -->
	<div class="container_12">
			
		<!-- BEGIN #login -->
		<div id="login">
								
			<h6>Login</h6>
																				
			<form action="" method="post">

				<p>
					<label>Email address</label>
					<input type="text" name="email" size="55" />
				</p>

				<p>
					<label>Password</label>
					<input type="password" name="password" size="55" />
				</p>

				<p>
					Remember me
					<input type="checkbox" name="remember" value="1" />
				</p>
										
				<div class="text_right">
					<p>
						<button type="submit" name="submit" class="button orange">Login</button>
					</p>
				</div>
				
				<div class="clear">&nbsp;</div>

				<div class="grid_5 alpha">
					<p>&#171; <a href="../index.php">Back to shop</a></p>
				</div>
				
				<div class="clear">&nbsp;</div>
						
			</form>

			<?php if ($error->has_errors()): ?>
				
				<div class="alert error">
					<?php foreach ($error->display_errors() as $value): ?>		
						<p><?php echo $value; ?></p>
					<?php endforeach; ?>
				</div>

			<?php $error->clear_errors(); endif; ?>

			<?php if (isset($failed)): ?>

				<div class="alert error">
					<p>Login failed.</p>
				</div>

			<?php endif; ?>
					
		</div>
		<!-- END #login -->
		
		<div class="clear">&nbsp;</div>
					
	</div>
	<!-- END .container_12 -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

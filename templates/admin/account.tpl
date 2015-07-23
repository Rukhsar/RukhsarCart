<?php require_once('header' . config_item('template', 'template_extension')); ?>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
							
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">

			<!-- BEGIN .title_background -->
			<div class="title_background">
				<h1>Your profile</h1>
			</div>
			<!-- END .title_background -->
			
			<div class="clear">&nbsp;</div>
			
			<?php if ($error->has_errors()): ?>
				
				<div class="alert error">
					<span>
						<strong>An error occurred while processing request</strong>
					</span>
					<?php foreach ($error->display_errors() as $value): ?>		
						<p><?php echo $value; ?></p>
					<?php endforeach; ?>
				</div>

			<?php $error->clear_errors(); endif; ?>

			<?php if (isset($success)): ?>
				<div class="alert success">
					<p>
						<span>User edited successfully!</span>
					</p>
				</div>
			<?php endif; ?>
					
			<form action="" method="post">

				<?php foreach ($account_details as $row): ?>
									
					<p>
						<label>First name: <span title="Required field">*</span></label>
						<input type="text" name="first_name" size="50" value="<?php echo $row['first_name']; ?>" />
					</p>
										
					<p>
						<label>Last name: <span title="Required field">*</span></label>
						<input type="text" name="last_name" size="50" value="<?php echo $row['last_name']; ?>" />
					</p>
										
					<p>
						<label>Email address: <span title="Required field">*</span></label>
						<input type="text" name="email" size="50" value="<?php echo $row['user_email']; ?>" readonly="readonly" />
					</p>
					
				<?php endforeach; ?>
										
				<p>
					<label>Password: <span title="Required field">*</span></label>
					<input type="password" name="password" size="50" />
				</p>

				<p>
					<button type="submit" name="submit" class="button orange">Save</button>
				</p>
									
			</form>
													
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

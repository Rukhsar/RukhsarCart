<?php require_once('header' . config_item('template', 'template_extension')); ?>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
							
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">

			<!-- BEGIN .title_background -->
			<div class="title_background">
				<h1>New customer</h1>
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
						<span>Customer added successfully!</span>
					</p>
				</div>
			<?php endif; ?>

			<?php if (isset($failed)): ?>
				<div class="alert error">
					<p>
						<span>This email address is already taken!</span>
					</p>
				</div>
			<?php endif; ?>
			
			<form action="#" method="post">
									
				<p>
					<label>First name: <span title="Required field">*</span></label>
					<input type="text" name="first_name" size="50" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" />
				</p>
									
				<p>
					<label>Last name: <span title="Required field">*</span></label>
					<input type="text" name="last_name" size="50" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" />
				</p>
									
				<p>
					<label>Email address: <span title="Required field">*</span></label>
					<input type="text" name="email" size="50" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" />
				</p>
									
				<p>
					<label>Telephone: <span title="Required field">*</span></label>
					<input type="text" name="telephone" size="50" value="<?php if (isset($_POST['telephone'])) echo $_POST['telephone']; ?>" />
				</p>
				
				<p>
					<label>Status:</label>
					<?php if (isset($_POST['user_status']) && $_POST['user_status'] == 1): ?>
						<input type="radio" name="user_status" value="1" checked="checked" /> Active
						<input type="radio" name="user_status" value="0" /> Inactive
					<?php else: ?>
						<input type="radio" name="user_status" value="1" /> Active
						<input type="radio" name="user_status" value="0" checked="checked" /> Inactive
					<?php endif; ?>
				</p>

				<p>
					<label>Welcome email:</label>
					<?php if (isset($_POST['send_email'])): ?>
						<input type="checkbox" name="send_email" value="true" checked="checked" />
					<?php else: ?>
						<input type="checkbox" name="send_email" value="true" />
					<?php endif; ?>
				</p>
				
				<h6>Address</h6>

				<p>
					<label>Company:</label>
					<input type="text" name="company" size="50" value="<?php if (isset($_POST['company'])) echo $_POST['company']; ?>"  />
				</p>

				<p>
					<label>Address: <span title="Required field">*</span></label>
					<input type="text" name="address" size="50" value="<?php if (isset($_POST['address'])) echo $_POST['address']; ?>" />
				</p>

				<p>
					<label>City: <span title="Required field">*</span></label>
					<input type="text" name="city" size="50" value="<?php if (isset($_POST['city'])) echo $_POST['city']; ?>" />
				</p>

				<p>
					<label>Post code: <span title="Required field">*</span></label>
					<input type="text" name="post_code" size="50" value="<?php if (isset($_POST['post_code'])) echo $_POST['post_code']; ?>" />
				</p>
													
				<p>
					<label>Country:</label>
					<select name="country">
						<?php foreach ($countries as $row): ?>
							<?php if ((isset($_POST['country'])) && $_POST['country'] == $row['country_id']): ?>
								<option value="<?php echo $row['country_id']; ?>" selected="selected"><?php echo $row['country_name']; ?></option>
							<?php else: ?>
								<option value="<?php echo $row['country_id']; ?>"><?php echo $row['country_name']; ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</p>

				<p>
					<label>Region / State: <span title="Required field">*</span></label>
					<input type="text" name="zone" size="50" value="<?php if (isset($_POST['zone'])) echo $_POST['zone']; ?>" />
				</p>
				
				<h6>Password</h6>

				<p>
					<label>Password: <span title="Required field">*</span></label>
					<input type="password" name="password" size="50" />
				</p>

				<p>
					<label>Confirm Password: <span title="Required field">*</span></label>
					<input type="password" name="confirm_password" size="50" />
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

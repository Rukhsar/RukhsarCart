<?php require_once('header' . config_item('template', 'template_extension')); ?>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
							
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">

			<!-- BEGIN .title_background -->
			<div class="title_background">
				<h1>Edit customer</h1>
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
			
			<form action="#" method="post">
				
				<?php if (isset($_GET['user_id']) && $row_count > 0): ?>
									
					<?php foreach ($customer_details as $row): ?>

						<p>
							<input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>" />
						</p>
																
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
							<input type="text" name="email" size="50" value="<?php echo $row['customer_email']; ?>" readonly="readonly" />
						</p>
											
						<p>
							<label>Telephone: <span title="Required field">*</span></label>
							<input type="text" name="telephone" size="50" value="<?php echo $row['customer_telephone']; ?>" />
						</p>
						
						<p>
							<label>Status:</label>
							<?php if ($row['user_status']): ?>
								<input type="radio" name="user_status" value="1" checked="checked" /> Active
								<input type="radio" name="user_status" value="0" /> Inactive
							<?php else: ?>
								<input type="radio" name="user_status" value="1" /> Active
								<input type="radio" name="user_status" value="0" checked="checked" /> Inactive
							<?php endif; ?>
						</p>
					
					<?php endforeach; ?>
						
						<h6>Address</h6>

					<?php foreach ($customer_addresses as $row): ?>

						<p>
							<label>Company:</label>
							<input type="text" name="company" size="50" value="<?php echo $row['company']; ?>" />
						</p>

						<p>
							<label>Address: <span title="Required field">*</span></label>
							<input type="text" name="address" size="50" value="<?php echo $row['address']; ?>" />
						</p>

						<p>
							<label>City: <span title="Required field">*</span></label>
							<input type="text" name="city" size="50" value="<?php echo $row['city']; ?>" />
						</p>

						<p>
							<label>Post code: <span title="Required field">*</span></label>
							<input type="text" name="post_code" size="50" value="<?php echo $row['post_code']; ?>" />
						</p>
															
						<p>
							<label>Country:</label>
							<select name="country">
								<?php foreach ($countries as $value): ?>
									<?php if ($value['country_id'] == $row['country_id']): ?>
										<option value="<?php echo $value['country_id']; ?>" selected="selected"><?php echo $value['country_name']; ?></option>
									<?php else: ?>
										<option value="<?php echo $value['country_id']; ?>"><?php echo $value['country_name']; ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
						</p>

						<p>
							<label>Region / State: <span title="Required field">*</span></label>
							<input type="text" name="zone" size="50" value="<?php echo $row['zone']; ?>" />
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
					
					<?php endforeach; ?>
																				
					<p>
						<button type="submit" name="submit" class="button orange">Save</button>
					</p>

				<?php else: ?>

					<div class="alert info">
						<p>Customer not found.</p>
					</div>

				<?php endif; ?>	
													
			</form>
									
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

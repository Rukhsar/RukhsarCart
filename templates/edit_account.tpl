<?php require_once('header' . config_item('template', 'template_extension')); ?>

<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {

	//qTip
	$('span[title]').qtip({
		style: {
			classes: 'ui-tooltip-shadow ui-tooltip-plain'
		},
		position: {
			my: 'bottom center',
			at: 'top center'
		}
	});
			
	//Check form fields
	$("form#edit_account").validate({
		rules: {
			first_name: "required",
			last_name: "required",
			email: {
				required: true,
				email: true
			},
			telephone: {
				required: true,
				number: true
			},
			address: "required",
			city: "required",
			post_code: "required",
			zone: "required",
			password: "required",
			confirm_password: {
				required: true,
				equalTo: "#password"
			}
		},
		messages: {
			first_name: "Enter your first name.",
			last_name: "Enter your last name.",
			email: {
				required: "Enter your email address.", 
				email: "Email address not valid."
			},
			telephone: {
				required: "Enter your telephone number.", 
				number: "Please enter a valid number."
			},
			address: "Enter your address.",
			city: "Enter your city.",
			post_code: "Enter your post code.",
			zone: "Enter your zone.",
			password: "Enter your password.",
			confirm_password: {
				required: "Confirm your password.", 
				equalTo: "The password does not match."
			}
		},
		success: function(label) {
			label.html("&nbsp;").addClass("success");
		}
	});
			
});
// ]]>
</script>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
		
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">
			
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
			
			<form action="" id="edit_account" method="post">
				
				<div class="title_background">
					<h1>Your personal details</h1>
				</div>
				
				<?php foreach ($customer_details as $row): ?>

					<p>
						<label>First name: <span title="Required field">*</span></label>
						<input type="text" id="first_name" name="first_name" size="50" value="<?php echo $row['first_name']; ?>" />
					</p>
				
					<p>
						<label>Last name: <span title="Required field">*</span></label>
						<input type="text" id="last_name" name="last_name" size="50" value="<?php echo $row['last_name']; ?>" />
					</p>
				
					<p>
						<label>Email address: <span title="Required field">*</span></label>
						<input type="text" id="email" name="email" size="50" value="<?php echo $row['customer_email']; ?>" readonly="readonly" />
					</p>
				
					<p>
						<label>Telephone: <span title="Required field">*</span></label>
						<input type="text" id="telephone" name="telephone" size="50" value="<?php echo $row['customer_telephone']; ?>" />
					</p>
				
				<?php endforeach; ?>
				
				<div class="title_background">
					<h1>Your address</h1>
				</div>
				
				<?php foreach ($customer_addresses as $row): ?>
					
					<p>
						<label>Company:</label>
						<input type="text" id="company" name="company" size="50" value="<?php echo $row['company']; ?>" />
					</p>

					<p>
						<label>Address: <span title="Required field">*</span></label>
						<input type="text" id="address" name="address" size="50" value="<?php echo $row['address']; ?>" />
					</p>

					<p>
						<label>City: <span title="Required field">*</span></label>
						<input type="text" id="city" name="city" size="50" value="<?php echo $row['city']; ?>" />
					</p>

					<p>
						<label>Post Code: <span title="Required field">*</span></label>
						<input type="text" id="post_code" name="post_code" size="50" value="<?php echo $row['post_code']; ?>" />
					</p>

					<p>
						<label>Country: <span title="Required field">*</span></label>
						<select id="country" name="country">
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
						<input type="text" id="zone" name="zone" size="50" value="<?php echo $row['zone']; ?>" />
					</p>
					
				<?php endforeach; ?>

				<div class="title_background">
					<h1>Your password</h1>
				</div>
									
				<p>
					<label>Password: <span title="Required field">*</span></label>
					<input type="password" id="password" name="password" size="50" />
				</p>
									
				<p>
					<label>Confirm password: <span title="Required field">*</span></label>
					<input type="password" id="confirm_password" name="confirm_password" size="50" />
				</p>
				
				<p>
					<button type="submit" name="submit" class="button orange">Edit account</button>
				</p>
												
			</form>
													
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
				
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

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
	$("form#create_account").validate({
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

			<?php if (isset($failed)): ?>
				<div class="alert error">
					<p>
						<span>This email address is already taken!</span>
					</p>
				</div>
			<?php endif; ?>
						
			<?php if (isset($success)): ?>
				
				<?php if (config_item('authentication', 'email_activation') && !config_item('authentication', 'approve_user_registration')): ?>
					
					<div class="alert success">
						<p>
							<span>Thank you for registering!</span>
						</p>
						<p>
							You will receive an email soon with a link to activate your account.
							If you don't receive an email after some time, check your spam folder.
						</p>
					</div>
					
				<?php elseif (!config_item('authentication', 'email_activation') && config_item('authentication', 'approve_user_registration')): ?>

					<div class="alert success">
						<p>
							<span>Thank you for registering!</span>
						</p>
						<p>
							You will be notified by email once your account has been activated by the store owner.							
						</p>
					</div>
									
				<?php else: ?>

					<div class="alert success">
						<p>
							<span>Congratulations! Your new account has been successfully created.</span>
						</p>
					</div>
									
				<?php endif; ?>
				
			<?php endif; ?>

			<form action="" id="create_account" method="post">

				<div class="title_background">
					<h1>Your personal details</h1>
				</div>
				
				<p>
					<label>First name: <span title="Required field">*</span></label>
					<input type="text" id="first_name" name="first_name" size="50" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" />
				</p>
			
				<p>
					<label>Last name: <span title="Required field">*</span></label>
					<input type="text" id="last_name" name="last_name" size="50" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" />
				</p>
			
				<p>
					<label>Email address: <span title="Required field">*</span></label>
					<input type="text" id="email" name="email" size="50" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" />
				</p>
			
				<p>
					<label>Telephone: <span title="Required field">*</span></label>
					<input type="text" id="telephone" name="telephone" size="50" value="<?php if (isset($_POST['telephone'])) echo $_POST['telephone']; ?>" />
				</p>

				<div class="title_background">
					<h1>Your address</h1>
				</div>

				<p>
					<label>Company:</label>
					<input type="text" id="company" name="company" size="50" value="<?php if (isset($_POST['company'])) echo $_POST['company']; ?>" />
				</p>

				<p>
					<label>Address: <span title="Required field">*</span></label>
					<input type="text" id="address" name="address" size="50" value="<?php if (isset($_POST['address'])) echo $_POST['address']; ?>" />
				</p>

				<p>
					<label>City: <span title="Required field">*</span></label>
					<input type="text" id="city" name="city" size="50" value="<?php if (isset($_POST['city'])) echo $_POST['city']; ?>" />
				</p>

				<p>
					<label>Post Code: <span title="Required field">*</span></label>
					<input type="text" id="post_code" name="post_code" size="50" value="<?php if (isset($_POST['post_code'])) echo $_POST['post_code']; ?>" />
				</p>

				<p>
					<label>Country: <span title="Required field">*</span></label>
					<select id="country" name="country">
						<?php foreach ($countries as $row): ?>
							<option value="<?php echo $row['country_id']; ?>"><?php echo $row['country_name']; ?></option>
						<?php endforeach; ?>
					</select>
				</p>

				<p>
					<label>Region / State: <span title="Required field">*</span></label>
					<input type="text" id="zone" name="zone" size="50" value="<?php if (isset($_POST['zone'])) echo $_POST['zone']; ?>" />
				</p>

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
					<button type="submit" name="submit" class="button orange">Register account</button>
				</p>
				
			</form>
																			
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
				
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

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
	$("form#login").validate({
		rules: {
			email: {
				required: true,
				email: true
			},
			password: "required"
		},
		messages: {
			email: {
				required: "Enter your email address.", 
				email: "Email address not valid."
			},
			password: "Enter your password."
		},
		success: function(label) {
			label.html("&nbsp;").addClass("success");
		}
	});

	//Check form fields
	$("form#password_forgotten").validate({
		rules: {
			email: {
				required: true,
				email: true
			}
		},
		messages: {
			email: {
				required: "Enter your email address.", 
				email: "Email address not valid."
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
		<div class="grid_12 title_background">
			<h1>Login</h1>
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
				
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

			<?php if (isset($failed) && isset($_POST['login'])): ?>
				<div class="alert error">
					<span>
						<strong>An error occurred while processing request</strong>
					</span>	
					<p>Login failed!</p>
				</div>
			<?php endif; ?>

			<?php if (isset($failed) && isset($_POST['reset_password'])): ?>
				<div class="alert error">
					<span>
						<strong>An error occurred while processing request</strong>
					</span>	
					<p>The email address was not found in our records, please try again!</p>
				</div>
			<?php endif; ?>

			<?php if (isset($success) && isset($_POST['reset_password'])): ?>
				<div class="alert success">
					<p>
						<span>A new password has been sent to your email address!</span>
					</p>
				</div>
			<?php endif; ?>
									
			<form action="" id="login" method="post">
			
				<p>
					<label>Email address: <span title="Required field">*</span></label>
					<input type="text" id="email" name="email" size="50" />
				</p>

				<p>
					<label>Password: <span title="Required field">*</span></label>
					<input type="password" id="password" name="password" size="50" />
				</p>

				<p>
					<label>Remember me</label>
					<input type="checkbox" id="remember" name="remember" value="remember" />
				</p>
									
				<p>
					<button type="submit" name="login" class="button orange">Login now</button>
				</p>
										
			</form>
												
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
		<!-- BEGIN .grid_12 -->
		<div class="grid_12 title_background">
			<h1>Lost password</h1>
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>

		<!-- BEGIN .grid_12 -->
		<div class="grid_12">
			
			<form action="" id="password_forgotten" method="post">
			
				<p>
					If you've forgotten your password, enter your email address below and we'll send 
					you an email message containing your new password.
				</p>
				
				<p>
					<label>Email address: <span title="Required field">*</span></label>
					<input type="text" id="email" name="email" size="50" />
				</p>
									
				<p>
					<button type="submit" name="reset_password" class="button orange">Submit request</button>
				</p>
			
			</form>
			
		</div>
		<!-- END .grid_12 -->
						
		<div class="clear">&nbsp;</div>
				
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

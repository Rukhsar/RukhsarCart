<?php

//Include library
require_once('../libraries/Session.php');

//Initialize object
$session = new Session();

if (!$session->get('step_3')) header("Location: step_2.php");
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
<!-- BEGIN head -->	
<head>
	
	<!-- Meta Tags -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	
	<!-- Title -->
	<title>Installation (step 3)</title>

	<!-- Style sheet -->
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="css/reset.css" rel="stylesheet" type="text/css" />
	<link href="css/text.css" rel="stylesheet" type="text/css" />
	<link href="css/960.css" rel="stylesheet" type="text/css" />
		
</head>
<!-- END head -->

<!-- BEGIN body -->
<body>

<!-- BEGIN #wrapper -->
<div id="wrapper">

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
		
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">

			<div class="title_background">
				<h1>4. Finished</h1>
			</div>
			
			<p>
				Congratulations on installing and configuring Shopping Cart as your online store solution!
				<br />
				Please log into the admin panel with the following details.
				<br />
				<br />
				<strong>Email:</strong> <?php echo $session->get('user_email'); ?>
				<br />
				<strong>Password:</strong> <?php echo $session->get('user_password'); ?>
				
				<?php $session->destroy(); ?>
			</p>
			
			<p>
				Finally, delete the installer from the server.
			</p>
			
			<p>
				<a href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . rtrim(rtrim(dirname($_SERVER['PHP_SELF']), 'install'), '/.\\'). '/'; ?>" class="button orange">Catalog</a>
				<a href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . rtrim(rtrim(dirname($_SERVER['PHP_SELF']), 'install'), '/.\\'). '/admin'; ?>" class="button orange">Admin panel</a>
			</p>
			
									
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
				
	</div>
	<!-- END #main -->

	<!-- BEGIN .container_12 -->
	<div class="container_12">
		
		<!-- BEGIN #footer -->
		<div id="footer" class="grid_12 text_center">
			<p>Copyright &copy; 2014 Rukhsar Manzoor</p>
		</div>
		<!-- END #footer -->
		
		<div class="clear">&nbsp;</div>
		
	</div>
	<!-- END .container_12 -->
			
</div>
<!-- END #wrapper -->
		
</body>
<!-- END body -->

</html>

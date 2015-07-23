<?php

//Include library
require_once('../libraries/Session.php');

//Initialize object
$session = new Session();

define('DIR_APPLICATION', realpath(dirname(__FILE__) . '/../') . '/');

$writeable_directories = array(
	DIR_APPLICATION . 'config',
	DIR_APPLICATION . 'logs',
	DIR_APPLICATION . 'uploads/files',
	DIR_APPLICATION . 'uploads/images'
);

$writeable_files = array(
	DIR_APPLICATION . 'config/authentication.php',
	DIR_APPLICATION . 'config/cart.php',
	DIR_APPLICATION . 'config/database.php',
	DIR_APPLICATION . 'config/upload.php',
	DIR_APPLICATION . 'config/template.php'
);

//Check settings server
function check_settings() {
	
	global $writeable_directories, $writeable_files;
	
	$error = array();
	
	if (phpversion() < '5.2')
		$error['warning'] = true;
		
	if (!ini_get('file_uploads'))
		$error['warning'] = true;
		
	if (!extension_loaded('session'))
		$error['warning'] = true;
	
	if (!extension_loaded('PDO'))
		$error['warning'] = true;
		
	if (!extension_loaded('gd'))
		$error['warning'] = true;

	if (ini_get('register_globals'))
		$error['warning'] = true;
		
	foreach ($writeable_directories as $value) {
		
		if (!is_writable($value))
			$error['warning'] = true;
		
	}

	foreach ($writeable_files as $value) {
		
		if (!is_writable($value))
			$error['warning'] = true;
		
	}
	
	if (!$error)
		return true;
	else
		return false;
    		
}	

if (check_settings()) $session->set('step_1', true);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
<!-- BEGIN head -->	
<head>
	
	<!-- Meta Tags -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	
	<!-- Title -->
	<title>Installation</title>

	<!-- Style sheet -->
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="css/reset.css" rel="stylesheet" type="text/css" />
	<link href="css/text.css" rel="stylesheet" type="text/css" />
	<link href="css/960.css" rel="stylesheet" type="text/css" />
	<link href="css/table.css" rel="stylesheet" type="text/css" />

	<!-- jQuery -->
	<script type="text/javascript" src="js/jquery.min.js"></script>

	<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function() {

		//Alternate row colors
		$(".table tr:nth-child(odd)").addClass("odd");
		$(".table tr:nth-child(even)").addClass("even");
    						
	});
	// ]]>
	</script>
			
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
				<h1>1. Requirements</h1>
			</div>

			<table class="table">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>Expected value</th>
						<th>Server value</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><strong>Required settings</strong></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>PHP version</td>
						<td>5.2 or higher</td>
						<td><?php echo phpversion(); ?></td>
					</tr>
					<tr>
						<td>File uploads</td>
						<td>On</td>
						<td>
							<?php if (ini_get('file_uploads')): ?> 
								<img src="images/accept.png" alt="" />
							<?php else: ?>
								<img src="images/cross.png" alt="" />
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td>Session</td>
						<td>On</td>
						<td>
							<?php if (extension_loaded('session')): ?> 
								<img src="images/accept.png" alt="" />
							<?php else: ?>
								<img src="images/cross.png" alt="" />
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td>Register globals</td>
						<td>Off</td>
						<td>
							<?php if (!ini_get('register_globals')): ?>
								<img src="images/accept.png" alt="" />
							<?php else: ?>
								<img src="images/cross.png" alt="" />
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td><strong>PHP Extensions</strong></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>PDO</td>
						<td>Loaded</td>
						<td>
							<?php if (extension_loaded('PDO')): ?> 
								<img src="images/accept.png" alt="" />
							<?php else: ?>
								<img src="images/cross.png" alt="" />
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td>GD library</td>
						<td>Loaded</td>
						<td>
							<?php if (extension_loaded('gd')): ?> 
								<img src="images/accept.png" alt="" />
							<?php else: ?>
								<img src="images/cross.png" alt="" />
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td><strong>Folder permissions</strong></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td><?php echo DIR_APPLICATION . 'config'; ?></td>
						<td>Writable</td>
						<td>
							<?php if (is_writable(DIR_APPLICATION . 'config')): ?>
								<img src="images/accept.png" alt="" />
							<?php else: ?>
								<img src="images/cross.png" alt="" />
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td><?php echo DIR_APPLICATION . 'logs'; ?></td>
						<td>Writable</td>
						<td>
							<?php if (is_writable(DIR_APPLICATION . 'logs')): ?>
								<img src="images/accept.png" alt="" />
							<?php else: ?>
								<img src="images/cross.png" alt="" />
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td><?php echo DIR_APPLICATION . 'uploads/files'; ?></td>
						<td>Writable</td>
						<td>
							<?php if (is_writable(DIR_APPLICATION . 'uploads/files')): ?>
								<img src="images/accept.png" alt="" />
							<?php else: ?>
								<img src="images/cross.png" alt="" />
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td><?php echo DIR_APPLICATION . 'uploads/images'; ?></td>
						<td>Writable</td>
						<td>
							<?php if (is_writable(DIR_APPLICATION . 'uploads/images')): ?>
								<img src="images/accept.png" alt="" />
							<?php else: ?>
								<img src="images/cross.png" alt="" />
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td><strong>File permissions</strong></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td><?php echo DIR_APPLICATION . 'config/authentication.php'; ?></td>
						<td>Writable</td>
						<td>
							<?php if (is_writable(DIR_APPLICATION . 'config/authentication.php')): ?>
								<img src="images/accept.png" alt="" />
							<?php else: ?>
								<img src="images/cross.png" alt="" />
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td><?php echo DIR_APPLICATION . 'config/cart.php'; ?></td>
						<td>Writable</td>
						<td>
							<?php if (is_writable(DIR_APPLICATION . 'config/cart.php')): ?>
								<img src="images/accept.png" alt="" />
							<?php else: ?>
								<img src="images/cross.png" alt="" />
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td><?php echo DIR_APPLICATION . 'config/database.php'; ?></td>
						<td>Writable</td>
						<td>
							<?php if (is_writable(DIR_APPLICATION . 'config/database.php')): ?>
								<img src="images/accept.png" alt="" />
							<?php else: ?>
								<img src="images/cross.png" alt="" />
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td><?php echo DIR_APPLICATION . 'config/upload.php'; ?></td>
						<td>Writable</td>
						<td>
							<?php if (is_writable(DIR_APPLICATION . 'config/upload.php')): ?>
								<img src="images/accept.png" alt="" />
							<?php else: ?>
								<img src="images/cross.png" alt="" />
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td><?php echo DIR_APPLICATION . 'config/template.php'; ?></td>
						<td>Writable</td>
						<td>
							<?php if (is_writable(DIR_APPLICATION . 'config/template.php')): ?>
								<img src="images/accept.png" alt="" />
							<?php else: ?>
								<img src="images/cross.png" alt="" />
							<?php endif; ?>
						</td>
					</tr>
				</tbody>
			</table>

			<?php if (check_settings()): ?>
				<div class="alert success">
					<p>
						Your server meets all the requirements for Shopping Cart to run properly, go to the next step by
						clicking the button below.
					</p>
				</div>
			<?php else: ?>
				<div class="alert error">
					<p>
						It seems that your server failed to meet the requirements to run Shopping Cart. Please contact 
						your server administrator or hosting company to get this resolved.
					</p>
				</div>
			<?php endif; ?>

			<?php if (check_settings()): ?>
				<a href="step_1.php" class="button orange float_right">Next step</a>	
			<?php else: ?>
				<a href="index.php" class="button orange float_right">Try again</a>
			<?php endif; ?>
												
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

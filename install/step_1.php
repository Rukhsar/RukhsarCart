<?php

//Include libraries
require_once('../libraries/Error.php');
require_once('../libraries/Session.php');
require_once('../libraries/Validate.php');

//Initialize objects
$error = new Error();
$session = new Session();
$validate = new Validate();

if (!$session->get('step_1')) header("Location: index.php");

//Check if the form has been submitted
if (isset($_POST['submit'])) {

	$validate->required($_POST['hostname'], 'Enter your hostname.');
	$validate->required($_POST['username'], 'Enter your username.');
	$validate->required($_POST['dbname'], 'Enter your database name.');
	
	if (!$error->has_errors()) {

		$session->set('hostname', $_POST['hostname']);
		$session->set('username', $_POST['username']);
		$session->set('password', $_POST['password']);
		$session->set('dbname', $_POST['dbname']);
				
		if (isset($_POST['create_db'])) {
			
			$session->set('create_db', true);
			
			if (@mysql_connect($_POST['hostname'], $_POST['username'], $_POST['password']))
				header("Location: step_2.php");
			else
				$failed = true;
			
		} else {
			
			$session->set('create_db', false);

			if (@mysql_connect($_POST['hostname'], $_POST['username'], $_POST['password']) && mysql_select_db($_POST['dbname']))
				header("Location: step_2.php");
			else
				$failed = true;	
			
		}
		
		if (!$failed) $session->set('step_2', true);
			
	}

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
<!-- BEGIN head -->	
<head>
	
	<!-- Meta Tags -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	
	<!-- Title -->
	<title>Installation (step 1)</title>

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
			
			<form action="" method="post">

				<div class="title_background">
					<h1>2. Please enter your database information</h1>
				</div>

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
						<span>
							<strong>An error occurred while processing request</strong>
						</span>	
						<p>Problem connecting to the database: <?php echo mysql_error(); ?></p>
					</div>
				<?php endif; ?>
						
				<p>
					<label>Server:</label>
					<input type="text" id="hostname" name="hostname" size="50" value="<?php if (isset($_POST['hostname'])) echo $_POST['hostname']; ?>" />
					<br />
					<span>The address of the database server in the form of a hostname or IP address.</span>
				</p>

				<p>
					<label>Username:</label>
					<input type="text" id="username" name="username" size="50" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" />
					<br />
					<span>The username used to connect to the database server.</span>
				</p>

				<p>
					<label>Password:</label>
					<input type="password" id="password" name="password" size="50" value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>" />
					<br />
					<span>The password that is used together with the username to connect to the database server.</span>
				</p>

				<p>
					<label>Database name:</label>
					<input type="text" id="dbname" name="dbname" size="50" value="<?php if (isset($_POST['dbname'])) echo $_POST['dbname']; ?>" />
					<br />
					<span>The name of the database.</span>
				</p>

				<p>
					<label>Create database</label>
					<input type="checkbox" id="create_db" name="create_db" value="true" /> (You may need to create it yourself)
				</p>
				
				<button type="submit" name="submit" class="button orange float_right">Next step</button>
			
			</form>
			
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

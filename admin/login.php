<?php

//Include the common file
require('../common.php');

//Check if the user is logged in and is admin
if ($authentication->logged_in() && $authentication->is_admin()) header("Location: index.php");

//Check if the form has been submitted
if (isset($_POST['submit'])) {
	
	$validate->email($_POST['email'], 'Email address not valid.');
	$validate->required($_POST['password'], 'Enter your password.');

	if (!$error->has_errors()) {

		$remember = false;
		
		if (isset($_POST['remember']))
			$remember = true;					
			
		if ($authentication->login($_POST['email'], $_POST['password'], $remember))
			header("Location: index.php");
		else
			$tpl->set('failed', true);
			
	}
	
}
//Display the template
$tpl->display('admin/login');

?>

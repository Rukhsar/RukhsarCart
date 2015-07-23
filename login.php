<?php

//Include the common file
require_once('common.php');

//Check if the user is logged in
if ($authentication->logged_in() && $authentication->is_group('customer')) header("Location: account.php");

//Check if the form has been submitted
if (isset($_POST['login'])) {
	
	$validate->email($_POST['email'], 'Email address not valid.');
	$validate->required($_POST['password'], 'Enter your password.');

	if (!$error->has_errors()) {

		$remember = false;
		
		if (isset($_POST['remember']))
			$remember = true;					
			
		if ($authentication->login($_POST['email'], $_POST['password'], $remember))
			header("Location: account.php");
		else
			$tpl->set('failed', true);
			
	} else {
		
		$tpl->set('failed', true);

	}
	
}
//Check if the form has been submitted
if (isset($_POST['reset_password'])) {

	$validate->email($_POST['email'], 'Email address not valid.');
	
	if (!$error->has_errors()) {
		
		if ($authentication->new_password($_POST['email']))
			$tpl->set('success', true);
		else
			$tpl->set('failed', true);
			
	}
				
}

//Display the template
$tpl->display('login');
			
?>

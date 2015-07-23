<?php

//Include the common file
require_once('common.php');

//Account activation
if (isset($_GET['email']) && isset($_GET['code']) && !$_POST) {

	if ($authentication->activate_user($_GET['email'], $_GET['code']))
		$tpl->set('success', true);
	else
		$tpl->set('failed', true);
		
} else {

	header("Location: index.php");

}
//Display the template
$tpl->display('activate');

?>


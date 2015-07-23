<?php

//Include the common file
require('../common.php');

//Check if the user is logged in
if (!$authentication->logged_in() || !$authentication->is_admin()) header("Location: login.php");

//Account details
foreach ($db->query("SELECT * FROM " . config_item('authentication', 'table_users') . " u, " . config_item('authentication', 'table_profiles') . " p WHERE u.user_id = '" . $session->get('user_id') . "' AND u.user_id = p.user_id") as $row)
	$account_details[] = $row; 
	
//Check if the form has been submitted
if (isset($_POST['submit'])) {

	$validate->required($_POST['first_name'], 'Enter your first name.');
	$validate->required($_POST['last_name'], 'Enter your last name.');
	$validate->email($_POST['email'], 'Email address not valid.');
	$validate->required($_POST['password'], 'Enter your password.');
	
	if (!$error->has_errors()) {

		$additional_data = array(
			'first_name' 	=> $_POST['first_name'],
			'last_name' 	=> $_POST['last_name']
		);
		
		$user = $authentication->get_user($session->get('user_id'));
		
		$authentication->update_user($session->get('user_id'), $user['user_email'], $_POST['password'], $additional_data);
		
		$tpl->set('success', true);
						
	}
	
}
//Template values
$tpl->set('account_details', $account_details);

//Display the template
$tpl->display('admin/account');

?>

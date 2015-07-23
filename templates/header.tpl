<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
<!-- BEGIN head -->	
<head>
	
	<!-- Meta Tags -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	
	<!-- Title -->
	<title>Shopping Cart</title>

	<!-- Style sheet -->
	<link href="<?php echo config_item('template', 'site_url'); ?>templates/css/style.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo config_item('template', 'site_url'); ?>templates/css/reset.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo config_item('template', 'site_url'); ?>templates/css/text.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo config_item('template', 'site_url'); ?>templates/css/960.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo config_item('template', 'site_url'); ?>templates/css/table.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo config_item('template', 'site_url'); ?>templates/css/jquery.qtip.min.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo config_item('template', 'site_url'); ?>templates/css/jquery.fancybox.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo config_item('template', 'site_url'); ?>templates/css/tabs.css" rel="stylesheet" type="text/css" />
	
	<!-- jQuery -->
	<script type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/js/jquery-ui.min.js"></script>
	<!-- jQuery plugins -->
	<script type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/js/jquery.qtip.min.js"></script>
	<script type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/js/jquery.blockUI.js"></script>
	<script type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/js/jquery.fancybox.js"></script>
	<script type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/js/jquery.cookie.js"></script>
			
	<!-- Custom js -->
	<script type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/js/custom.min.js"></script>
				
</head>
<!-- END head -->

<!-- BEGIN body -->
<body>

<!-- BEGIN #wrapper -->
<div id="wrapper">

	<!-- BEGIN #header -->
	<div id="header" class="container_12">
		
		<!-- BEGIN #top_menu -->						
		<div id="top_menu" class="grid_4">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="#">About us</a></li>
				<li><a href="#">F.A.Q</a></li>
			</ul>
		</div>
		<!-- END #top_menu -->
		
		<!-- BEGIN #user_menu -->						
		<div id="user_menu" class="grid_8">
			<ul>
				<?php if (!$authentication->logged_in() || !$authentication->is_group('customer')): ?>
					<li>Welcome: Guest</li>
					<li><a href="login.php">Login</a></li>
					<li><a href="create_account.php">Register</a></li>
				<?php else: ?>	
					<li>
						<a href="account.php">
							Welcome: <?php if ($session->get('user_email')) echo $session->get('user_email'); ?>
						</a>
					</li>
					<li><a href="?logout">Logout</a></li>
				<?php endif; ?>
				<li><a href="view_cart.php">View cart</a></li>
				<li>
					<div id="cart_items">
						<?php require_once('display_cart.php');	?>
					</div>
				</li>
			</ul>
		</div>
		<!-- END #user_menu -->
		
		<div class="clear">&nbsp;</div>
						
	</div>
	<!-- END #header -->

	<?php if (is_dir(realpath(dirname(__FILE__) . '/../') . '/install')): ?>
		<div class="container_12">
			<div class="grid_12">
				<div class="alert error">
					<span>
						<strong>Warning</strong>
					</span>	
					<p>Install folder still exists and should be deleted for security reasons.</p>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	<?php endif; ?>

	<noscript>
		<div class="container_12">
			<div class="grid_12">
				<div class="alert error">
					<span>
						<strong>Warning</strong>
					</span>	
					<p>
						Many features on the web site require Javascript and cookies, you can enable both via your browser's
						preference settings.
					</p>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</noscript>

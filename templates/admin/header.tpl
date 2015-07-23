<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
<!-- BEGIN head -->	
<head>
	
	<!-- Meta Tags -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	
	<!-- Title -->
	<title>Shopping Cart</title>

	<!-- Style sheet -->
	<link href="<?php echo config_item('template', 'site_url'); ?>templates/admin/css/style.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo config_item('template', 'site_url'); ?>templates/admin/css/reset.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo config_item('template', 'site_url'); ?>templates/admin/css/text.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo config_item('template', 'site_url'); ?>templates/admin/css/960.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo config_item('template', 'site_url'); ?>templates/admin/css/table.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo config_item('template', 'site_url'); ?>templates/admin/css/tabs.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo config_item('template', 'site_url'); ?>templates/admin/css/dialog.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo config_item('template', 'site_url'); ?>templates/admin/css/jquery.qtip.min.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo config_item('template', 'site_url'); ?>templates/admin/css/jquery.jqplot.min.css" rel="stylesheet" type="text/css" />
	
	<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/admin/js/excanvas.min.js"></script><![endif]-->
	
	<!-- jQuery -->
	<script type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/admin/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/admin/js/jquery-ui.min.js"></script>
	<!-- jQuery plugins -->
	<script type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/admin/js/jquery.qtip.min.js"></script>
	<script type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/admin/js/jquery.cookie.js"></script>
	<script type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/admin/js/tiny_mce/jquery.tinymce.js"></script>
	<script type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/admin/js/jquery.jqplot.min.js"></script>
	<script type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/admin/js/jqplot.barRenderer.min.js"></script>
	<script type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/admin/js/jqplot.categoryAxisRenderer.min.js"></script>
	<script type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/admin/js/jqplot.pointLabels.min.js"></script>
				
	<!-- Custom js -->
	<script type="text/javascript" src="<?php echo config_item('template', 'site_url'); ?>templates/admin/js/custom.js"></script>
	
</head>
<!-- END head -->

<!-- BEGIN body -->
<body>

<!-- BEGIN #wrapper -->
<div id="wrapper">
	
	<?php if ($authentication->logged_in() && $authentication->is_admin()): ?>
		<!-- BEGIN #header -->
		<div id="header" class="container_12">
			
			<!-- BEGIN .grid_8 -->						
			<div class="grid_8">
				<a href="index.php"><img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/logo.png" alt="" /></a>
			</div>
			<!-- END .grid_8 -->
			
			<!-- BEGIN #user_menu -->
			<div id="user_menu" class="grid_4">
				<ul>
					<li><span><strong>Welcome:</strong> <?php if ($session->get('user_email')) echo $session->get('user_email'); ?></span></li>
					<li><a href="../index.php" target="_blank"><img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/home.png" alt="" title="My Shop" /></a></li>
					<li><a href="account.php"><img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/user.png" alt="" title="Profile" /></a></li>
					<li><a href="index.php?logout"><img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/logout.png" alt="" title="Logout" /></a></li>
				</ul>
			</div>
			<!-- END #user_menu -->
			
			<div class="clear">&nbsp;</div>
			
			<div class="grid_12 line">&nbsp;</div>
			
			<div class="clear">&nbsp;</div>
			
			<!-- BEGIN #main_menu -->
			<div id="main_menu" class="grid_9">
				<ul>
					<li><a href="categories.php" <?php if (in_array(basename($_SERVER['PHP_SELF']), array('categories.php', 'add_category.php', 'edit_category.php'))) echo 'class="current"'; ?>>Categories</a></li>
					<li><a href="products.php" <?php if (in_array(basename($_SERVER['PHP_SELF']), array('products.php', 'add_product.php', 'edit_product.php'))) echo 'class="current"'; ?>>Products</a></li>
					<li><a href="customers.php" <?php if (in_array(basename($_SERVER['PHP_SELF']), array('customers.php', 'add_customer.php', 'edit_customer.php'))) echo 'class="current"'; ?>>Customers</a></li>
					<li><a href="orders.php" <?php if (in_array(basename($_SERVER['PHP_SELF']), array('orders.php', 'order_details.php'))) echo 'class="current"'; ?>>Orders</a></li>
					<li><a href="coupons.php" <?php if (in_array(basename($_SERVER['PHP_SELF']), array('coupons.php', 'add_coupon.php', 'edit_coupon.php'))) echo 'class="current"'; ?>>Coupons</a></li>
					<li><a href="settings.php" <?php if (in_array(basename($_SERVER['PHP_SELF']), array('settings.php'))) echo 'class="current"'; ?>>Settings</a></li>
				</ul>
			</div>
			<!-- END #main_menu -->
					
			<div class="clear">&nbsp;</div>
							
		</div>
		<!-- END #header -->
	<?php endif; ?>
	
	<?php if (is_dir(realpath(dirname(__FILE__) . '/../../') . '/install')): ?>
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
					<p>
						<span>
							<strong>Warning</strong>
						</span>
					</p>
					<p>
						Many features on the web site require Javascript and cookies, you can enable both via your browser's
						preference settings.
					</p>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</noscript>

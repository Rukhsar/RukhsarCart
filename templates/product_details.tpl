<?php require_once('header' . config_item('template', 'template_extension')); ?>

<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
				
	//Fancybox
	$("a.image").fancybox();

	//Tabs
	$('#tabs').tabs({ 
		cookie: { expires: 30 } 
	});
				
});
// ]]>
</script>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
		
		<div id="menu">
			<div class="grid_9">
				<?php echo get_categories(0); ?>
			</div>
			
			<div class="grid_3 alpha omega">
				<form action="search.php" method="post">
					<input type="text" name="filter_product_name" size="20" />
					<input type="submit" name="filter" value="Go" class="search" />
				</form>
			</div>
			
		</div>
				
		<div class="clear">&nbsp;</div>
		
		<div class="grid_12">
			
			<div id="show_errors">
				<?php require_once('errors.php');	?>
			</div>
			
		</div>
		
		<div class="clear">&nbsp;</div>
											
		<?php if (isset($_GET['product_id']) && $cart->get_product($_GET['product_id']) > 0): ?>
		
			<?php foreach ($cart->get_product($_GET['product_id']) as $value): ?>
				
				<div class="grid_12 loading product_details">

					<div class="title_background">
						<h1><?php echo $value['product_name']; ?></h1>
					</div>
					
					<div class="clear">&nbsp;</div>

					<div class="grid_4 alpha">
						
						<?php if (is_null($value['product_image'])): ?>
							<p><img src="<?php echo config_item('cart', 'site_url'); ?>uploads/images/no_image.png" alt="" /></p>
						<?php else: ?>
							<p>
								<a href="<?php echo config_item('cart', 'site_url'); ?>uploads/images/<?php echo $value['product_image']; ?>" class="image" target="_blank" rel="product_id_<?php echo $_GET['product_id']; ?>">
									<img src="<?php echo config_item('cart', 'site_url'); ?>uploads/images/<?php echo $value['product_thumbnail']; ?>" alt="" />
								</a>
							</p>
						<?php endif; ?>
						
					</div>

					<div class="grid_8 omega">

						<!-- BEGIN #tabs -->
						<div id="tabs">
						
							<ul>
								<li><a href="#tab-1">Information</a></li>
								<li><a href="#tab-2">Description</a></li>
								<?php if (count($cart->get_product_images($_GET['product_id'])) > 0): ?>
									<li><a href="#tab-3">Gallery</a></li>
								<?php endif; ?>
							</ul>
							
							<div id="tab-1">

								<p>
									<strong>Price:</strong> 
									
									<?php echo price($value['product_price']); ?>								
								</p>
								
								<form class="add_product" method="post" action="">
									
									<input type="hidden" name="product_id" value="<?php echo $_GET['product_id']; ?>" />
									
									<?php if (count($cart->get_product_options($_GET['product_id'])) > 0): ?>
										<p><strong>Available Options:</strong></p>
									<?php endif; ?>
									
									<?php foreach ($cart->get_product_options($_GET['product_id']) as $product_options): ?>
									
										<p>
											<?php echo $product_options['option_name']; ?><br />
											<select name="option[]" class="option">
											
												<?php foreach ($product_options['option_values'] as $value_options): ?>
													<option value="<?php echo $value_options['option_value_id']; ?>">
														<?php echo $value_options['option_value']; ?>
														<?php if ($value_options['option_price'] != 0): ?>
															<?php echo $value_options['option_type'] . price($value_options['option_price']); ?>
														<?php endif; ?>
													</option>
												<?php endforeach; ?>
												
											</select>
										</p>
										
									<?php endforeach; ?>
									
									<p>
										<button type="submit" name="add_product" class="button orange">Add to cart</button>
									</p>
									
								</form>
						
							</div>
							
							<div id="tab-2" class="description">
								<?php echo $value['product_description']; ?>
							</div>
							
							<?php if (count($cart->get_product_images($_GET['product_id'])) > 0): ?>
								<div id="tab-3">
									<p>
										<?php foreach ($cart->get_product_images($_GET['product_id']) as $value): ?>
											<a href="<?php echo config_item('cart', 'site_url'); ?>uploads/images/<?php echo $value['image']; ?>" class="image" target="_blank" rel="product_id_<?php echo $_GET['product_id']; ?>">
												<img src="<?php echo config_item('cart', 'site_url'); ?>uploads/images/<?php echo $value['thumbnail']; ?>" alt="" />
											</a>
										<?php endforeach; ?>
									</p>
								</div>
							<?php endif; ?>
							
						</div>
						<!-- END #tabs -->
																							
					</div>
												
				</div>
				
				<div class="clear">&nbsp;</div>
				
			<?php endforeach; ?>
									
		<?php else: ?>
			
			<div class="grid_12">
				<p>Product not found.</p>
			</div>
			
			<div class="clear">&nbsp;</div>
			
		<?php endif; ?>
		
	</div>
	<!-- END #main -->
		
<?php require_once('footer' . config_item('template', 'template_extension')); ?>

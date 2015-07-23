<?php require_once('header' . config_item('template', 'template_extension')); ?>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
		
		<!-- BEGIN #menu -->
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
		<!-- END #menu -->
		
		<div class="clear">&nbsp;</div>
		
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">
			
			<h1>Welcome to our site</h1>
			
			<p>
				Lorem ipsum cubilia interdum dictum tincidunt pulvinar porta adipiscing, mauris pellentesque ac neque 
				sem platea tincidunt dolor nisl, dictum eget vestibulum habitant gravida nibh venenatis.
				<br />
				<br />
				Odio fusce arcu faucibus facilisis amet mollis consectetur dapibus aptent donec nullam, fames diam 
				ipsum vitae integer sollicitudin felis luctus a.
				<br />
				<br />				
				Tellus consequat id mollis himenaeos habitasse donec adipiscing aliquet placerat tortor elementum, 
				mollis dapibus torquent tempus maecenas commodo quisque ligula euismod.
			</p>
									
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
		<!-- BEGIN .grid_12 -->	
		<div class="grid_12 title_background">
			<h1>Latest products</h1>
		</div>
		<!-- END .grid_12 -->
			
		<div class="clear">&nbsp;</div>

		<?php foreach ($products as $row): ?>
		
			<div class="grid_3">
				
				<div class="box text_center">
					
					<span class="thumbnail">
						<a href="product_details.php?product_id=<?php echo $row['product_id']; ?>">
							<?php if (is_null($row['product_thumbnail'])): ?>
								<img src="<?php echo config_item('cart', 'site_url'); ?>uploads/images/no_image.png" alt="" />
							<?php else: ?>
								<img src="<?php echo config_item('cart', 'site_url'); ?>uploads/images/<?php echo $row['product_thumbnail']; ?>" alt="" />
							<?php endif; ?>
						</a>
					</span>
					
					<span class="price">
						<?php echo price($row['product_price']); ?>
					</span>
					
					<span class="title"><?php echo $row['product_name']; ?></span>
									
					<div class="line"></div>
					
					<p>
						<a href="product_details.php?product_id=<?php echo $row['product_id']; ?>" class="button orange">Read more</a>
					</p>

				</div>
				
			</div>
		
		<?php endforeach; ?>
		
		<div class="clear">&nbsp;</div>
		
	</div>
	<!-- END #main -->
			
<?php require_once('footer' . config_item('template', 'template_extension')); ?>

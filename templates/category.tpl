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
		<div class="grid_12 title_background">

			<h1><?php echo $category_name; ?></h1>
			
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
			
		<?php if (count($cart->get_categories($_GET['category_id'])) > 0): ?>

			<?php if (is_array($cart->get_categories($_GET['category_id']))): ?>
				
				<?php $row = 0; ?>
				
				<?php foreach ($cart->get_categories($_GET['category_id']) as $value): ?>
					
					<div class="grid_2 text_center">
						<a href="?category_id=<?php echo $value['category_id']; ?>">
							<img src="<?php echo config_item('template', 'site_url'); ?>templates/images/folder.png" alt="" />
						</a>
						<br />
						<a href="?category_id=<?php echo $value['category_id']; ?>">
							<?php echo $value['category_name']; ?>
						</a>
					</div>
					
					<?php $row++; ?>
					
					<?php if ($row == 6): ?>
						<div class="clear">&nbsp;</div>
					<?php $row = 0; endif; ?>
					
				<?php endforeach; ?>
				
			<?php endif; ?>
		
		<?php endif; ?>
		
		<div class="clear">&nbsp;</div>
		
		<?php if (count($cart->get_products($_GET['category_id'])) > 0): ?>
			
			<?php if (is_array($cart->get_products($_GET['category_id']))): ?>
				
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
				
				<div class="grid_5">
						
					<?php

						if($current_page > 1 && ($current_page - 1) < $pages)
							echo '<a href="?category_id=' . $_GET['category_id'] . '&page=' . ($current_page - 1) . '" class="button orange">&laquo; Previous page</a>';
						if($pages > $current_page && ($current_page - 1) < $pages)
							echo '<a href="?category_id=' . $_GET['category_id'] . '&page=' . ($current_page + 1) . '" class="button orange">Next page &raquo;</a>';

					?>
					
				</div>
								
			<?php endif; ?>
			
		<?php else: ?>

			<div class="grid_12">
				<div class="alert info">
					<p>Products not found.</p>
				</div>
			</div>
			
		<?php endif; ?>
							
		<div class="clear">&nbsp;</div>
				
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

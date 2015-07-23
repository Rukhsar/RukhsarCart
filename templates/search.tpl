<?php require_once('header' . config_item('template', 'template_extension')); ?>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
				
		<!-- BEGIN .grid_12 -->
		<div class="grid_12 title_background">
			<h1>Results</h1>
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>

		<?php if (isset($_POST['filter']) && !empty($_POST['filter_product_name'])): ?>
		
			<?php foreach ($results as $row): ?>
			
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
		
		<?php else: ?>

			<div class="grid_12">
				<div class="alert info">
					<p>Please type a search keyword!</p>
				</div>
			</div>
				
		<?php endif; ?>
		
		<div class="clear">&nbsp;</div>
				
	</div>
	<!-- END #main -->
		
<?php require_once('footer' . config_item('template', 'template_extension')); ?>

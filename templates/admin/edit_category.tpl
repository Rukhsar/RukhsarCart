<?php require_once('header' . config_item('template', 'template_extension')); ?>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
							
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">

			<!-- BEGIN .title_background -->
			<div class="title_background">
				<h1>Edit category</h1>
			</div>
			<!-- END .title_background -->
			
			<div class="clear">&nbsp;</div>
			
			<?php if ($error->has_errors()): ?>
				
				<div class="alert error">
					<span>
						<strong>An error occurred while processing request</strong>
					</span>
					<?php foreach ($error->display_errors() as $value): ?>		
						<p><?php echo $value; ?></p>
					<?php endforeach; ?>
				</div>

			<?php $error->clear_errors(); endif; ?>
					
			<form action="" method="post">
									
				<?php if (isset($_GET['category_id']) && $row_count > 0): ?>

					<?php foreach ($category_details as $row): ?>

						<p>
							<input type="hidden" name="category_id" value="<?php echo $row['category_id']; ?>" />
						</p>
																						
						<p>
							<label>Name: <span title="Required field">*</span></label>
							<input type="text" name="category_name" size="50" value="<?php echo $row['category_name']; ?>" />
							<img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/help.png" alt="" title="The name of the category will appear in navigation." />
						</p>
						
						<p>
							<label>Parent category:</label>
							<select name="parent_id">
								<option value="">-- None --</option>
								<?php foreach (categories(0) as $value): ?>
									<?php if ($value['category_id'] == $row['parent_id']): ?>
										<option value="<?php echo $value['category_id']; ?>" selected="selected"><?php echo $value['category_name']; ?></option>
									<?php else: ?>	
										<option value="<?php echo $value['category_id']; ?>"><?php echo $value['category_name']; ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
							<img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/help.png" alt="" title="Choose from dropdown if this is not top level category." />
						</p>
						
						<p>
							<label>Category status:</label>
							<?php if ($row['category_status']): ?>
								<input type="radio" name="category_status" value="1" checked="checked" /> Active
								<input type="radio" name="category_status" value="0" /> Inactive
							<?php else: ?>
								<input type="radio" name="category_status" value="1" /> Active
								<input type="radio" name="category_status" value="0" checked="checked" /> Inactive
							<?php endif; ?>
						</p>
						
						<p>
							<button type="submit" name="submit" class="button orange">Save</button>
						</p>

					<?php endforeach; ?>

				<?php else: ?>
				
					<div class="alert info">
						<p>Category not found.</p>
					</div>

				<?php endif; ?>
									
			</form>
													
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

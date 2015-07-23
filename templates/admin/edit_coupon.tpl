<?php require_once('header' . config_item('template', 'template_extension')); ?>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
							
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">

			<!-- BEGIN .title_background -->
			<div class="title_background">
				<h1>Edit coupon</h1>
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
			
			<form action="#" method="post">

				<?php if (isset($_GET['coupon_id']) && $row_count > 0): ?>

					<?php foreach ($coupon_details as $row): ?>

						<p>
							<input type="hidden" name="coupon_id" value="<?php echo $row['coupon_id']; ?>" />
						</p>
																								
						<p>
							<label>Coupon name: <span title="Required field">*</span></label>
							<input type="text" name="coupon_name" size="50" value="<?php echo $row['coupon_name']; ?>" />
						</p>
											
						<p>
							<label>Code: <span title="Required field">*</span></label>
							<input type="text" name="coupon_code" size="50" value="<?php echo $row['coupon_code']; ?>" />
						</p>
						
						<p>
							<label>Type:</label>
							<select name="coupon_type">
								<option value="P" <?php if ($row['coupon_type'] == 'P') echo 'selected="selected"' ?>>Percentage</option>
								<option value="F" <?php if ($row['coupon_type'] == 'F') echo 'selected="selected"' ?>>Fixed amount</option>
							</select>
						</p>

						<p>
							<label>Discount: <span title="Required field">*</span></label>
							<input type="text" name="coupon_discount" size="15" value="<?php echo $row['coupon_discount']; ?>" />
						</p>

						<p>
							<label>Date start: <span title="Required field">*</span></label>
							<select name="date_start_1">
								<option value="">-- Day --</option>
								<?php for ($i = 1; $i < 32; $i++): ?>
									<option value="<?php echo $i; ?>" <?php if (date('d', strtotime($row['date_start'])) == $i) echo 'selected="selected"' ?>><?php echo $i; ?></option>
								<?php endfor; ?>
							</select>
												
							<select name="date_start_2">
								<option value="">-- Month --</option>
								<?php for ($i = 1; $i < 13; $i++): ?>
									<option value="<?php echo $i; ?>" <?php if (date('m', strtotime($row['date_start'])) == $i) echo 'selected="selected"' ?>><?php echo $i; ?></option>
								<?php endfor; ?>
							</select>						
									
							<select name="date_start_3">
								<option value="">-- Year --</option>
								<?php for ($i = '2011'; $i < date('Y') + 5; $i++): ?>
									<option value="<?php echo $i; ?>" <?php if (date('Y', strtotime($row['date_start'])) == $i) echo 'selected="selected"' ?>><?php echo $i; ?></option>
								<?php endfor; ?>
							</select>
						</p>

						<p>
							<label>Date end: <span title="Required field">*</span></label>
							<select name="date_end_1">
								<option value="">-- Day --</option>
								<?php for ($i = 1; $i < 32; $i++): ?>
									<option value="<?php echo $i; ?>" <?php if (date('d', strtotime($row['date_end'])) == $i) echo 'selected="selected"' ?>><?php echo $i; ?></option>
								<?php endfor; ?>
							</select>
												
							<select name="date_end_2">
								<option value="">-- Month --</option>
								<?php for ($i = 1; $i < 13; $i++): ?>
									<option value="<?php echo $i; ?>" <?php if (date('m', strtotime($row['date_end'])) == $i) echo 'selected="selected"' ?>><?php echo $i; ?></option>
								<?php endfor; ?>
							</select>						
												
							<select name="date_end_3">
								<option value="">-- Year --</option>
								<?php for ($i = '2011'; $i < date('Y') + 5; $i++): ?>
									<option value="<?php echo $i; ?>" <?php if (date('Y', strtotime($row['date_end'])) == $i) echo 'selected="selected"' ?>><?php echo $i; ?></option>
								<?php endfor; ?>
							</select>
						</p>

						<p>
							<label>Status:</label>								
							<?php if ($row['coupon_status']): ?>
								<input type="radio" name="coupon_status" value="1" checked="checked" /> Active
								<input type="radio" name="coupon_status" value="0" /> Inactive
							<?php else: ?>
								<input type="radio" name="coupon_status" value="1" /> Active
								<input type="radio" name="coupon_status" value="0" checked="checked" /> Inactive
							<?php endif; ?>
						</p>
																		
						<p>
							<button type="submit" name="submit" class="button orange">Save</button>
						</p>

					<?php endforeach; ?>

				<?php else: ?>

					<div class="alert info">
						<p>Coupon not found.</p>
					</div>

				<?php endif; ?>	
									
			</form>
									
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

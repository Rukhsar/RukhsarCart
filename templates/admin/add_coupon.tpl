<?php require_once('header' . config_item('template', 'template_extension')); ?>

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
							
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">

			<!-- BEGIN .title_background -->
			<div class="title_background">
				<h1>New coupon</h1>
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

			<?php if (isset($success)): ?>
				<div class="alert success">
					<p>
						<span>Coupon added successfully!</span>
					</p>
				</div>
			<?php endif; ?>
			
			<form action="#" method="post">
									
				<p>
					<label>Coupon name: <span title="Required field">*</span></label>
					<input type="text" name="coupon_name" size="50" value="<?php if (isset($_POST['coupon_name'])) echo $_POST['coupon_name']; ?>" />
				</p>
									
				<p>
					<label>Code: <span title="Required field">*</span></label>
					<input type="text" name="coupon_code" size="50" value="<?php if (isset($_POST['coupon_code'])) echo $_POST['coupon_code']; ?>" />
				</p>
				
				<p>
					<label>Type:</label>
					<select name="coupon_type">
						<option value="P">Percentage</option>
						<option value="F">Fixed amount</option>
					</select>
				</p>

				<p>
					<label>Discount: <span title="Required field">*</span></label>
					<input type="text" name="coupon_discount" size="15" value="<?php if (isset($_POST['coupon_discount']) && empty($_POST['coupon_discount'])) echo '0'; ?>" />
				</p>

				<p>
					<label>Date start: <span title="Required field">*</span></label>
					<select name="date_start_1">
						<option value="">-- Day --</option>
						<?php for ($i = 1; $i < 32; $i++): ?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php endfor; ?>
					</select>
										
					<select name="date_start_2">
						<option value="">-- Month --</option>
						<?php for ($i = 1; $i < 13; $i++): ?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php endfor; ?>
					</select>						
										
					<select name="date_start_3">
						<option value="">-- Year --</option>
						<?php for ($i = '2011'; $i < date('Y') + 5; $i++): ?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php endfor; ?>
					</select>						
				</p>

				<p>
					<label>Date end: <span title="Required field">*</span></label>
					<select name="date_end_1">
						<option value="">-- Day --</option>
						<?php for ($i = 1; $i < 32; $i++): ?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php endfor; ?>
					</select>
										
					<select name="date_end_2">
						<option value="">-- Month --</option>
						<?php for ($i = 1; $i < 13; $i++): ?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php endfor; ?>
					</select>						
										
					<select name="date_end_3">
						<option value="">-- Year --</option>
						<?php for ($i = '2011'; $i < date('Y') + 5; $i++): ?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php endfor; ?>
					</select>
				</p>

				<p>
					<label>Status:</label>
					<input type="radio" name="coupon_status" value="1" checked="checked" /> Active
					<input type="radio" name="coupon_status" value="0" /> Inactive
				</p>
																
				<p>
					<button type="submit" name="submit" class="button orange">Save</button>
				</p>
									
			</form>
									
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

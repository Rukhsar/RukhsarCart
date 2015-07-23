<?php require_once('header' . config_item('template', 'template_extension')); ?>

<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
	
	//TinyMCE
	$('textarea.product_description').tinymce({
		
		//Location of TinyMCE script
		script_url : '<?php echo config_item('template', 'site_url'); ?>templates/admin/js/tiny_mce/tiny_mce.js',
		
		//General options
		theme : "simple",
		width : "700",
		height : "250"
		
	});
	
	//Tabs
	$('#tabs').tabs({ 
		cookie: { expires: 30 } 
	});
	
	var url;
			
	$('#dialog:ui-dialog').dialog('destroy');

	$('#dialog_confirm').dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		height: 180,
		width: 450,
		modal: true,
		create: function(event, ui) {
			$('.ui-button').addClass('button orange');
		},
		buttons: {
			'Delete': function() {
				window.location.href = url;
			},
			'Cancel': function() {
				$(this).dialog('close');
			}
		}
	});
	
	$('.delete_image').click(function() {
		
		url = $(this).attr('href');
		
		$('#dialog_confirm').dialog('open');

		return false;
		
	});
						
});
// ]]>
</script>

	<!-- BEGIN #dialog_confirm -->
	<div id="dialog_confirm" title="Delete item(s)?">
		<p>
			These item(s) will be permanently deleted and cannot be recovered. Are you sure?
		</p>
	</div>
	<!-- END #dialog_confirm -->

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
							
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">

			<!-- BEGIN .title_background -->
			<div class="title_background">
				<h1>Edit product</h1>
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

			<!-- BEGIN #tabs -->
			<div id="tabs">
			
				<ul>
					<li><a href="#tab-1">General</a></li>
					<li><a href="#tab-2">Data</a></li>
					<li><a href="#tab-3">Options</a></li>
					<li><a href="#tab-4">Gallery</a></li>
				</ul>
				
				<form action="#" enctype="multipart/form-data" method="post">
					
					<?php if (isset($_GET['product_id']) && $row_count > 0): ?>
						
						<?php foreach ($product_details as $row): ?>
						
							<div id="tab-1">

								<p>
									<input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>" />
								</p>
															
								<p>
									<label>Category: <span title="Required field">*</span></label>
									<select name="category_id">
										<option value="">-- None --</option>
										<?php foreach (categories(0) as $value): ?>
											<?php if ($value['category_id'] == $row['category_id']): ?>
												<option value="<?php echo $value['category_id']; ?>" selected="selected"><?php echo $value['category_name']; ?></option>
											<?php else: ?>	
												<option value="<?php echo $value['category_id']; ?>"><?php echo $value['category_name']; ?></option>
											<?php endif; ?>
										<?php endforeach; ?>
									</select>
								</p>
																			
								<p>
									<label>Name: <span title="Required field">*</span></label>
									<input type="text" name="product_name" size="30" value="<?php echo $row['product_name']; ?>" />
								</p>

								<p>
									<label>Description:</label>
								</p>
													
								<p>
									<textarea name="product_description" class="product_description">
										<?php echo $row['product_description']; ?>
									</textarea>
								</p>
								
							</div>
							
							<div id="tab-2">

								<p>
									<label>Image:</label>
									<input type="file" name="image" />
									<?php if (is_null($row['product_image'])): ?>
										<a href="<?php echo config_item('cart', 'site_url'); ?>uploads/images/no_image.png" target="_blank" class="image_preview">
											<img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/image_preview.png" alt="" />
										</a>
									<?php else: ?>
										<a href="<?php echo config_item('cart', 'site_url'); ?>uploads/images/<?php echo $row['product_thumbnail']; ?>" target="_blank" class="image_preview">
											<img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/image_preview.png" alt="" />
										</a>
									<?php endif; ?>
								</p>

								<p>
									<label>Price: <span title="Required field">*</span></label>
									<input type="text" name="product_price" size="10" value="<?php echo $row['product_price']; ?>" />
									<img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/help.png" alt="" title="Enter quantity of product." />
								</p>

								<p>
									<label>Quantity: <span title="Required field">*</span></label>
									<input type="text" name="product_quantity" size="10" value="<?php echo $row['product_quantity']; ?>" />
									<img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/help.png" alt="" title="Enter quantity of product." />
								</p>

								<p>
									<label>Requires shipping:</label>
									<?php if ($row['shipping']): ?>
										<input type="radio" name="shipping" value="1" checked="checked" /> Yes
										<input type="radio" name="shipping" value="0" /> No
									<?php else: ?>
										<input type="radio" name="shipping" value="1" /> Yes
										<input type="radio" name="shipping" value="0" checked="checked" /> No
									<?php endif; ?>
								</p>

								<p>
									<label>Downloadable product:</label>
									<?php if ($db->row_count("SELECT digital_good_id FROM " . config_item('cart', 'table_digital_goods') . " WHERE product_id = '" . $_GET['product_id'] . "'") > 0) : ?>
										<input type="checkbox" name="digital_good" value="true" checked />
									<?php else: ?>
										<input type="checkbox" name="digital_good" value="true" />
									<?php endif; ?>
								</p>

								<p>
									<label>Product file:</label>
									<input type="file" name="file" />
									<?php if ($digital_goods['filename']): ?>
										Current file: <a href="?download&digital_good_id=<?php echo $digital_goods['digital_good_id']; ?>"><?php echo $digital_goods['filename']; ?></a>
									<?php endif; ?>
								</p>

								<p>
									<label>Product expiry:</label>											
									<?php if ($db->row_count("SELECT digital_good_id FROM " . config_item('cart', 'table_digital_goods') . " WHERE product_id = '" . $_GET['product_id'] . "'") > 0) : ?>

										<?php if ($digital_goods['number_days']): ?>
											<input type="text" name="expiry" size="5" value="<?php echo $digital_goods['number_days']; ?>" />
											<input type="radio" name="expiry_type" value="days" checked="checked" /> Days
											<input type="radio" name="expiry_type" value="downloads" /> Downloads
										<?php endif; ?>
										
										<?php if ($digital_goods['number_downloadable']): ?>
											<input type="text" name="expiry" size="5" value="<?php echo $digital_goods['number_downloadable']; ?>" />
											<input type="radio" name="expiry_type" value="days" /> Days
											<input type="radio" name="expiry_type" value="downloads" checked="checked" /> Downloads
										<?php endif; ?>
									
									<?php else: ?>
										<input type="text" name="expiry" size="5" />
										<input type="radio" name="expiry_type" value="days" checked="checked" /> Days
										<input type="radio" name="expiry_type" value="downloads" /> Downloads
									<?php endif; ?>
								</p>
													
							</div>
							
							<div id="tab-3">

								<div class="grid_6">
									<p>
										Option name:
										<input type="text" id="option_name" size="35" />
									</p>					
								</div>
								
								<div class="grid_5">
									<p>
										<a href="#" id="add_option" class="button orange">Add option</a>
									</p>					
								</div>
								
								<div class="clear"></div>
																
								<div id="options">
									
									<?php $option_row = 0; $option_value_row = 0; ?>
																				
									<?php foreach ($cart->get_product_options($_GET['product_id']) as $value): ?>
									
										<div id="option<?php echo $option_row; ?>" class="grid_11">
														
											<table class="table">
												<thead>
													<tr>
														<th>Option name</th>
														<th>Position</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>

													<tr>
														<td><input type="text" name="product_option[<?php echo $option_row; ?>][name]" value="<?php echo $value['option_name']; ?>" /></td>
														<td><input type="text" name="product_option[<?php echo $option_row; ?>][position]" value="<?php echo $value['position']; ?>" size="5" /></td>
														<td>
															<a href="#" id="add_<?php echo $option_row; ?>" class="add_option_value button orange">Add option value</a>
															<a href="#" id="remove_<?php echo $option_row; ?>" class="remove_option button orange">Remove</a>
														</td>
													</tr>
																	
												</tbody>
											</table>

											<?php foreach ($value['option_values'] as $value_options): ?>
											
												<div id="option<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" class="grid_7 push_1">

													<table class="table">
														<thead>
															<tr>
																<th>Option value</th>
																<th>Price</th>
																<th>Quantity</th>
																<th>Type</th>
																<th>Position</th>
																<th>Action</th>
															</tr>
														</thead>
														<tbody>

															<tr>
																<td><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][value]" value="<?php echo $value_options['option_value']; ?>" /></td>
																<td><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price]" value="<?php echo $value_options['option_price']; ?>" size="5" /></td>
																<td><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][quantity]" value="<?php echo $value_options['option_quantity']; ?>" size="5" /></td>
																<td>
																	<select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][type]">
																		<option value="+" <?php if ($value_options['option_type'] == '+') echo 'selected="selected"' ?>>+</option>
																		<option value="-" <?php if ($value_options['option_type'] == '-') echo 'selected="selected"' ?>>-</option>
																	</select>
																</td>
																<td><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][position]" value="<?php echo $value_options['position']; ?>" size="5" /></td>
																<td><a href="#" id="remove_<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" class="remove_option_value button orange">Remove</a></td>
															</tr>
																		
														</tbody>
													</table>
																				
												</div>
												
												<?php $option_value_row++; ?>
												
											<?php endforeach; ?>
																										
										</div>												
																						
										<?php $option_row++; ?>
										
									<?php endforeach; ?>
									
								</div>
															
								<div class="clear"></div>
								
							</div>

							<div id="tab-4">

								<div class="grid_11">
									<div class="alert info">
										<strong>Here you add additional images to your product. Click Add image button to select file and press Save to upload file.</strong>
									</div>
								</div>

								<?php $image_row = 0; ?>
							
								<div id="images" class="grid_11">
									
									<table class="table">
										<thead>
											<tr>
												<th>File name</th>
												<th>&nbsp;</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($cart->get_product_images($_GET['product_id']) as $value): ?>

												<tr id="image<?php echo $image_row; ?>">
													<td>
														<input type="hidden"name="product_image[<?php echo $image_row; ?>]" value="<?php $value['image']; ?>" />
														<a href="<?php echo config_item('cart', 'site_url'); ?>uploads/images/<?php echo $value['thumbnail']; ?>" target="_blank" class="image_preview">
															<img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/image_preview.png" alt="" />
														</a>
													</td>
													<td><a href="edit_product.php?product_id=<?php echo $_GET['product_id']; ?>&delete_image=1&image_id=<?php echo $value['image_id']; ?>" id="remove_<?php echo $image_row; ?>" class="delete_image button orange">Remove</a></td>
												</tr>
														
												<?php $image_row++; ?>
											
											<?php endforeach; ?>
										</tbody>
										<tfoot>
											<tr>
												<td><a href="" id="add_image" class="button orange">Add image</a></td>
												<td>&nbsp;</td>
											</tr>
										</tfoot>
									</table>
								
								</div>
											
							</div>
											
							<p>
								<button type="submit" name="submit" class="button orange">Save</button>
							</p>
						
						<?php endforeach; ?>
						
					<?php else: ?>

						<div class="alert info">
							<p>Product not found.</p>
						</div>
				
					<?php endif; ?>	
										
				</form>
				
			</div>
			<!-- END #tabs -->
																		
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
	</div>
	<!-- END #main -->

<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
	
	var option_row = '<?php echo $option_row; ?>';

	$('#add_option').click(function() {
		
		if ($.trim($('#option_name').val()) == '') {
		
			alert('Please enter option name!');
		
		} else {
		
			html  = '<div id="option' + option_row + '" class="grid_11">';
			
			html += '<table class="table">';
			html += '<thead>';
			html += '<tr>';
			html += '<th>Option name</th>';
			html += '<th>Position</th>';
			html += '<th>Action</th>';
			html += '</tr>';
			html += '</thead>';
			html += '<tbody>';

			html += '<tr>';
			html += '<td><input type="text" name="product_option[' + option_row + '][name]" value="' + $('#option_name').val() + '" /></td>';
			html += '<td><input type="text" name="product_option[' + option_row + '][position]" size="5" /></td>';
			html += '<td>';
			html += '<a href="#" id="add_' + option_row + '" class="add_option_value button orange">Add option value</a>';
			html += '<a href="#" id="remove_' + option_row + '" class="remove_option button orange">Remove</a>';
			html += '</td>';
			html += '</tr>';
						
			html += '</tbody>';
			html += '</table>';
			
			html += '</div>';
				 
			$('#options').append(html);
			
			option_row++;
			
		}
		
		return false;
		
	});

	$('.remove_option').live('click', function() {
			
		$('#option' + $(this).attr('id').split('_')[1]).remove();
		
		return false;
		
	});
	
	var option_value_row = '<?php echo $option_value_row; ?>';

	$('.add_option_value').live('click', function() {
		
		html  = '<div id="option' + $(this).attr('id').split('_')[1] + '_' + option_value_row + '" class="grid_7 push_1">';

		html += '<table class="table">';
		html += '<thead>';
		html += '<tr>';
		html += '<th>Option value</th>';
		html += '<th>Price</th>';
		html += '<th>Quantity</th>';
		html += '<th>Type</th>';
		html += '<th>Position</th>';
		html += '<th>Action</th>';
		html += '</tr>';
		html += '</thead>';
		html += '<tbody>';

		html += '<tr>';
		html += '<td><input type="text" name="product_option[' + $(this).attr('id').split('_')[1] + '][product_option_value][' + option_value_row + '][value]" value="Option value ' + option_value_row + '" /></td>';
		html += '<td><input type="text" name="product_option[' + $(this).attr('id').split('_')[1] + '][product_option_value][' + option_value_row + '][price]" size="5" /></td>';
		html += '<td><input type="text" name="product_option[' + $(this).attr('id').split('_')[1] + '][product_option_value][' + option_value_row + '][quantity]" size="5" /></td>';
		html += '<td>';
		html += '<select name="product_option[' + $(this).attr('id').split('_')[1] + '][product_option_value][' + option_value_row + '][type]">';
		html += '<option value="+">+</option>';
		html += '<option value="-">-</option>';
		html += '</select>';
		html += '</td>';
		html += '<td><input type="text" name="product_option[' + $(this).attr('id').split('_')[1] + '][product_option_value][' + option_value_row + '][position]" size="5" /></td>';
		html += '<td><a href="#" id="remove_' + $(this).attr('id').split('_')[1] + '_' + option_value_row + '" class="remove_option_value button orange">Remove</a></td>';
		html += '</tr>';
					
		html += '</tbody>';
		html += '</table>';
							
		html += '</div>';
		
		$('#options > #option' + $(this).attr('id').split('_')[1]).append(html);
		
		option_value_row++;
		
		return false;
		
	});

	$('.remove_option_value').live('click', function() {
		
		var data =  $(this).attr('id').split('_');

		$('#option' + data[1] + '_' + data[2]).remove();
		
		return false;
		
	});

	var image_row = <?php echo $image_row; ?>;
	
	$('#add_image').click(function() {

		html  = '<tr id="image' + image_row + '">';
		html += '<td><input type="file" name="product_image[' + image_row + ']" /></td>';
		html += '<td><a href="#" id="remove_' + image_row + '" class="remove_image button orange">Remove</a></td>';
		html += '</tr>';
			 
		$('#images table > tbody').append(html);
		
		image_row++;
		
		return false;
			
	});


	$('.remove_image').live('click', function() {
		
		$('#image' + $(this).attr('id').split('_')[1]).remove();
		
		return false;
		
	});
										
});
// ]]>
</script>

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

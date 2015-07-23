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
	
	var option_row = 0;

	$('#add_option').click(function() {
		
		if ($.trim($('#option_name').val()) == '') {
		
			alert('Please enter option name.');
		
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
	
	var option_value_row = 0;

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
	
	var image_row = 0;
	
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

	<!-- BEGIN #main -->
	<div id="main" class="container_12">
							
		<!-- BEGIN .grid_12 -->
		<div class="grid_12">

			<!-- BEGIN .title_background -->
			<div class="title_background">
				<h1>New product</h1>
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
						<span>Product added successfully!</span>
					</p>
				</div>
			<?php endif; ?>

			<!-- BEGIN #tabs -->
			<div id="tabs">
			
				<ul>
					<li><a href="#tab-1">General</a></li>
					<li><a href="#tab-2">Data</a></li>
					<li><a href="#tab-3">Options</a></li>
					<li><a href="#tab-4">Gallery</a></li>
				</ul>
				
				<form action="#" enctype="multipart/form-data" method="post">
					
					<div id="tab-1">
						
						<p>
							<label>Category: <span title="Required field">*</span></label>
							<select name="category_id">
								<option value="">-- None --</option>
								<?php foreach (categories(0) as $value): ?>
									<option value="<?php echo $value['category_id']; ?>"><?php echo $value['category_name']; ?></option>
								<?php endforeach; ?>
							</select>
						</p>
																	
						<p>
							<label>Name: <span title="Required field">*</span></label>
							<input type="text" name="product_name" size="50" value="<?php if (isset($_POST['product_name'])) echo $_POST['product_name']; ?>" />
						</p>

						<p>
							<label>Description:</label>
						</p>
											
						<p>
							<textarea name="product_description" class="product_description">
								<?php if (isset($_POST['product_description'])): ?>
									<?php echo $_POST['product_description']; ?>
								<?php else: ?>
									Product description.
								<?php endif; ?>
							</textarea>
						</p>
						
					</div>
					
					<div id="tab-2">

						<p>
							<label>Image:</label>
							<input type="file" name="image" />
						</p>

						<p>
							<label>Price: <span title="Required field">*</span></label>
							<input type="text" name="product_price" size="10" value="<?php if (isset($_POST['product_price'])) echo $_POST['product_price']; ?>" />
							<img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/help.png" alt="" title="Enter price in 0.00 format." />
						</p>

						<p>
							<label>Quantity: <span title="Required field">*</span></label>
							<input type="text" name="product_quantity" size="10" value="<?php if (isset($_POST['product_quantity'])) echo $_POST['product_quantity']; ?>" />
							<img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/help.png" alt="" title="Enter quantity of product." />
						</p>

						<p>
							<label>Requires shipping:</label>
							<?php if (isset($_POST['shipping']) && $_POST['shipping'] == 1): ?>
								<input type="radio" name="shipping" value="1" checked="checked" /> Yes
								<input type="radio" name="shipping" value="0" /> No
							<?php else: ?>
								<input type="radio" name="shipping" value="1" /> Yes
								<input type="radio" name="shipping" value="0" checked="checked" /> No
							<?php endif; ?>
						</p>

						<p>
							<label>Downloadable product:</label>
							<?php if (isset($_POST['digital_good'])): ?>
								<input type="checkbox" name="digital_good" value="true" checked="checked" />
							<?php else: ?>
								<input type="checkbox" name="digital_good" value="true" />
							<?php endif; ?>
						</p>

						<p>
							<label>Product file:</label>
							<input type="file" name="file" />
						</p>

						<p>
							<label>Product expiry:</label>
							<input type="text" name="expiry" size="10" value="<?php if (isset($_POST['expiry'])) echo $_POST['expiry']; ?>" />
							<input type="radio" name="expiry_type" value="days" checked="checked" /> Days
							<input type="radio" name="expiry_type" value="downloads" /> Downloads
						</p>
											
					</div>
					
					<div id="tab-3">

						<div class="grid_6">
							<p>
								Option name:
								<input type="text" id="option_name" size="35"/>
							</p>					
						</div>
						
						<div class="grid_5">
							<p>
								<a href="#" id="add_option" class="button orange">Add option</a>
							</p>					
						</div>
						
						<div class="clear"></div>
																				
						<div id="options"></div>
													
						<div class="clear"></div>
						
					</div>
					
					<div id="tab-4">

						<div class="grid_11">
							<div class="alert info">
								<strong>Here you add additional images to your product. Click Add image button to select file and press Save to upload file.</strong>
							</div>
						</div>
						
						<div id="images" class="grid_11">
							
							<table class="table">
								<thead>
									<tr>
										<th>File name</th>
										<th>&nbsp;</th>
									</tr>
								</thead>
								<tbody>

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
					
				</form>
				
			</div>
			<!-- END #tabs -->
																		
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

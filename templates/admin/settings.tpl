<?php require_once('header' . config_item('template', 'template_extension')); ?>

<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
	
	//Tabs
	$('#tabs').tabs({ 
		cookie: { expires: 30 } 
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
				<h1>Settings</h1>
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
						<span>Settings edited successfully!</span>
					</p>
				</div>
			<?php endif; ?>
			
			<form action="#" method="post">
									
				<!-- BEGIN #tabs -->
				<div id="tabs">
				
					<ul>
						<li><a href="#tab-1">General</a></li>
						<li><a href="#tab-2">Cart</a></li>
						<li><a href="#tab-3">Upload</a></li>
					</ul>

					<form action="#" method="post">
						
						<div id="tab-1">
											
							<p>
								<label>Site title: <span title="Required field">*</span></label>
								<input type="text" name="site_title" size="50" value="<?php echo config_item('cart', 'site_title'); ?>" />
							</p>
												
							<p>
								<label>Site url: <span title="Required field">*</span></label>
								<input type="text" name="site_url" size="50" value="<?php echo config_item('cart', 'site_url'); ?>" />
							</p>
												
							<p>
								<label>Admin email: <span title="Required field">*</span></label>
								<input type="text" name="admin_email" size="50" value="<?php echo config_item('cart', 'admin_email'); ?>" />
							</p>

							<p>
								<label>Customer registration:</label>					
								<select id="type_registration" name="type_registration">
									<option value="0">Immediate registration</option>
									<option value="1" <?php if (config_item('authentication', 'email_activation') == 1) echo 'selected="selected"'; ?>>Activation by email</option>
									<option value="2" <?php if (config_item('authentication', 'approve_user_registration') == 1) echo 'selected="selected"'; ?>>Approval by the administrator</option>
								</select>
							</p>

							<p>
								<label>Items per page (Catalog): <span title="Required field">*</span></label>
								<input type="text" name="per_page_catalog" size="10" value="<?php echo config_item('cart', 'per_page_catalog'); ?>" />
							</p>

							<p>
								<label>Items per page (Admin): <span title="Required field">*</span></label>
								<input type="text" name="per_page_admin" size="10" value="<?php echo config_item('cart', 'per_page_admin'); ?>" />
							</p>

							<p>
								<input type="hidden" name="secret_word" value="<?php echo config_item('authentication', 'secret_word'); ?>" />
							</p>

							<p>
								<label>New order notification:</label>					
								<select id="new_order_notification" name="new_order_notification">
									<option value="0" <?php if (config_item('cart', 'new_order_notification') == 0) echo 'selected="selected"'; ?>>No</option>
									<option value="1" <?php if (config_item('cart', 'new_order_notification') == 1) echo 'selected="selected"'; ?>>Yes</option>
								</select>
							</p>
													
						</div>
						
						<div id="tab-2">
							
							<p>
								<label>Currency:</label>
								<select name="currency">
									<option value="&amp#1583;&amp#46;&amp#1573;|AED" <?php if (config_item('cart', 'currency_code') == 'AED') echo 'selected="selected"'; ?>>United Arab Emirates Dirham</option>
									<option value="&amp#36;|AUD" <?php if (config_item('cart', 'currency_code') == 'AUD') echo 'selected="selected"'; ?>>Australian Dollar</option>
									<option value="&amp#82;&amp#36;|BRL" <?php if (config_item('cart', 'currency_code') == 'BRL') echo 'selected="selected"'; ?>>Brazilian Real</option>
									<option value="&amp#36;|CAD" <?php if (config_item('cart', 'currency_code') == 'CAD') echo 'selected="selected"'; ?>>Canadian Dollar</option>
									<option value="&amp#67;&amp#72;&amp#70;|CHF" <?php if (config_item('cart', 'currency_code') == 'CHF') echo 'selected="selected"'; ?>>Swiss Franc</option>
									<option value="&amp#165;|CNY" <?php if (config_item('cart', 'currency_code') == 'CNY') echo 'selected="selected"'; ?>>Chinese Yuan</option>
									<option value="&amp#75;&amp#269;|CZK" <?php if (config_item('cart', 'currency_code') == 'CZK') echo 'selected="selected"'; ?>>Czech Koruna</option>
									<option value="&amp#107;&amp#114;|DKK" <?php if (config_item('cart', 'currency_code') == 'DKK') echo 'selected'; ?>>Danish Krone</option>
									<option value="&amp#8364;|EUR" <?php if (config_item('cart', 'currency_code') == 'EUR') echo 'selected="selected"'; ?>>Euro</option>
									<option value="&amp#163;|GBP" <?php if (config_item('cart', 'currency_code') == 'GBP') echo 'selected="selected"'; ?>>Pound Sterling</option>
									<option value="&amp#36;|HKD" <?php if (config_item('cart', 'currency_code') == 'HKD') echo 'selected="selected"'; ?>>Hong Kong Dollar</option>
									<option value="&amp#107;&amp#110;|HRK" <?php if (config_item('cart', 'currency_code') == 'HRK') echo 'selected="selected"'; ?>>Croatia Kuna</option>
									<option value="&amp#70;&amp#116;|HUF" <?php if (config_item('cart', 'currency_code') == 'HUF') echo 'selected="selected"'; ?>>Hungary Forint</option>
									<option value="&amp#82;&amp#112;|IDR" <?php if (config_item('cart', 'currency_code') == 'IDR') echo 'selected="selected"'; ?>>Indonesia Rupiah</option>
									<option value="&amp#8362;|ILS" <?php if (config_item('cart', 'currency_code') == 'ILS') echo 'selected="selected"'; ?>>Israeli Shekel</option>
									<option value="&amp#8377;|INR" <?php if (config_item('cart', 'currency_code') == 'INR') echo 'selected="selected"'; ?>>Indian Rupee</option>						
									<option value="&amp#165;|JPY" <?php if (config_item('cart', 'currency_code') == 'JPY') echo 'selected="selected"'; ?>>Japanese Yen</option>
									<option value="&amp#36;|MXN" <?php if (config_item('cart', 'currency_code') == 'MXN') echo 'selected="selected"'; ?>>Mexican Peso</option>
									<option value="&amp#82;&amp#77;|MYR" <?php if (config_item('cart', 'currency_code') == 'MYR') echo 'selected="selected"'; ?>>Malaysian Ringgit</option>
									<option value="&amp#8358;|NGN" <?php if (config_item('cart', 'currency_code') == 'NGN') echo 'selected="selected"'; ?>>Nigerian Naira</option>
									<option value="&amp#107;&amp#114;|NOK" <?php if (config_item('cart', 'currency_code') == 'NOK') echo 'selected'; ?>>Norwegian Krone</option>
									<option value="&amp#36;|NZD" <?php if (config_item('cart', 'currency_code') == 'NZD') echo 'selected="selected"'; ?>>New Zealand Dollar</option>
									<option value="&amp#8369;|PHP" <?php if (config_item('cart', 'currency_code') == 'PHP') echo 'selected="selected"'; ?>>Philippine Peso</option>
									<option value="&amp#122;&amp#322;|PLN" <?php if (config_item('cart', 'currency_code') == 'PLN') echo 'selected="selected"'; ?>>Polish Zloty</option>
									<option value="&amp#108;&amp#101;&amp#105;|RON" <?php if (config_item('cart', 'currency_code') == 'RON') echo 'selected="selected"'; ?>>Romanian New Leu</option>
									<option value="&amp#1088;&amp#1091;&amp#1073;|RUB" <?php if (config_item('cart', 'currency_code') == 'RUB') echo 'selected="selected"'; ?>>Russian Ruble</option>
									<option value="&amp#107;&amp#114;|SEK" <?php if (config_item('cart', 'currency_code') == 'SEK') echo 'selected="selected"'; ?>>Swedish Krona</option>
									<option value="&amp#36;|SGD" <?php if (config_item('cart', 'currency_code') == 'SGD') echo 'selected="selected"'; ?>>Singapore Dollar</option>
									<option value="&amp#3647;|THB" <?php if (config_item('cart', 'currency_code') == 'THB') echo 'selected="selected"'; ?>>Thai Baht</option>
									<option value="&amp#8356;|TRY" <?php if (config_item('cart', 'currency_code') == 'TRY') echo 'selected="selected"'; ?>>Turkish Lira</option>
									<option value="&amp#78;&amp#84;&amp#36;|TWD" <?php if (config_item('cart', 'currency_code') == 'TWD') echo 'selected="selected"'; ?>>Taiwan New Dollar</option>
									<option value="&amp#36;|USD" <?php if (config_item('cart', 'currency_code') == 'USD') echo 'selected="selected"'; ?>>US Dollar</option>
									<option value="&amp#82;|ZAR" <?php if (config_item('cart', 'currency_code') == 'ZAR') echo 'selected="selected"'; ?>>South Africa Rand</option>	
								</select>
							</p>
							
							<p>
								<label>Currency position:</label>
								<select name="currency_position">
									<option value="left" <?php if (config_item('cart', 'currency_position') == 'left') echo 'selected="selected"'; ?>>Left</option>
									<option value="right" <?php if (config_item('cart', 'currency_position') == 'right') echo 'selected="selected"'; ?>>Right</option>
								</select>
							</p>

							<p>
								<label>Shipping cost:</label>
								<input type="text" name="shipping_cost" size="10" value="<?php if (isset($_POST['shipping_cost']) && empty($_POST['shipping_cost'])) echo '0'; else echo config_item('cart', 'shipping_cost'); ?>" />
							</p>

							<p>
								<label>Tax description:</label>
								<input type="text" name="tax_description" size="30" value="<?php echo config_item('cart', 'tax_description'); ?>" />
							</p>

							<p>
								<label>Tax rate:</label>
								<input type="text" name="tax_rate" size="10" value="<?php if (isset($_POST['tax_rate']) && empty($_POST['tax_rate'])) echo '0'; else echo config_item('cart', 'tax_rate'); ?>" />
								<input type="checkbox" name="tax_shipping" value="true" <?php if (config_item('cart', 'tax_shipping') == 1) echo 'checked="checked"'; ?> /> Apply to shipping
							</p>

							<p>
								<label>Paypal email: <span title="Required field">*</span></label>
								<input type="text" name="paypal_email" size="50" value="<?php echo config_item('cart', 'paypal_email'); ?>" />
							</p>

							<p>
								<label>PayPal cancel return: <span title="Required field">*</span></label>
								<input type="text" name="paypal_cancel_return" size="50" value="<?php echo config_item('cart', 'paypal_cancel_return'); ?>" />
							</p>
							
							<p>
								<label>Paypal sandbox:</label>
								<input type="radio" name="paypal_sandbox" value="1" <?php if (config_item('cart', 'paypal_sandbox') == 1) echo 'checked="checked"'; ?> /> Active
								<input type="radio" name="paypal_sandbox" value="0" <?php if (config_item('cart', 'paypal_sandbox') == 0) echo 'checked="checked"'; ?> /> Inactive
							</p>
						
						</div>
						
						<div id="tab-3">
							
							<p>
								<label> The maximum size (bytes): <span title="Required field">*</span></label>
								<input type="text" name="max_filesize" size="15" value="<?php echo config_item('upload', 'max_filesize'); ?>" />
							</p>
							
							<p>
								<label>Image thumbnail size: <span title="Required field">*</span></label>
								<input type="text" name="max_width_thumbnail" size="10" value="<?php echo config_item('upload', 'max_width_thumbnail'); ?>" />
								x
								<input type="text" name="max_height_thumbnail" size="10" value="<?php echo config_item('upload', 'max_height_thumbnail'); ?>" />
								<img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/help.png" alt="" title="The maximum width and height." />
								<br />
								<label>&nbsp;</label>
								<input type="checkbox" id="crop_thumbnail" name="crop_thumbnail" value="1" <?php if (config_item('upload', 'crop_thumbnail') == 1) echo 'checked="checked"'; ?> />
								<span>Crop thumbnail to exact dimensions.</span>
							</p>
							
							<p>
								<label>Image size: <span title="Required field">*</span></label>
								<input type="text" name="max_width" size="10" value="<?php echo config_item('upload', 'max_width'); ?>" />
								x
								<input type="text" name="max_height" size="10" value="<?php echo config_item('upload', 'max_height'); ?>" />
								<img src="<?php echo config_item('template', 'site_url'); ?>templates/admin/images/help.png" alt="" title="The maximum width and height." />
							</p>
						
						</div>
																												
						<p>
							<button type="submit" name="submit" class="button orange">Save</button>
						</p>
											
					</form>
				
				</div>
				<!-- END #tabs -->
									
			</form>
									
		</div>
		<!-- END .grid_12 -->
		
		<div class="clear">&nbsp;</div>
		
	</div>
	<!-- END #main -->

<?php require_once('footer' . config_item('template', 'template_extension')); ?>

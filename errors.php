<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.'); ?>

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

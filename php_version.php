<?php
	add_action( 'admin_notices', function() use ($name, $min_php){
		?>
		<div class="update-nag">
			<?php echo 'You need to update your PHP version to run '. $name; ?> <br/>
			<?php echo 'Actual version is:'; ?>
			<strong><?php echo phpversion(); ?></strong>, <?php echo 'required is'; ?>
			<strong><?php echo $min_php; ?></strong>
		</div>
		<?php
	} );
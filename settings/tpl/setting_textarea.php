<?php
if ( $code_editor ) {
	if ( empty( $code_editor ) ) {
		$code_editor = 'css';
	}

	wp_enqueue_code_editor( array( 'type' => 'text/' . $code_editor ) );

	echo '<script>jQuery( document ).ready( function() {
			wp.codeEditor.initialize( jQuery( "#' . $ID . '" ), { mode: "' . $code_editor . '" } );
		});
</script>';
}
?>
<h4><?php echo $title; ?></h4>
<label for="<?php echo $ID; ?>">
	<textarea style="height:200px;"
		data-sv_type="sv_form_field"
		class="sv_input"
		id="<?php echo $ID; ?>"
		name="<?php echo $name; ?>"
		<?php echo $required; ?>
		<?php echo $disabled; ?>><?php echo esc_textarea($value); ?></textarea>
</label>
<div class="description"><?php echo $description; ?></div>
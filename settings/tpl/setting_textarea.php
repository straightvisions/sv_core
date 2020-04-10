<?php
if ( $props['code_editor'] ) {
	if ( empty( $props['code_editor'] ) ) {
		$props['code_editor'] = 'css';
	}

	wp_enqueue_code_editor( array( 'type' => 'text/' . $props['code_editor'] ) );

	echo '<script>jQuery( document ).ready( function() {
			wp.codeEditor.initialize( jQuery( "#' . $props['ID'] . '" ), { mode: "' . $props['code_editor'] . '" } );
		});
</script>';
}
?>
<label for="<?php echo $props['ID']; ?>">
	<textarea style="height:200px;"
		data-sv_type="sv_form_field"
		class="sv_input"
		id="<?php echo $props['ID']; ?>"
		name="<?php echo $props['name']; ?>"
		<?php echo $props['required']; ?>
		<?php echo $props['disabled']; ?>><?php echo esc_textarea($props['value']); ?></textarea>
</label>
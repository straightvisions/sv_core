<?php $color = esc_attr( $props['value'] ? $this->get_rgb( $props['value']) : 'transparent' ); ?>
<script>
	jQuery( document ).ready( function() {
		if ( typeof SVColorPicker !== 'undefined' ) {
			SVColorPicker.renderColorPicker( '<?php echo $props['name']; ?>', '<?php echo $color; ?>', <?php echo json_encode( get_theme_support( 'editor-color-palette' )[0] ) ?> );
		}
	} );
</script>
<div class="sv_setting_color_display" title="<?php echo __( 'Toggle Color Picker', 'sv_core' ); ?>">
	<div class="sv_setting_color_value" style="background-color:rgba(<?php echo $color; ?>)"></div>
</div>
<label for="<?php echo $props['name']; ?>" class="sv_input_label_color sv_hidden">
	<input
		data-sv_type="sv_form_field"
        data-sv_settings_type="color"
		class="sv_input"
		id="<?php echo $props['name']; ?>"
		name="<?php echo $props['name']; ?>"
		type="color"
		value="<?php echo $color; ?>"
	/>
</label>
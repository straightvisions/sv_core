<?php
$this->setting_color->localize_script( $this->get_field_id(), $this->get_data() );

$color_value = esc_attr( $this->get_rgb( $value ) );
$value = ! empty( $value ) ? 'value="' . esc_attr( $this->get_hex( $value ) ). '"' : '';
?>
<div class="sv_setting_header">
	<h4 title="<?php echo __( 'Toggle Color Picker', 'sv_core' ); ?>"><?php echo $title; ?></h4>
	<div
		class="sv_setting_color_display"
		title="<?php echo __( 'Toggle Color Picker', 'sv_core' ); ?>"
	>
		<div
			class="sv_setting_color_value"
			style="background-color:rgba(<?php echo $color_value; ?>)"></div>
	</div>
</div>
<label for="<?php echo $ID; ?>" class="sv_input_label_color sv_hidden">
	<input
		data-sv_type="sv_form_field"
		class="sv_input"
		id="<?php echo $ID; ?>"
		name="<?php echo $name; ?>"
		type="color"
<?php echo $value; ?>
	/>
</label>
<div class="description"><?php echo $description; ?></div>
<?php $color_value = isset( $value['color'] ) ? esc_attr( $this->get_rgb( $value['color'] ) ) : '0,0,0,1'; ?>

<div id="<?php echo $ID . '_color'; ?>" class="sv_setting">
	<div class="sv_setting_header">
		<h4 title="<?php _e( 'Toggle Color Picker', 'sv_core' ); ?>"><?php _e( 'Border Color', 'sv_core' ); ?></h4>
		<div
			class="sv_setting_color_display"
			title="<?php _e( 'Toggle Color Picker', 'sv_core' ); ?>"
		>
			<div
				class="sv_setting_color_value"
				style="background-color:rgba(<?php echo $color_value; ?>)"></div>
		</div>
	</div>
	<label for="<?php echo $ID . '_color'; ?>" class="sv_input_label_color sv_hidden">
		<input
			data-sv_type="sv_form_field"
			class="sv_input"
			id="<?php echo $ID . '_color'; ?>"
			name="<?php echo $name . '[color]'; ?>"
			type="color"
			value="<?php echo $this->get_hex( $color_value ); ?>"
		/>
	</label>
</div>
<div id="<?php echo $ID . '_color'; ?>" class="sv_setting">
	<div class="sv_setting_header">
		<?php 
			$color_value = esc_attr( $values[0] === 'inset' ? $values[5] : $values[4] );
        ?>
		<h4 title="<?php _e( 'Toggle Color Picker', 'sv_core' ); ?>"><?php _e( 'Shadow Color', 'sv_core' ); ?></h4>
		<div
			class="sv_setting_color_display"
			title="<?php _e( 'Toggle Color Picker', 'sv_core' ); ?>"
		>
			<div
				class="sv_setting_color_value"
				style="background-color:rgba(<?php echo $this->get_rgb( $color_value ); ?>)"></div>
		</div>
	</div>
	<label for="<?php echo $ID . '_color'; ?>" class="sv_input_label_color sv_hidden sv_setting_box_shadow_color">
		<input
			class="sv_input"
			type="color"
			id="<?php echo $ID . '_color'; ?>"
			value="<?php echo $color_value; ?>"
		/>
	</label>
</div>
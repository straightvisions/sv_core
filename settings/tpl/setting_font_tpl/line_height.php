<?php 
	$line_height_number = intval( esc_attr( preg_replace('/[^0-9]/', '', $value['height'] ) ) );
	$line_height_unit 	= esc_attr( preg_replace('/[0-9]+/', '', $value['height'] ) );
?>

<div id="<?php echo $ID . '_height'; ?>" class="sv_setting">
    <h4><?php _e( 'Line Height', 'sv100' ); ?></h4>
	<label for="<?php echo $ID . '_height'; ?>">
		<input
			class="sv_input"
			type="number"
			value="<?php echo $line_height_number; ?>"
			min="0"
		/>
		<select class="sv_input_units">
		<?php 
			foreach( $this->get_units() as $unit ) {
				echo '<option value="' . $unit . '"';
				echo $line_height_unit === $unit ? ' selected' : '';
				echo '>' . $unit . '</option>';
			}
		?>
		</select>
		<input
			data-sv_type="sv_form_field"
			class="sv_input"
			id="<?php echo $ID . '_height'; ?>"
			name="<?php echo $name . '[height]'; ?>"
			type="hidden"
			value="<?php echo esc_attr( $value['height'] ); ?>"
		/>
	</label>
</div>